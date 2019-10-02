<?php  
class ControllerInformationArticles extends Controller {
	public function index() {
	
		$this->load->language('information/articles');
		$this->load->model('tool/image');

		$articles_description = $this->config->get('module_article_description');
		$current_language = $this->config->get('config_language_id');

		if ($articles_description[$current_language]['heading']) {
			$text_articles = $articles_description[$current_language]['heading'];
		}else{
    			$text_articles = $this->language->get('text_articles');
		}

		if ($articles_description[$current_language]['title']) {
			$title_tag = $articles_description[$current_language]['title'];
		}else{
    			$title_tag = $this->language->get('text_articles');
		}

		if ($articles_description[$current_language]['metadescription']) {
			$metadescription = $articles_description[$current_language]['metadescription'];
		}else{
    			$metadescription = '';
		}

		if ($articles_description[$current_language]['description']) {
			$module_description = $articles_description[$current_language]['description'];
		}else{
    			$module_description = '';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		$data['breadcrumbs'] = array();
		
      		$data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
		'href'      => $this->url->link('common/home')
      		);
      		
      		$data['breadcrumbs'][] = array(
        	'text'      => $text_articles,
		'href'      => $this->url->link('information/articles')
      		);
		
		$this->document->setTitle($title_tag);
		$this->document->setDescription($metadescription);

		$data['heading_title'] = $text_articles;

		if ($module_description) {
			$data['articles_description'] = html_entity_decode($module_description, ENT_QUOTES, 'UTF-8');
		}else{
			$data['articles_description'] = ''; 
		}

		$data['list_type'] = $this->config->get('module_article_show_list');

		$this->document->addScript('catalog/view/javascript/articles.js');		
		
		if (isset($this->request->get['article'])) {
			$parts = explode('_', (string)$this->request->get['article']);
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
					
		$articles = $this->model_catalog_article->getArticles(0);
		
		foreach ($articles as $result) {

			if ($this->config->get('module_article_thumb_category_width') && $this->config->get('module_article_thumb_category_height')) {
				if ($result['image']) {
				$thumb_category = $this->model_tool_image->resize($result['image'], $this->config->get('module_article_thumb_category_width'), $this->config->get('module_article_thumb_category_height'), 'w');
				} else {
				$thumb_category = $this->model_tool_image->resize('placeholder.png', $this->config->get('module_article_thumb_category_width'), $this->config->get('module_article_thumb_category_height'));
				}
                 	} else {
				$thumb_category = '';
			}


			$short_description = html_entity_decode($result['short_description'], ENT_QUOTES, 'UTF-8');

			$description = strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'));

			if (strlen(strip_tags($short_description)) > 20) {
				$desc = str_replace(array('<p>', '</p>'), '', $short_description);
			}else{
				if (strlen($description) > 20 && $this->config->get('module_article_desc_limit')) {
					$desc = utf8_substr(str_replace(array('<p>', '</p>'), '', $description), 0, $this->config->get('module_article_desc_limit')) . '...';
				}else{
					$desc = '';
				}
			}

			if (!$result['alternative_link']) {
				$link_to_go = $this->url->link('information/article', 'article=' . $result['article_id']);
				$external = false;
			}else{
				$link_to_go = $result['alternative_link'];
				$external = true;
			}

			$level_data = $this->model_catalog_article->getArticles($result['article_id']);
			
					$data['articles'][] = array(
					'article_id'  => $result['article_id'],
					'thumb_category'  => $thumb_category,
					'name'        => $result['name'],
					'description' => $desc,
					'children'    => $level_data,
					'href'        => $link_to_go,
					'date'  => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'viewed'  => $result['viewed'],
					'external'  => $external
					);

		}

		$data['latest_articles'] = array();

		if ($this->config->get('module_article_page_limit')) {
			$limit = $this->config->get('module_article_page_limit');
		}else{
    			$limit = 10;
		}

		$filter_data = array(
			'parent_id'  => '0',
			'article_id'  => '0',
			'sort'  => 'a.date_added',
			'order' => 'DESC',
			'start'              => ($page - 1) * $limit,
			'limit'              => $limit
		);
					
		if ($this->config->get('module_article_show_latest')) {			
			$latest_articles = $this->model_catalog_article->getLatestArticles($filter_data);
		}else{
			$latest_articles = $this->model_catalog_article->getArticlesList($filter_data);
		}
		
		if ($latest_articles) {
		foreach ($latest_articles as $result) {

			if ($this->config->get('module_article_thumb_width') && $this->config->get('module_article_thumb_height')) {
				if ($result['image']) {
				$thumb = $this->model_tool_image->resize($result['image'], $this->config->get('module_article_thumb_width'), $this->config->get('module_article_thumb_height'), 'w');
				} else {
				$thumb = $this->model_tool_image->resize('placeholder.png', $this->config->get('module_article_thumb_width'), $this->config->get('module_article_thumb_height'));
				}
                 	} else {
				$thumb = '';
			}

			$short_description = html_entity_decode($result['short_description'], ENT_QUOTES, 'UTF-8');

			$description = strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'));

			if (strlen(strip_tags($short_description)) > 20) {
				$desc = str_replace(array('<p>', '</p>'), '', $short_description);
			}else{
				if (strlen($description) > 20 && $this->config->get('module_article_desc_limit')) {
					$desc = utf8_substr(str_replace(array('<p>', '</p>'), '', $description), 0, $this->config->get('module_article_desc_limit')) . '...';
				}else{
					$desc = '';
				}
			}

			if (!$result['alternative_link']) {
				$link_to_go = $this->url->link('information/article', 'article=' . $result['article_id']);
				$external = false;
			}else{
				$link_to_go = $result['alternative_link'];
				$external = true;
			}
			
					$data['latest_articles'][] = array(
					'article_id'  => $result['article_id'],
					'thumb'        => $thumb,
					'name'        => $result['name'],
					'description' => $desc,
					'href'        => $link_to_go,
					'date'  => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'viewed'  => $result['viewed'],
					'external'  => $external
					);
		   }
		}

		if ($this->config->get('module_article_show_latest')) {
			$articles_total = $this->model_catalog_article->getTotalArticles();
		}else{
			$articles_total = $this->model_catalog_article->getTotalArticlesByArticleId();
		}

		$pagination = new Pagination();
		$pagination->total = $articles_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('information/articles','&page={page}');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($articles_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($articles_total - $limit)) ? $articles_total : ((($page - 1) * $limit) + $limit), $articles_total, ceil($articles_total / $limit));

		if ($page == 1) {
		    $this->document->addLink($this->url->link('information/articles', '', true), 'canonical');
		} elseif ($page == 2) {
		    $this->document->addLink($this->url->link('information/articles', '', true), 'prev');
		} else {
		    $this->document->addLink($this->url->link('information/articles', '&page='. ($page - 1), true), 'prev');
		}

		if ($limit && ceil($articles_total / $limit) > $page) {
		    $this->document->addLink($this->url->link('information/articles', '&page='. ($page + 1), true), 'next');
		}

		$data['show_date'] = $this->config->get('module_article_show_date');
		$data['show_views'] = $this->config->get('module_article_show_views');
		$data['show_readmore'] = $this->config->get('module_article_show_readmore');
		$data['continue'] = $this->url->link('common/home');
		
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		//поиск статей, которые относятся к категории "Вопросы и ответы"
		foreach ($data['articles'] as $key => $article_category) {
			if ($article_category['name'] == 'Вопросы и ответы') {
				foreach ($article_category['children'] as $article) {
					$noShowArticleId[] = $article['article_id'];
				}
			}
		}

		//удаление статей, которые относятся к категории "Вопросы и ответы"
		if (isset($noShowArticleId)) {
			foreach ($data['latest_articles'] as $key => $article) {
				foreach ($noShowArticleId as $id) {
					if ($article['article_id'] == $id) {
						unset($data['latest_articles'][$key]);
					}
				}
			}
		}
		

		$this->response->setOutput($this->load->view('information/articles', $data));
	}
}
