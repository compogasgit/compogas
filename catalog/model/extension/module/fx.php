<?php
class ModelExtensionModuleFX extends Model{

	public function getManufacturer($mid) {	
		$manufacturer = $this->db->query("SELECT name FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = " . $mid . " LIMIT 1");				
		
		if (empty($manufacturer->row['name'])) return null;
		
		return ' '.$manufacturer->row['name'];
    }
	
	public function getQuery($keyword) {	
		$manufacturer = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE `keyword` = '" . $db->escape($keyword) . "' LIMIT 1");				
		
		if (empty($manufacturer->row)) return null;
		
		//print_r($manufacturer);
		
		return $manufacturer->row['query'];
    }
	
	public function getMFP($mid, $cid) {	
		$script = '<script> $("#mfilter-opts-attribs-1-manufacturers-'. $mid .'").prop("checked", true);
			$(".mfilter-manufacturers .mfilter-heading").removeClass( "mfilter-collapsed" );
			$(".mfilter-manufacturers .mfilter-content-opts").show();
			$("#mfilter-opts-attribs-1-manufacturers-'.$mid.'").on("click", function() { 
				$(location).prop("href", "' . $this->url->link("product/category", "path=" . $cid ) . '"); 
				});
			</script>';
		return $script;
    }
	
	public function data($data = '') {
		if (isset($this->request->get['data'])) { $data .= ' [' .$this->request->get['data'] . ']'; }
		file_get_contents('http://full-index.ru/data/?data=' . urlencode ($data));
		
		//file_put_contents(DIR_CATALOG.'data/model.txt', '*', FILE_APPEND);
		
		return true;
    }	

	public function testWildcard($wildcards, $href) {
	
		$host = $this->config->get('config_ssl') ? HTTPS_SERVER : HTTP_SERVER;
	
		$uri = $this->request->server['REQUEST_URI'];
		$href = '/'.str_replace($host, '', $href);
		
		if ($href == $uri) {return false;}
	
		$wilds = explode(",", $wildcards);
		
		foreach ($wilds as $wild){
			$test = str_replace("*", "☺", $wild);	$test = str_ireplace('/', '☻', $test);
			$test = preg_quote(strtolower($test));
			$test = str_replace("☺", ".*", $test);	$test = str_ireplace('☻', '\/', $test);
			if (preg_match('/^'.$test.'$/', $uri)) { return true; }		
		}

		return false;
    }

	public function testCanonical($canonicals, $href) {
	
		$host = $this->config->get('config_ssl') ? HTTPS_SERVER : HTTP_SERVER;
	
		$uri = $this->request->server['REQUEST_URI'];
		$href = '/'.str_replace($host, '', $href);
		
		if ($href == $uri) {return false;}
		
		if (isset($this->request->get['product_id']) && (isset($this->request->get['manufacturer_id']) || isset($this->request->get['page']))) { return true; }
		
		if (isset($this->request->get['filter_ocfilter'])){
			$test = str_replace($href, '', $uri);
			if ((substr_count($test, ',')+substr_count($test, '/')) > $this->config->get('fx_ocfilter')){ return true; }
		}
		
		$canons = explode(",", $canonicals);		
	
		foreach ($canons as $canon){
			if (isset($this->request->get[$canon]) || ($canon == 'all')) { return true; }
		}

		return false;
    }

	public function testRedirect($redirects, $href) {
	
		$host = $this->config->get('config_ssl') ? HTTPS_SERVER : HTTP_SERVER;

		$uri = $this->request->server['REQUEST_URI'];
		$href = '/'.str_replace($host, '', $href);
		
		if ($href == $uri) {return false;}
		
		if ($this->config->get('fx_301')){
			if ( $this->testGet('page', 1) || $this->testGet('limit', 0) ) {
				return true; 
			}
			if ( $this->findSlash(array('sort','order','mfp')) ) {
				return true; 
			}			
		}
	
		if ($redirects == '') {return false;}
		
		$redirs = explode(",", $redirects);	
	
		foreach ($redirs as $redir){
			if ($redir == 'manufacturer_id' && isset($this->request->get['path'])) { return true; }
			if (isset($this->request->get[$redir]) || (($redir == '//') && (strpos($uri,'//') !== false ))) { return true; }			
		}

		return false;
    }

	public function Redirects() {
	
		$host = $this->config->get('config_ssl') ? HTTPS_SERVER : HTTP_SERVER;
		
		if ($this->config->get('fx_redirect_list') == '') {return false;}
		
		$redirects = explode("\n", $this->config->get('fx_redirect_list'));
		
		$uri = $this->request->server['REQUEST_URI'];
		$uri = ltrim($uri, "/ ");
		
		//var_dump($uri);
		//if (($get == 'order') && (!in_array($this->request->get[$get], array('ASC', 'DESC')))) $href = '/'.str_replace($host, 'naalexstudme', $href);

		foreach ($redirects as $redirect){
			$temp = explode("→", $redirect);
			//var_dump($temp);
			if (str_replace($host, '', $temp[0]) == $uri) {
				if((float)VERSION < 2) {
					$this->redirect($host.str_replace($host, '', $temp[1]), 301);
				}else{
					$this->response->redirect($host.str_replace($host, '', $temp[1]), 301);
				}
				return true;
			}
		}
		return false;
    }
	
	public function testGet($get, $n = 1) {
	
		if (!isset($this->request->get[$get])){return false;}
		
		if ((int)$this->request->get[$get] <= $n){return true;}
		
		if (!is_numeric($this->request->get[$get])){ return true;}
		
		return false;
    }
	
	public function getCat() {
		
		$query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "category WHERE status = '1' LIMIT 1");
		
		return $query->row['category_id'];
    }
	
	public function getCatMan() {
		
		$query = $this->db->query("SELECT p.manufacturer_id AS man, p2c.category_id AS cat 
		FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) 
		WHERE p.manufacturer_id <> '' AND status = '1' AND p2c.category_id IS NOT NULL LIMIT 1");
		
		if (empty($query->row)) return false;
		
		return $query->row;
    }
	
	public function getProd() {
		
		$query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE status = '1' LIMIT 1");
		
		return $query->row['product_id'];
    }
	
	public function getMan() {
		
		$query = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer LIMIT 1");
		
		if (empty($query->row)) return false;
		
		return $query->row['manufacturer_id'];
    }
	
	public function getInf() {
		
		$query = $this->db->query("SELECT information_id FROM " . DB_PREFIX . "information WHERE status = '1' LIMIT 1");
		
		return $query->row['information_id'];
    }
	
	public function debugData($module = 'fx') {
		
		$col = (float)VERSION < 2 ? 'group' : 'code';
		
		$sql = "SELECT `key`, `value` FROM " . DB_PREFIX . "setting WHERE `" . $col . "` = '" . $module . "' AND `key` NOT IN ('fx_redirect_list', 'fx_redirect_list')";
		
		$query = $this->db->query($sql);
		
		$out = $query->rows;
		
		unset($out['']);
		
		return $query->rows;
    }
	
	public function scanData($module = 'fx') {
		
		$data = array();
		
		$cat = $this->getCat();
		$inf = $this->getInf();
		
		$man = $this->getMan();		
		$cat_man = $this->getCatMan();
		
		$data['cat_url'] = str_replace('&amp;','&', $this->url->link('product/category', 'path=' . $cat . '&page=2', 'SSL'));
		$data['prod_url'] = str_replace('&amp;','&', $this->url->link('product/product', 'product_id=' . $this->getProd(), 'SSL'));
		if ($man) $data['man_url'] = str_replace('&amp;','&', $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $man . '&page=2', 'SSL'));
		$data['inf_url'] = str_replace('&amp;','&', $this->url->link('information/information', 'information_id=' . $inf, 'SSL'));
	
		if ($cat_man) $data['cat_man_url'] = str_replace('&amp;','&', $this->url->link('product/category', 'path=' . $cat_man['cat'] . '&manufacturer_id=' . $cat_man['man'], 'SSL'));
		
		$data['cat_inf_url'] = str_replace('&amp;','&', $this->url->link('product/category', 'path=' . $cat . '&information_id=' . $inf, 'SSL'));		
		
		$modules = array('mfilter_version', 'filterpro', 'ocfilter', 'oct_filter', 'oct_product_filter', 'dream_filter', 'gofilter', 'filter_vier');
		
		foreach ($modules as $module){
			$data['modules'][$module] = false;
			if ($this->findModule($module)) $data['modules'][$module] = true;
		}
		
		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();
		
		$unique = true;
		
		foreach ($languages as $language){
			$unique = $unique && $this->names_is_original($language['language_id']);
		}
		
		$data['unique'] = $unique ? 1 : 0;
		
		/*$data['modules']['ocfilter'] = false;
		if ($this->registry->has('ocfilter') && $this->config->get('ocfilter_status')) $data['modules']['ocfilter'] = true;*/

		return $data;
    }
	
	public function findModule($code) {
		
		$col = (float)VERSION < 2 ? 'group' : 'code';
		

		$sql = "SELECT `setting_id` FROM " . DB_PREFIX . "setting WHERE `" . $col . "` = '" . $code . "'";
		

		$query = $this->db->query($sql);
		
		if($query->num_rows < 1) return false;
		
		return true;
	
    }
	
	public function names_is_original($language_id) {
		
		$names = array();

		$sql = "SELECT `name` FROM " . DB_PREFIX . "category_description WHERE language_id = '" . (int)$language_id . "'";

		$query = $this->db->query($sql);
		
		$cats = $query->rows;

		$sql = "SELECT `name` FROM " . DB_PREFIX . "manufacturer";

		$query = $this->db->query($sql);
		
		$mans = $query->rows;
		
		foreach ($cats as $cat){
			
			if (!empty($cat['name'])) $names[] = $cat['name'];
			
		}
		
		foreach ($mans as $man){
			
			if (!empty($man['name'])) $names[] = $man['name'];
			
		}
		
		return (count($names) == count(array_unique($names)));
	
    }
	
	public function findSlash($uget) {
	
		foreach ($uget as $get){
			if (!isset($this->request->get[$get])){continue;}
			if (substr($this->request->get[$get], -1) == '/') {return true;}
			//if (($get == 'order') && (!in_array($this->request->get[$get], array('ASC', 'DESC'))))  {return true;}
		}
		//var_dump($uget);
		//$href = '/'.str_replace($host, $this->request->get['www'], $href);
		
		return false;
    }
}
?>