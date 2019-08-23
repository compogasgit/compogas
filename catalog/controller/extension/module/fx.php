<?php

class ControllerExtensionModuleFX extends Controller {

    private $error = array();
    private $total = '';
    private $page = '';
    private $index_href = '';
    private $host = '';
    private $all = '';
	private $ocf = '';
	private $heading_title = '';
	
    public function index($data = array()) {
	
		if ($this->cancel()) return array('data' => array());

		$data['meta_description'] = $this->document->getDescription();
		$data['title'] = $this->document->getTitle();
		
		$data = $this->setData($data);		
		
		$this->gethref();
	
		$host = $this->host;
		
		$out['route'] = $route = $this->route();
		
		$out['page'] = isset($this->request->get['page']) ? (int)$this->request->get['page'] : 0;
		
		$pageurl = $rel_pageurl = $path_manufacturer_error = '';	

		$out['clear'] = $no_data = 0;
		
		$limit = $this->limit();

		$this->page = $page = isset($this->request->get['page']) ? (int)$this->request->get['page'] : 1;
		
		$gets = explode(",", $this->config->get('fx_canonical_paht'));
		
		$rel_url = $this->relUrl();

		$fx['name'] = $data['name'] = $this->setName($data['name'], $route);
		$canonical = $this->config->get('fx_canonicals');
		$redirects = $this->config->get('fx_redirects');

		switch ($route){
			case 'product/category':
				$parts = explode('_', (string)$this->request->get['path']);
				$category_id = (int)array_pop($parts);
				$rel_pageurl = 'path=' . $category_id . $rel_url['prev_next'];
				$out['href'] = $this->url->link('product/category', 'path=' . $category_id . $rel_url['canonical'], 'SSL');
				break;
			case 'product/manufacturer/info':
				$rel_pageurl = 'manufacturer_id=' . $this->request->get['manufacturer_id'] . $rel_url['prev_next'];
				$out['href'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . $rel_url['canonical'], 'SSL');
				break;
			case 'product/special':
				$out['href'] = $this->url->link('product/special', $rel_url['canonical'], 'SSL');
				break;
			case 'product/product':				
				$rel_pageurl = 'product_id=' . $this->request->get['product_id'] . $rel_url['prev_next'];
				$out['href'] = $this->url->link('product/product', 'product_id=' . $this->request->get['product_id'] . $rel_url['canonical'], 'SSL');
				$canonical = $this->config->get('fx_canonicals_p');
				$redirects = $this->config->get('fx_redirects_p');
				$no_data = 1;
				break;
			case 'information/information':
				$rel_pageurl = 'information_id=' . $this->request->get['information_id'] . $rel_url['prev_next'];
				$out['href'] = $this->url->link('information/information', 'information_id=' . $this->request->get['information_id'] . $rel_url['canonical'], 'SSL');
				$canonical = $this->config->get('fx_canonicals_i');
				$redirects = $this->config->get('fx_redirects_i');
				$no_data = 1;		
				break;
			case 'common/home':
				$out['href'] = $this->url->link('common/home', '', 'SSL');
				$canonical = $this->config->get('fx_canonicals_h');
				$redirects = $this->config->get('fx_redirects_h');
				$no_data = 1;
				break;
			default:
				$out['href'] = $this->url->link($route, '', 'SSL');
				$no_data = 1;
				break;
		}
		
		$out['heading_title'] = $this->heading_title = $data['data']['heading_title'];
		$out['name'] = $data['name'];		
		$out['meta_description'] = ($data['meta_description'] != '') ? $data['meta_description'] : $data['name'];
		$out['title'] = (($data['title'] == '') || ($this->config->get('fx_title_to_name') && ($page > 1))) ? $data['name'] : $data['title'];
		//$out['title'] = (($data['title'] == '') || ($this->config->get('fx_title_pattern') && ($page > 1))) ? $data['name'] : $data['title'];
		
		$out['data']['manufacturer_mfp'] = '';
		$m_name = '';
		
		if (($route == 'product/category') && !empty($this->request->get['manufacturer_id']) && !empty($this->request->get['path'])) {
			$mid = (int)$this->request->get['manufacturer_id'];
			$this->load->model('extension/module/fx');
			$cid = explode("_", $this->request->get['path']);
			$category_id = (int)array_pop($cid);
			
			$m_name = $this->model_extension_module_fx->getManufacturer($mid);
			
			//$out['data']['manufacturer_mfp'] = $this->getMFP($mid, $category_id);
			$out['data']['footer'] = $this->getMFP($mid, $category_id) . $data['data']['footer'];
			
			if ((float)VERSION < 3 ) {
				$c_m_url = $this->url->link('product/category', 'path=' . $category_id. '&manufacturer_id=' . $mid, true);
			} else {
				$m_full_url = parse_url($this->url->link('product/manufacturer/info', 'manufacturer_id=' . $mid, true));
				$m_url_path = ltrim(str_replace('/index.php', '', $m_full_url['path']), '/');
				$m_short_url = !empty($m_url_path) ? $m_url_path : '?manufacturer_id=' . $mid;
				$c_m_url = rtrim($this->url->link('product/category', 'path=' . $category_id, true), '/') . '/' . $m_short_url;
			}
			
			$out['man_breadcrumb'] = array(
				'text' => $m_name,
				'href' => $c_m_url
			);
			
			$out['clear'] = 1;			
			
			$out['title'] = str_replace($fx['name'], $fx['name'].$m_name, $out['title']);
			$out['meta_description'] = str_replace($fx['name'], $fx['name'].$m_name, $out['meta_description']);
			$out['heading_title'] = str_replace($fx['name'], $fx['name'].$m_name, $out['heading_title']);
			
			if ($this->config->get('fx_title_to_name')) $out['title'] = $fx['name'].$m_name;
			
			$c_m_num = array_keys($this->request->get);
			$path_num = (int)array_search('path', $c_m_num);
			$man_num = (int)array_search('manufacturer_id', $c_m_num);
			
			if ($man_num < $path_num) $path_manufacturer_error = 1;
		}
		

		$this->index_href = $href = $out['href'];		
		$this->total = $total_pages = $data['total'];
		$ocf = '';
		
		if ($this->registry->has('ocfilter') && method_exists($this->ocfilter, 'getPageInfo')) {
		  $page_info = $this->ocfilter->getPageInfo();
		  if ($page_info) $this->ocf = $page_info;
		}
		
		if($this->config->get('fx_google')) {	
			if ($limit && ($total_pages > $page)){
				$out['next'] = $this->url->link($route, $rel_pageurl . '&page=' . ($page + 1), 'SSL');
			}
			if ($page > 1){
				$out['prev'] = $this->url->link($route, $rel_pageurl . ( $page == 2 ? '' : '&page='. ($page - 1)), 'SSL');
			}
		}		
		
		if ($this->multiTest($this->config->get('fx_no_pagination'))) $out['next'] = $out['prev'] = '';
		
		$href_no_page = $this->url->link($route, $rel_pageurl, 'SSL');
		
		//print($this->url->link($route, $rel_pageurl, 'SSL') . '   | href_no_page <br>');
		
		if ($this->testCanonical($canonical)) $out['canonical'] = $this->multiTest($this->config->get('fx_no_canonical')) ? '' : $href;
		
		if ($this->testWildcard($this->config->get('fx_redirect_pattern')) || $this->testRedirect($redirects, $href_no_page)) {
			$this->redirect_to($href);	
		}
		
		if ($this->testWildcard($this->config->get('fx_404_pattern'), $href) || $this->multiTest($this->config->get('fx_404_get'))) {
			$this->code404();
		}
		
		if ($this->pageEmpty($data['data'], 'canonical')) $out['canonical'] = $href_no_page;
		
		if ($this->pageEmpty($data['data'], 'prev_next')) $out['next'] = $out['prev'] = '';
		
		if ($this->testWildcard($this->config->get('fx_canonical_pattern'))) $out['canonical'] = $href;
		
		if (empty($out['canonical']) && $path_manufacturer_error) $out['canonical'] = $this->url->link('product/category', 'path=' . $category_id . '&manufacturer_id=' . $this->request->get['manufacturer_id'], 'SSL');
		
		if ($this->ocfilter()) $out['canonical'] = $out['clear'] = '';
		
		$this->load->language('extension/module/fx');
		
		if ($page > 1) {
			
			$this->load->language('extension/module/fx');
			
			$h1 = str_replace("[h1]", $out['heading_title'], $this->config->get('fx_pattern'));
			$h1 = str_replace("[name]", $data['name'], $h1);
			$h1 = str_replace("[page]", $this->language->get('text_page'), $h1);
			$h1 = str_replace("[out_of]", $this->language->get('text_out_of'), $h1);
			$h1 = str_replace("[n]", $page, $h1);
			$h1 = str_replace("[total]", $data['total'], $h1);			
			$h1 = str_replace(array("[total]", "[h1]", "[name]", "[page]", "[out_of]", "[n]", "[total]"), '', $h1);
			
			if ((strpos($h1, '[url]')) !== false){	$h1 = html_entity_decode(str_replace("[url]", $href, $h1));}

			$metadesc = str_replace("[desc]", $out['meta_description'], $this->config->get('fx_metapattern'));
			$metadesc = str_replace("[name]", $data['name'], $metadesc);
			$metadesc = str_replace("[h1]", $out['heading_title'], $metadesc);
			$metadesc = str_replace("[page]", $this->language->get('text_page'), $metadesc);
			$metadesc = str_replace("[out_of]", $this->language->get('text_out_of'), $metadesc);
			$metadesc = str_replace("[n]", $page, $metadesc);
			$metadesc = str_replace("[total]", $data['total'], $metadesc);			
			$metadesc = str_replace(array("[desc]", "[h1]", "[name]", "[page]", "[out_of]", "[n]", "[total]"), '', $metadesc);
			

			$fx_title_pattern = $this->config->get('fx_title_pattern');
			$title = str_replace("[title]", $out['title'], $fx_title_pattern);
			$title = str_replace("[name]", $data['name'], $title);
			$title = str_replace("[h1]", $out['heading_title'], $title);
			$title = str_replace("[page]", $this->language->get('text_page'), $title);
			$title = str_replace("[out_of]", $this->language->get('text_out_of'), $title);
			$title = str_replace("[n]", $page, $title);
			$title = str_replace("[total]", $data['total'], $title);			
			$title = str_replace(array("[title]", "[h1]", "[name]", "[page]", "[out_of]", "[n]", "[total]"), '', $title);
		
			if ($h1 != ''){ $out['heading_title'] = $h1;}
			if (!$this->config->get('fx_description')) $out['clear'] = 1;
			if ($metadesc != '') $out['meta_description'] = $metadesc;
			if ($title != '') $out['title'] = $title;
		}
		
	
		$after = '';
		$is_sort = isset($this->request->get['sort']) && isset($this->request->get['order']);
		$is_limit = isset($this->request->get['limit']);
		
		if ($this->config->get('fx_sortlimit') && ($is_sort || $is_limit)){
			$after = ' [';
			if (isset($this->request->get['sort'])){
				foreach ($data['data']['sorts'] as $sorts) {
					if ($sorts['value'] == $this->request->get['sort'].'-'.$this->request->get['order']) { 
						$after .= $sorts['text'];		
					}
				}
			}				
			$after .= ($is_sort && $is_limit) ? ' | ' : '';
			$after .= $is_limit ? $this->request->get['limit'].']' : ']';
		}
		
		$after = $page < 2 ? '' : $after;
		
		$out['title'] = ($out['title'] == $data['title'] ) ? $out['title'].$m_name.$after : $out['title'].$after;
		$out['meta_description'] = ($out['meta_description'] == $data['meta_description'] ) ? $out['meta_description'].$m_name.$after : $out['meta_description'].$after;
		$out['heading_title'] = ($out['heading_title'] == $data['data']['heading_title'] ) ? $out['heading_title'].$m_name.$after : $out['heading_title'].$after;

		$out['data']['page'] = $out['page'];
		$out['data']['fx_span'] = $this->getH1($out['heading_title']);
		
		if ($this->config->get('fx_clear_sort_limit_doubles')) {
			if (isset($data['data']['limits'])) $out['data']['limits'] = $this->clear_limits($data['data']['limits']);
			if (isset($data['data']['sorts'])) $out['data']['sorts'] = $this->clear_sorts($data['data']);
		}
		
		if (isset($data['data']['breadcrumbs'])) $out['data']['breadcrumbs'] = $this->bread($data['data']['breadcrumbs']); /**/
		$data['data'] = array_merge($data['data'], $out['data']);
		
		if (($page > 1) || !empty($m_name)) {
			$out['data'] = array_merge($out['data'], $this->hidden($data['data']));
		}		
		
		if ($this->isYandex() && $this->config->get('fx_yandex_canonical')) {
			$out['canonical'] = $href_no_page;
			$out['prev'] = $out['next'] = '';
		}
		
		$this->delLinks($href, array ("prev","canonical","next"));
		if (!empty($out['prev']))	$this->document->addLink($out['prev'], 'prev');
		if (!empty($out['next']))	$this->document->addLink($out['next'], 'next');
		
		if (!empty($out['canonical'])) { 
			if ($this->cicleProtect($out['canonical'])) $this->document->addLink($out['canonical'], 'canonical');
			
			if ($out['canonical'] != $this->full_url()) $out['clear'] = $this->config->get('fx_description_canonical') ? 1 : $out['clear'];
		}
		
		if ($out['clear']) $out['data']['description'] = $out['data']['description_2'] = $out['data']['thumb'] = '';

		if ($no_data) $out['data'] = array();
		
		if ($fx['name'] != '') {
		
			$out['data']['heading_title'] = $out['heading_title'];
			$this->document->setTitle($out['title']);
			$this->document->setDescription($out['meta_description']);
		
		}
		
		if ((float)VERSION < 1.9 ){
			$this->fuck15($out);
		} else {
			return $out;
		}
		
		return $out;
    }	
	
	public function test404() {	
	
		$uri = $this->request->server['REQUEST_URI'];
		
		//$uri = '/tyrututu/456/canon.html';
		
		$preclear = explode("?", $uri);
		
		$clear = trim($preclear[0], '/');
		
		//print_r($clear);
		
		$parts = explode("/", $clear);
		
		while (!empty($parts)) {
		
			//$temp = 
			
			$prelast = explode(".", end($parts));
			
			$last = $prelast[0];
			
		
			$this->load->model('extension/module/fx');

			$query = $this->model_extension_module_fx->getQuery($last);
			
			
			if (!empty($query)) {

				preg_match('/(.*)_id=(\d*)/', $query, $match);
				
				
				switch ($match[1]){
					case 'category':
						$to = $this->url->link('product/category', 'path=' . $match[2], 'SSL');
						break;
					case 'manufacturer':
						$to = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $match[2], 'SSL');
						break;
					case 'product':
						$to = $this->url->link('product/product', 'product_id=' . $match[2], 'SSL');
						break;
					case 'information':
						$to = $this->url->link('information/information', 'information_id=' . $match[2], 'SSL');
						break;
				}
			
			}
			
			if (isset($to)) $this->response->redirect($to, 301);
			
			array_pop($parts);
			
		}

		//return $script;

    }
	
	public function pageEmpty($data, $attribute) {
	
		$count = isset($data['products']) ? count($data['products']) : 0;
	
		$canonical = $prev_next = false;
		
		$get_list = 'mfp,filter';
		
		$list = array("product/category", "product/manufacturer/info", "product/special");
		
		$route_allow = in_array($this->route(), $list) ? true : false;
		



		if ($route_allow && $this->testIssetGet($get_list) && !$count) $canonical = true;
		

		
		if ($this->total < $this->page) {		
			$prev_next = true;
			if ($this->page > 1) $canonical = true;
		}
		
		//$canonical = $this->multiTest($this->config->get('fx_no_canonical')) ? false : $canonical;
		
		$prev_next = $this->config->get('fx_no_blank_pages') ? $prev_next : false;
		
		if ($attribute == 'prev_next') return $prev_next;
		if ($attribute == 'canonical') return $canonical;
		
		return false;

    }
	
	public function bread($breadcrumbs) {
		
		$last = array_pop($breadcrumbs);
		$last['href'] .= '#';
		
		$breadcrumbs[] = $last;
			
		return $breadcrumbs;

    }
	
	public function isAdmin() {
		
		if	(method_exists($this->load, 'library')) $this->load->library('user');

		$user = new User($this->registry);

		if ($user->isLogged()) return true;
	
		return false;
    }
	
	public function cancel() {
		
		$route = $this->route();
		
		if (empty($route)) return true;
	
		$list = array("product/tags", "product/compare", "product/catalog");
		
		if (in_array($route, $list)) return true;
		
		$parts = array(
			'product/category' => 'path',
			'product/manufacturer/info' => 'manufacturer_id',
			'product/product' => 'product_id',
			'information/information' => 'information_id',
		);
		
		foreach ($parts as $key=>$value){
			if (($route == $key) && !$this->testIssetGet($value)) return true;
		}
	
		return false;

    }
	
	public function relUrl() {
	
		$rel_url['canonical'] = $rel_url['prev_next'] = '';
		
		$gets = explode(",", $this->config->get('fx_canonical_paht'));

		foreach ($gets as $get){
	
			$get = trim($get);
			
			if ($this->testIssetGet($get)){
				
				if ($get == 'page') {
					$rel_url['canonical'] .= ($this->page < 2) ? '' : '&page=' . $this->page;
				} else {
					$rel_url['canonical'] .= '&' . $get . '=' . $this->request->get[$get];
				}
			}
		}
		
		$gets = explode(",", $this->config->get('fx_prev_next_paht'));

		foreach ($gets as $get){	
			$get = trim($get);
			
			if ($this->testIssetGet($get) && ($get != 'page')) $rel_url['prev_next'] .= '&' . $get . '=' . $this->request->get[$get];
		}
		

		return $rel_url;

    }
	
	public function delLinks($href, $links) {
		
		if ((float)VERSION < 1.9 ){ 
			$this->document->delLinks($href, $links);
		} else {
			$this->document->delLinks($links);
		}		

		return true;

    }
	
	public function limit() {	
		
		if ((float)VERSION < 1.9 ){			
			$limit = isset($this->request->get['limit']) ? (int)$this->request->get['limit'] : $this->config->get('config_catalog_limit');
		} else if ((float)VERSION < 2.3 ) {			
			$limit = isset($this->request->get['limit']) ? (int)$this->request->get['limit'] : $this->config->get('config_product_limit');
		} else if ((float)VERSION < 3 ) {	
			$limit = isset($this->request->get['limit']) ? (int)$this->request->get['limit'] : $this->config->get($this->config->get('config_theme') . '_product_limit');
		} else {
			$limit = isset($this->request->get['limit']) ? (int)$this->request->get['limit'] : $this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit');
		}

		return $limit;

    }
	
	public function code404() {
		
		if ((float)VERSION < 1.9 ){ 
			$this->getChild('error/not_found');
		} else {
			$this->load->controller('error/not_found');
		}		

		return true;

    }
    
    public function isAjax() {	
        if (!empty($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) {
            return true;
        }
        
        return false;
    }
   
    public function redirect_to($to, $code = 301) {
        
        $current = $this->full_url();
        
        if ($current == $to) return false;
        
        
        if((float)VERSION < 2) {
			$this->redirect($to, $code);
		}else{
			$this->response->redirect($to, $code);
		}
		
    }
	
	public function info($data='') {
		
		if (empty($data)) $data = $this->request->get['data'];
		
		$parseurl = 'http://full-index.ru/messages/?data=' . $data;
		
		/*$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; FX)');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $parseurl);
		$pagecode = curl_exec( $ch );
		curl_close($ch);*/
		
		$headers = @get_headers($parseurl);
		
		if($headers === false){
			echo '<p class = "text-center" style="padding: 101px 0">Error connecrion to full-index.ru host... You can see info directly on <a href="http://full-index.ru/messages/">official site</a></p>';
		} else {

			$pagecode = @file_get_contents($parseurl);
			
			echo $pagecode;
			
		}

    }
	
	public function fuck15($data) {

		$this->data['fx'] = json_encode($data);

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/fx.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/fx.tpl';
		} else {
			$this->template = 'default/template/module/fx.tpl';
		}
		
		$this->render();

    }
	
	public function getMFP($mid, $cid) {	

		$script = '<script>
			
		function fx_back(){
			$(location).prop("href", "' . $this->url->link("product/category", "path=" . $cid , 'SSL') . '");
		}
		
		function fx_check(){
			$("#mfilter-opts-attribs-1-manufacturers-'. $mid .'").prop("checked", true);
		}
		
		$( document ).ready(function() {
			$(".mfilter-manufacturers .mfilter-heading").removeClass( "mfilter-collapsed" );
			$(".mfilter-manufacturers .mfilter-content-opts").show();
		
			fx_check();			
		});

		$("#mfilter-opts-attribs-1-manufacturers-'.$mid.'").on("click", function() { fx_back();});				
		$("#mfilter-opts-attribs-1-manufacturers-'.$mid.'").parent().parent().on("click", ".mfilter-close", function() { fx_back();});		
			
			</script>';

		return $script;

    }
	
	public function ocfilter() { // true = убрать canonical

		$gets = $this->request->get;		
		unset($gets['_route_']);
		$test = isset($gets['filter_ocfilter']) ? $gets['filter_ocfilter'] : false;
		
		$canonicals = 'page,sort,limit,order';
		if ($this->multiTest($canonicals) || (count($gets) > 3)) return false;
		
		if (!empty($this->ocf)) return true; // посадочная
		
		if (!$test) return false;

		if ((substr_count($test, ',')+substr_count($test, ';')) + 1 <= $this->config->get('fx_ocfilter')) return true;
		
		return false;
    }
	
	public function rewiews() {
		
		$limit = 5;
	
		$out = $this->config->get('fx_review_page') ? 999 : $limit;
		
		if ($this->route() == 'product/product/review' && !$this->isAjax() && $this->config->get('fx_review_301')) {
			$this->redirect_to($this->url->link('product/product', 'product_id=' . $this->request->get['product_id'], 'SSL'));
		}
		
		if ((float)VERSION < 1.9 ){
			$this->fuck15($out);
		}
		
		return $out;

    }

	public function route() {
			
		if (isset($this->request->get['route'])) {
			$route = $this->request->get['route'];
		} else {
			$uri = $this->request->server['REQUEST_URI'];
			$route = '';
			if (($uri == '/') || ($uri == '')) $route = 'common/home';
		}

		return $route;

    }

	public function testWildcard($wildcards, $href = '') {
		
		$uri = $this->request->server['REQUEST_URI'];

		$wilds = explode(',', $wildcards);

		foreach ($wilds as $wild){
			
			$wild = trim($wild);

			$test = str_replace("*", "☺", $wild);	$test = str_ireplace('/', '☻', $test);

			$test = preg_quote(strtolower($test));

			$test = str_replace("☺", ".*", $test);	$test = str_ireplace('☻', '\/', $test);

			if (preg_match('/^'.$test.'$/', $uri)) return true;

		}

		return false;

    }

	public function testCanonical($canonicals, $href='') {	

		if (isset($this->request->get['product_id']) && (isset($this->request->get['manufacturer_id']) || isset($this->request->get['page']))) return true;

		if (trim($canonicals) == 'all') return true;
		
		if ($this->multiTest($canonicals)) return true;

		return false;
    }

	public function cicleProtect($href = '') {
		
        $current = $this->full_url();
		
        //print($this->full_url() . '   | cicleProtect <br>');
		
		if (($href == $current) && $this->config->get('fx_canonical_protection')) return false;

		return true;
    }
	
	

	public function multiTest($str, $href = '') {
		
		$elements = explode(",", $str);

		foreach ($elements as $element){
			
			$element = trim($element);
			
			if ((strpos($element, '|')) !== false) {
				
				$sub_elements = explode("|", $element);
				
				$i =0;
				
				foreach ($sub_elements as $sub_element){
					
					if ($this->testIssetGet($sub_element)) $i++;
				
				}
				
				if (count($sub_elements) == $i) return true;
				
			} else {
				
				if ($this->testIssetGet($element)) return true;
				
			}
		
		
		}


		return false;

    }
	
	public function gethref() {
	
		$href = $this->index_href;
		//$host = $this->config->get('config_ssl') ? HTTPS_SERVER : HTTP_SERVER;
		$host = $this->config->get('config_secure') ? str_replace('http:/', 'https:/', $this->config->get('config_ssl')) : $this->config->get('config_url');
		if (substr_count($host, "/") > 3){		
			$ssl = $this->config->get('config_secure') ? 'https://' : 'http://';
			$host = $ssl . $this->request->server['HTTP_HOST'];
			$href = str_replace($host, '', $href);
		} else {
			$href = '/'.str_replace($host, '', $href);
		}
		
		$this->host = $host;

		return $href;

    }
	
	public function full_url() {
	
		$host = $this->config->get('config_secure') ? str_replace('http:/', 'https:/', $this->config->get('config_ssl')) : $this->config->get('config_url');
	
		$ssl = $this->config->get('config_secure') ? 'https://' : 'http://';
		$host = $ssl . $this->request->server['HTTP_HOST'];
		
		/*
		if (substr_count($host, "/") > 3){		
			$ssl = $this->config->get('config_secure') ? 'https://' : 'http://';
			$host = $ssl . $this->request->server['HTTP_HOST'];
		}*/
		
		$uri = $this->request->server['REQUEST_URI'];
		
		$full = rtrim($host, '') . '' . ltrim($uri, '');
		
		return $full;
    }

	public function setName($name, $route) {
	
		$list = array(
			'product/category',
			'product/manufacturer/info',
			'product/product',
			'information/information',
		);
		
		if (in_array($route, $list)) return $name;
		
		$this->load->language($route);
		
		$name = $this->language->get('heading_title');
		
		$parts = explode("/", $route);	
		
		if ($name == 'heading_title') $name = array_pop($parts);
		
		return $name;

    }
	
	public function clear_limits($limits) {
		
		if (empty($limits)) return $limits;
	
		if ((float)VERSION < 1.9 ){ 
			$default = $this->config->get('config_catalog_limit');
		} elseif((float)VERSION < 2.3) {
			$default = $this->config->get('config_product_limit');
		} else {
			$default = $this->config->get($this->config->get('config_theme') . '_product_limit');
		}
		
		$limits[0]['href'] = preg_replace('/(\?|&|&amp;)limit=' . $default . '$/', '', $limits[0]['href']);
		
		$limits[0]['href'] = str_replace(array('/&amp;limit=', '/&limit='), '/?limit=', $limits[0]['href']);
		$limits[0]['href'] = str_replace(array('/&amp;sort=', '/&sort='), '/?sort=', $limits[0]['href']);
		
		return $limits;

    }
	
	public function clear_sorts($data) {
		
		if (empty($data['sorts'])) return $data['sorts'];
		
		$data['sort'] = $this->config->get('fx_clear_sort') ? $this->config->get('fx_clear_sort') : 'p.sort_order';
		$data['order'] = $this->config->get('fx_clear_order') ? $this->config->get('fx_clear_order') : 'ASC';
		
		$replace = array('?sort='.$data['sort'], '&sort='.$data['sort'], '&amp;sort='.$data['sort'],'&amp;order='.$data['order'], '?order='.$data['order'], '&order='.$data['order']);

		foreach($data['sorts'] as &$sort){			
			
			$temp = str_replace($replace, '', $sort['href'], $n);
			$temp = str_replace(array('/&amp;sort=', '/&sort='), '/?sort=', $temp);
			$temp = str_replace(array('/&amp;limit=', '/&limit='), '/?limit=', $temp);
			
			if ($n > 1) $sort['href'] = $temp;
		}
		
		return $data['sorts'];

    }
	
	public function getH1($h1) {
	
		$style = (strlen($this->config->get('fx_style')) > 3 ) ? ' style="'.$this->config->get('fx_style').'"' : '';
		
		$span = '<span'. $style . '>' . $h1 . '</span>';

		
		if (($this->page > 1)&&($this->config->get('fx_hide_h1'))) { 
			return '<!--hidden h1-->';
		}
		
		if (($this->page > 1) && $this->config->get('fx_span')) { 
			return $span;
		}
		
		return '';

    }	

	public function hidden($out) {
		
		$page = isset($this->request->get['page']) ? (int)$this->request->get['page'] : 1;
		
		//$out['content_top'] = $out['column_left'] = $out['column_right'] = $out['content_bottom'] = $out['categories'] = '';
		
		if ($this->config->get('fx_noindex_addon_top')) $out['content_top'] = '<!--noindex-->' . $out['content_top'] . '<!--/noindex-->';
		if ($this->config->get('fx_noindex_addon_left')) $out['column_left'] = '<!--noindex-->' . $out['column_left'] . '<!--/noindex-->';
		if ($this->config->get('fx_noindex_addon_right')) $out['column_right'] = '<!--noindex-->' . $out['column_right'] . '<!--/noindex-->';
		if ($this->config->get('fx_noindex_addon_bottom')) $out['content_bottom'] = '<!--noindex-->' . $out['content_bottom'] . '<!--/noindex-->';
		
		if ($this->config->get('fx_noindex_addon_cats_list')) {
			
			foreach($out['categories'] as &$category){					
				$category['name'] = '<!--noindex-->' . $category['name'] . '<!--/noindex-->';					
			}
			
			if (!isset($out['text_refine']))  $out['text_refine'] = '<!--noindex-->' . $out['text_refine'] . '<!--/noindex-->';
			
			foreach($out['sorts'] as &$sort){	
				$sort['text'] = '<!--noindex-->' . $sort['text'] . '<!--/noindex-->';
			}
			
			$out['footer'] = '<!--noindex-->' . $out['footer'] . '<!--/noindex-->';
			
		}
		
		if (!isset($out['content_top'])) $out['content_top'] = '';
		if (!isset($out['column_left'])) $out['column_left'] = '';
		if (!isset($out['column_right'])) $out['column_right'] = '';
		if (!isset($out['content_bottom'])) $out['content_bottom'] = '';

		if ($this->config->get('fx_hide_content_top') || ($out['content_top'] == '<!--noindex--><!--/noindex-->')) $out['content_top'] ='';
		if ($this->config->get('fx_hide_column_left') || ($out['column_left'] == '<!--noindex--><!--/noindex-->')) $out['column_left'] ='';
		if ($this->config->get('fx_hide_column_right') || ($out['column_right'] == '<!--noindex--><!--/noindex-->')) $out['column_right'] ='';
		if ($this->config->get('fx_hide_content_bottom') || ($out['content_bottom'] == '<!--noindex--><!--/noindex-->')) $out['content_bottom'] ='';
		if ($this->config->get('fx_hide_cats_list')) $out['categories'] = array();

		
		return $out;

    }
	
	public function hidden15($out) {
		
		$hide = false;
		
		if (($this->request->get['route'] == 'product/category') && !empty($this->request->get['manufacturer_id']) && !empty($this->request->get['path'])) $hide = true;
		
		if ($this->page > 1) $hide = true;

		if (!$hide) return $out;
		
		if (!isset($out['content_top'])) $out['common/content_top'] = '';
		if (!isset($out['column_left'])) $out['common/column_left'] = '';
		if (!isset($out['column_right'])) $out['common/column_right'] = '';
		if (!isset($out['content_bottom'])) $out['common/content_bottom'] = '';
			
		if ($this->config->get('fx_hide_content_top') || ($out['common/content_top'] == '<!--noindex--><!--/noindex-->')) $out['common/content_top'] ='';
		if ($this->config->get('fx_hide_column_left') || ($out['common/column_left'] == '<!--noindex--><!--/noindex-->')) $out['common/column_left'] ='';
		if ($this->config->get('fx_hide_column_right') || ($out['common/column_right'] == '<!--noindex--><!--/noindex-->')) $out['common/column_right'] ='';
		if ($this->config->get('fx_hide_content_bottom') || ($out['common/content_bottom'] == '<!--noindex--><!--/noindex-->')) $out['common/content_bottom'] ='';

		
		if ((float)VERSION < 1.9 ){
			$this->fuck15($out);
		}
		return $out;

    }	
	
	public function testIssetGet($str) {
	
		$gets = explode(",", $str);		

		foreach ($gets as $get){
			if (isset($this->request->get[$get])) return true;
		}


		return false;

    }	
	
	public function isYandex() {
	
		if (!isset($this->request->server['HTTP_USER_AGENT'])) return false;
		
		$agent = $this->request->server['HTTP_USER_AGENT'];
		
		if (((strpos($agent, 'yandex')) !== false) && ((strpos($agent, 'bot')) !== false)) return true;

		return false;

    }
	
	public function seo_pro() {
	
		if ($this->config->get('config_seo_url_type') && ($this->config->get('config_seo_url_type') == 'seo_pro')) return true;

		return false;

    }	
	
	public function seopro_tags($tmp = array()) {
	
		$out = array();
	
		$allowed = explode(",", $this->config->get('fx_seopro_tags'));
		foreach ($allowed as $allow){
			if (isset($tmp[$allow])) {$out[$allow] = $tmp[$allow];}
		}

		return $out;

    }
	
	public function layout() {
	
		if ($this->route() == 'product/category' && isset($this->request->get['page'])) return $this->config->get('fx_layout');

		return false;

    }
	
	public function clear_host($url) {
	
		$host = $this->config->get('config_secure') ? str_replace('http:/', 'https:/', $this->config->get('config_ssl')) : $this->config->get('config_url');
		if (substr_count($host, "/") > 3){		
			$ssl = $this->config->get('config_secure') ? 'https://' : 'http://';
			$host = $ssl . $this->request->server['HTTP_HOST'];
			$url = '/'. ltrim(str_replace($host, '', $url), '/');
		} else {
			$url = '/'.str_replace($host, '', $url);
		}
		
		return $url;
    }

	public function testRedirect($redirects, $to = '') {

		$uri = $this->request->server['REQUEST_URI'];
		if (!$to) $to = $this->gethref();
		
		//print($this->clear_host($to) . ' = ' . $uri . '   |  testRedirect<br>');
		
		$to = $this->clear_host($to);
		
		if ($to == $uri) return false;
		
		if ($to == '/index.php?route=common/home' && $uri == '/') return false;
		
		$code = (int)$this->config->get('fx_redirect_empty_page');
		
		if (($this->page > 1) && ($this->page > $this->total) && ($code > 300) && ($code < 600))  $this->redirect_to($to, $code);

		if ($this->config->get('fx_301')){

			if ( $this->testGet('page') || $this->testGet('limit') ) return true;
			
			if ( $this->findSlash(array('sort','order','mfp', 'product_id', 'path', 'manufacturer_id', 'information_id')) ) return true; 

		}

		if ($redirects == '') return false;

		$redirs = explode(",", $redirects);	

		foreach ($redirs as $redir){
			
			$redir = trim($redir);

			if ($redir == 'manufacturer_id' && isset($this->request->get['path'])) return true;

			if (isset($this->request->get[$redir]) || (($redir == '//') && (strpos($uri,'//') !== false ))) return true;		

		}
		
		return false;

    }

	public function setData($data) {

		if (!isset($data['name'])) $data['name'] = '';
		if (!isset($data['ocfilter'])) $data['ocfilter'] = false;
		if (!isset($data['total'])) $data['total'] = 0;
		$data['total'] = ceil($data['total']);
		
		if (!isset($data['data']['limits'])) $data['data']['limits'] = array();
		
		if (!isset($data['data']['heading_title'])) $data['data']['heading_title'] = '';
		if (!isset($data['data']['content_top'])) $data['data']['content_top'] = '';
		if (!isset($data['data']['column_left'])) $data['data']['column_left'] = '';
		if (!isset($data['data']['column_right'])) $data['data']['column_right'] = '';
		if (!isset($data['data']['content_bottom'])) $data['data']['content_bottom'] = '';
		if (!isset($data['data']['footer'])) $data['data']['footer'] = '';
		
		return $data;

    }
	
	public function data() {
		$href = $this->gethref();
		$this->load->model('extension/module/fx');
		$limit = $this->model_extension_module_fx->data($this->host);
		return $limit;
    }

	public function Redirects() {


		$uri = $this->request->server['REQUEST_URI'];
		$href = $this->gethref();

		if ($this->config->get('fx_redirect_list') == '') return false;

		$redirects = explode(PHP_EOL, $this->config->get('fx_redirect_list'));

		$uri = $this->request->server['REQUEST_URI'];

		$uri = ltrim($uri, "/ ");


		foreach ($redirects as $redirect){

			$temp = explode("→", $redirect);

			if (str_replace($host, '', $temp[0]) == $uri) {

				if((float)VERSION < 2) {
					$this->redirect($host.str_replace($host, '', $temp[1]), 301);
				}else{
					$this->redirect_to($host.str_replace($host, '', $temp[1]));
				}

				return true;

			}

		}

		return false;

    }

	public function testManuficturer($mid = 0) {
		
		$route = $this->request->get['route'];

		if (($route == 'product/category') && isset($this->request->get['manufacturer_id'])) {
				$mid = $filter_data['filter_manufacturer_id'] = $data['filter_manufacturer_id'] = (int)$this->request->get['manufacturer_id'];
				$this->load->model('extension/module/fx');
				$man['name'] = $this->model_extension_module_fx->getManufacturer($mid);
				$man['manufacturer_mfp'] = $this->getMFP($mid, $category_info['category_id']);
				$this->man = $man;
				return $man;
		}
		
		return null;

    }

	public function testGet($get, $n = 1) {
	
		if (!isset($this->request->get[$get])) return false;

		if ((int)$this->request->get[$get] <= $n) return true;

		if (!ctype_digit((string)$this->request->get[$get])) return true;

		return false;

    }

	public function findSlash($uget) {

		foreach ($uget as $get){

			if (!isset($this->request->get[$get])) continue;

			if (substr($this->request->get[$get], -1) == '/') return true;

			if (($get == 'order') && (!in_array($this->request->get[$get], array('ASC', 'DESC', 'asc', 'desc')))) return true;

		}	

		return false;

    }

    public function test() {
	
		$host = $this->config->get('config_ssl') ? HTTPS_SERVER : HTTP_SERVER;
		
		$this->title = 'test';
	
		$robots = file_get_contents ($host.'robots.txt');
		$result = strpos ($robots, 'page');
		if ($result) {
			$this->error['warning'] = $this->language->get('error_robots');
			//print_r ($robots);
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = '1';
		} else {
			$data['error_warning'] = '';
		}
		
		$this->load->model('extension/module/fx');
		
		$category_id = $this->model_extension_module_fx->getCat();

		$parseurl = str_replace('&amp;','&', $this->url->link('product/category', 'path=' . $category_id . '&page=2', 'SSL'));
		
		$headers = array('HTTP_ACCEPT: Something', 'HTTP_ACCEPT_LANGUAGE: ru, en, da, nl', 'HTTP_CONNECTION: Something');

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; FX)');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $parseurl);
		$pagecode = curl_exec( $ch );
		curl_close($ch);
		
		if ($pagecode != ''){
			$code = explode('head>', $pagecode ); 
			$pagecode = str_replace('<','{',$code[1]); 
		}
		$pagecode = substr($pagecode, 0, -2);				
		if ((strpos($pagecode, 'robots') || strpos($pagecode, 'yandex') || strpos($pagecode, '"google"')) && strpos($pagecode, 'noindex'))  { 
			$data['error_warning'] .= '2';
		}		
		
		if ($data['error_warning'] != ''){
		$json = $data['error_warning'];
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode((int)$json));
		}
    }

    public function scan($encode=true) {
	
		$json = array();
		
		$this->load->model('extension/module/fx');
		
		$json['scan'] = $this->model_extension_module_fx->scanData('fx');
		
		$json['debug'] = $this->model_extension_module_fx->debugData('fx');
		
		$json['seo_pro'] = $this->seo_pro();		
		
		$json['version'] = round((float)VERSION, 1);
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));

    }
	
    public function decode($encode = '/') {
		
		$this->url->addRewrite($this);
		
		//print($this->request->get['_route_']);

		$this->response->addHeader('Content-Type: text/html');
		$this->response->setOutput($print);

    }

    public function debug() {
	
		$html = '';
		
		$this->load->model('extension/module/fx');
		$data = $this->model_extension_module_fx->debugData('fx');
				
		foreach ($data as $row){
		
			if (!strpos($row['key'], '_redirect_list')) $html .= '<b>"' . $row['key'] . '"</b><span style = "color:#088">&#09;=>&#09;</span>"' . $row['value'] . '",<br>';
		}
		
		$this->response->addHeader('Content-Type: text/html');
		$this->response->setOutput($html);

    }

    public function pagination($data) {
		$output = str_replace('{page}"', '1"', $data);
		$output = str_replace(array('?page=1"', '&amp;page=1"'), '"', $output);
		$output = str_replace(array('?page=1&', '&amp;page=1&'), '&', $output);

		return $output;
    }
	
	public function event(){
	
		switch ($this->route()){
			case 'information/information':
				$href = $this->url->link('information/information', 'information_id=' . $this->request->get['information_id'], 'SSL');
				$canonical = $this->config->get('fx_canonicals_i');
				$redirects = $this->config->get('fx_redirects_i');
				break;
			case 'common/home':
				$href = $this->url->link('common/home', '', 'SSL');
				$canonical = $this->config->get('fx_canonicals_h');
				$redirects = $this->config->get('fx_redirects_h');
				break;
			default:
				$href = $this->url->link($this->route(), '', 'SSL');
				return;
				break;
		}
		
		if ($this->testCanonical($canonical, $href)) $out['canonical'] = $href;
		
		if ($this->testWildcard($this->config->get('fx_redirect_pattern'), $href) || $this->testRedirect($redirects, $href)) {
			$this->redirect_to($href);
		}
		
		if ($this->testWildcard($this->config->get('fx_404_pattern'), $href) || $this->multiTest($this->config->get('fx_404_get'), $href)) {
			$this->code404();
		}
		
		if ($this->testWildcard($this->config->get('fx_canonical_pattern'))) $out['canonical'] = $href;
		
		$this->delLinks($href, array("canonical"));
		if (!empty($out['canonical']))	$this->document->addLink($out['canonical'], 'canonical');
		
		return;
	}
	
	public function header_old($data) {
		
		
		//$out['title'] = $this->fx->getTitle();
		
		if (empty($out['title'])) $out['title'] = $data['title'];
		
		$out['page'] = $page = isset($this->request->get['page']) ? (int)$this->request->get['page'] : 0;		
		
		$after = (($page > 1) && $this->config->get('fx_page')) ? ' - '. 'страница' .' '.$page : '';		
		
		$out['title'] .= $after;		
		
		if ($this->config->get('fx_block_noindex')) $out['robots'] = $out['noindex'] = false;
		
		//$out['title'] .= '[fx]';
		
		$out['fx_robots'] = '';
		
		if ($page == 1) { 
			$out['fx_robots'] = '<meta name="robots" content="noindex, nofollow"/>';
		} else if (($page > 1) && $this->config->get('fx_yandex')) {
			$out['fx_robots'] = '<meta name="yandex" content="noindex' . ($this->config->get('fx_follow') ? '"/>' : ', nofollow"/>') ;
		} elseif ($this->multiTest($this->config->get('fx_noindex_get')) || $this->testWildcard($this->config->get('fx_noindex_mask'))) {
			$out['fx_robots'] = '<meta name="'.$this->config->get('fx_noindex_name').'" content="noindex' . ($this->config->get('fx_follow') ? '"/>' : ', nofollow"/>') ;
		}

		return $out;

    }
	
	public function header($data) {		
		
		if ($this->cancel()) return array();
		
		//$out['title'] = $this->fx->getTitle();
		
		if (empty($out['title'])) $out['title'] = $data['title'];
		
		$out['description'] = empty($data['description']) ? '' : $data['description'];
		
		$this->load->language('extension/module/fx');		
		
		$out['page'] = $page = isset($this->request->get['page']) ? (int)$this->request->get['page'] : 0;		
		
		$after = (($page > 1) && $this->config->get('fx_page')) && !$this->config->get('fx_title_pattern') ? ' - '. $this->language->get('text_page') .' '. $page : '';			
		
		$out['title'] .= $after;	
		
		if ($this->config->get('fx_block_noindex') && !$this->multiTest($this->config->get('fx_noindex_exceptions_get'))) $out['robots'] = $out['noindex'] = $out['show_meta_robots'] = false;
		
		//$out['title'] .= '[fx]';
		
		$out['fx_robots'] = '';
		
		if ($page == 1) $out['fx_robots'] = '" />'.PHP_EOL.'<meta name="robots" content="noindex, nofollow';

		if ($this->config->get('fx_noindex') && ($this->multiTest($this->config->get('fx_noindex_get')) || $this->testWildcard($this->config->get('fx_noindex_mask')))) {
			$out['fx_robots'] = '" />'.PHP_EOL.'<meta name="'.$this->config->get('fx_noindex_name').'" content="noindex' . ($this->config->get('fx_follow') ? '' : ', nofollow');
		}
		
		$out['description'] .= $out['fx_robots'];
		
		$out['fx_robots'] = '';

		if ((float)VERSION < 1.9 ){
			$this->fuck15($out);
		} else {
			return $out;
		}
		
		return $out;

    }
}