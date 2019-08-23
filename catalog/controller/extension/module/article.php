<?php
class ControllerExtensionModuleArticle extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/article');

		if ($this->config->get('module_article_description')[$this->config->get('config_language_id')]['heading']) {
			$data['heading_title'] = $this->config->get('module_article_description')[$this->config->get('config_language_id')]['heading'];
		}else{
    			$data['heading_title'] = $this->language->get('heading_title');
		}

		$data['articles_link'] = $this->url->link('information/articles');

		if (isset($this->request->get['article'])) {

		if ($this->config->get('module_article_show_path') == '1') {
			$parts = explode('_', (string)$this->request->get['article']);
		} else {
			$full_path = $this->model_catalog_article->getFullPath($this->request->get['article']);
			$parts = explode('_', (string)$full_path);
		}

		} else {
			$parts = array();
		}

		if (isset($parts[0])) {
			$data['article_id'] = $parts[0];
		} else {
			$data['article_id'] = 0;
		}

		if (isset($parts[1])) {
			$data['child_id'] = $parts[1];
		} else {
			$data['child_id'] = 0;
		}

		$this->load->model('catalog/article');

		$data['articles'] = array();

		$filter_data_articles = array(
			'parent_id'  => 0,
			'start'  => 0,
			'limit'  => 1000
		);

		$articles = $this->model_catalog_article->getCategories($filter_data_articles);

		if ($articles) {

		$data['list_type'] = 1;

		foreach ($articles as $article) {

			$children_data = array();

			if ($article['article_id'] == $data['article_id']) {

			$filter_data_children = array(
				'parent_id'  => $article['article_id']
			);
				$children = $this->model_catalog_article->getCategories($filter_data_children);

				foreach($children as $child) {
				if (!$child['alternative_link']) {
					$link_to_go = $this->url->link('information/article', 'article=' . $article['article_id'] . '_' . $child['article_id']);
				}else{
					$link_to_go = $child['alternative_link'];
				}
					
					$children_data[] = array(
						'article_id' => $child['article_id'], 
						'name' => $child['name'], 
						'href' => $link_to_go
					);
			    }
			}

			$data['articles'][] = array(
				'article_id' => $article['article_id'],
				'name'        => $article['name'],
				'children'    => $children_data,
				'href'        => $this->url->link('information/article', 'article=' . $article['article_id'])
			);

		}
		} else {

			$data['list_type'] = 2;

			$filter_data_articles_2 = array(
				'parent_id'  => 0,
				'start'  => 0,
				'limit'  => 20
			);

			$articles = $this->model_catalog_article->getArticlesNoCategory($filter_data_articles_2);
	
			foreach ($articles as $article) {
	
				$data['articles'][] = array(
					'article_id' => $article['article_id'],
					'name'        => $article['name'],
					'children'    => false,
					'href'        => $this->url->link('information/article', 'article=' . $article['article_id'])
				);
			}
		}


		return $this->load->view('extension/module/article', $data);

	}
}