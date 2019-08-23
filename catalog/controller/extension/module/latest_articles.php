<?php
class ControllerExtensionModuleLatestArticles extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/latest_articles');
		
		if (isset($setting['title'][$this->config->get('config_language_id')])) {
			$data['heading_title'] = $setting['title'][$this->config->get('config_language_id')];
		} else {
			$data['heading_title'] = $setting['name'];	
		}
	
		$this->document->addScript('catalog/view/javascript/articles.js');
				
		$this->load->model('catalog/article');
		
		$this->load->model('tool/image');

		if (isset($setting['show_date'])) {
			$data['show_date'] = $setting['show_date'];
		} else {
			$data['show_date'] = '';
		}

		if (isset($setting['parent_id'])) {
			$setting_parent_id = $setting['parent_id'];
		} else {
			$setting_parent_id = 0;
		}

		$category_id = 0;
		$product_id = 0;
		$parent_id = $setting_parent_id;

		if (isset($this->request->get['path']) && !isset($this->request->get['product_id'])) {
			$parts = explode('_', (string)$this->request->get['path']);
			$category_id = (int)array_pop($parts);
			if (isset($setting['module_type']) && $setting['module_type'] == 2) {
				$articles_to_category = $this->model_catalog_article->getParentToCategory($category_id);
				if ($articles_to_category) {
					$parent_id = $articles_to_category;
				}
			}
		} 

		if (isset($this->request->get['product_id'])) {
			if (isset($setting['module_type']) && $setting['module_type'] == 3) {
				$articles_to_product = $this->model_catalog_article->getParentToProduct($this->request->get['product_id']);
				if ($articles_to_product) {
					$parent_id = $articles_to_product;
				}
			}
			$product_id = $this->request->get['product_id'];
		}
		
		$data['articles'] = array();
		
		$filter_data = array(
			'parent_id'  => $parent_id,
			'category_id'  => $category_id,
			'product_id'  => $product_id,
			'sort'  => 'a.date_added',
			'order' => 'DESC',
			'start' => 0,
			'limit' => $setting['limit']
		);

		if ($parent_id == '0') {
			$data['parent_href'] = $this->url->link('information/articles');
		}else{
			$data['parent_href'] = $this->url->link('information/article', 'article=' . $parent_id);
		}

		if (isset($setting['module_type'])) {
			if ($setting['module_type'] == 1) {
				$articles_results = $this->model_catalog_article->getLatestArticles($filter_data);
			} elseif ($setting['module_type'] == 2) {
				$articles_results = $this->model_catalog_article->getArticlesToCategory($filter_data);
			} elseif ($setting['module_type'] == 3) {
				$articles_results = $this->model_catalog_article->getArticlesToProduct($filter_data);
			}
		} else {
			$articles_results = $this->model_catalog_article->getLatestArticles($filter_data);
		}
	
		if ($articles_results) {
		foreach ($articles_results as $article) {
			if ($article['image']) {
				$image = $this->model_tool_image->resize($article['image'], $setting['width'], $setting['height'], 'w');
			} else {
				$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
			}

			$short_description = strip_tags(html_entity_decode($article['short_description'], ENT_QUOTES, 'UTF-8'));

			$description = strip_tags(html_entity_decode($article['description'], ENT_QUOTES, 'UTF-8'));

			if (strlen($short_description) > 20) {
				$desc = $short_description;
			}else{
				$desc = $description;
			}

			if (!$article['alternative_link']) {
				$link_to_go = $this->url->link('information/article', 'article=' . $article['article_id']);
			}else{
				$link_to_go = $article['alternative_link'];
			}
		
			$data['articles'][] = array(
				'article_id' => $article['article_id'],
				'thumb'   	 => $image,
				'name'    	 => $article['name'],
				'caption'    	 => utf8_substr($desc, 0, 120) . '...',
				'href'    	 => $link_to_go,
				'date'  	=> date($this->language->get('date_format_short'), strtotime($article['date_added'])),
				'viewed'  	=> $article['viewed']
			);
		}

		$data['custom_position'] = 'la-custom-off';
		$data['layout_position'] = '';
		if (isset($setting['layout_position'])) {
			if ($setting['layout_position'] == 'content_top') {
				$data['layout_position'] = ' la-top';
			} elseif ($setting['layout_position'] == 'content_bottom') {
				$data['layout_position'] = ' la-bottom';				
			}
		} else {
			$data['custom_position'] = 'la-custom-on';
		}

			return $this->load->view('extension/module/latest_articles', $data);
	    }
	}
}
