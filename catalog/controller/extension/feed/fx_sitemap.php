<?php
class ControllerExtensionFeedFxSitemap extends Controller {

    private $host = '';
    private $excluded = array();
    private $settings = array();
    private $get_arr = array();
    private $categories = false;
    private $mode = '';
	
	public function index() {
		
		$cat = '';
		
		$this->settings = $settings = json_decode($this->config->get('fx_sitemap_settings'), true);
		
		if (!$settings['sitemap_on']) exit;
		
		$this->load->model('extension/module/fx_sitemap');
		
		$this->language_id();
		
		if ($settings['multistore']) $this->model_extension_module_fx_sitemap->isMulti(true);
		
		$host = $this->config->get('config_secure') ? str_replace('http:/', 'https:/', $this->config->get('config_ssl')) : $this->config->get('config_url');
		$ssl = $this->config->get('config_secure') ? 'https://' : 'http://';
		
		/*if (substr_count($host, "/") > 3){		
			$ssl = $this->config->get('config_secure') ? 'https://' : 'http://';
			$host = $ssl . $this->request->server['HTTP_HOST'];
		}*/
		
		if (isset($this->request->get['host'])) $host = $this->request->get['host'];
		
		$this->host = $host = rtrim($host, '/') . '/' . (isset($this->request->get['prefix']) ? $this->request->get['prefix'] . '/' : '');
		
        $get_arrs = array();
        
        if ($settings['default']){
            $parse = parse_url(str_replace('&amp;', '&', $settings['default']));          
            parse_str($parse['query'], $get_arrs);
        }
		
		$get_arr = array();
		
		$arrs = array('limit', 'page', 'part', 'express', 'express_cat', 'ultra', 'file', 'key', 'save', 'multi', 'img', 'blog', 'article', 'news', 'language', 'prefix', 'host');
		
		foreach ($arrs as $g) {
			if (isset($get_arrs[$g])){
				$get_arr[$g] = $get_arrs[$g];
			}
			if (isset($this->request->get[$g])){
				$get_arr[$g] = $this->request->get[$g];
				if ($this->request->get[$g] == 'off') unset($get_arr[$g]);
			}
			
		}
		
		if (isset($this->request->get['page'])){
			$get_arr['part'] = $this->request->get['page'];
		}
		if (isset($this->request->get['part']) || isset($this->request->get['page'])){
			unset($get_arr['multi']);
			$settings['multi'] = false;
			
		}
		if (isset($this->request->get['multi']) && $this->request->get['multi'] == 'off'){
			$get_arr['multi'] = false;
		}
		
		$this->get_arr = $get_arr;
		
		$this->settings = $settings = array_merge($settings, $get_arr);
		
		$mode = $settings['express'] ? 'express' : ($settings['ultra'] ? 'ultra' : '');		
		
		$excluded = $this->excluded = $settings['exclude_file_on'] ? $this->check("delete.sitemap") : array();
		$plus  =  $settings['add_file_on'] ? $this->check("add.sitemap") : array();
		
		$start_time = (time() + (float)microtime());
		
		$total = $total_p = 0;

		$url = '';
		
		if ($settings['multi'] || !isset($settings['part']) || (isset($settings['part']) && (int)$settings['part'] == 0)){
		
			if ($settings['products_on']){
				if (isset($settings['img'])){
					$total_p =  $settings['img'] == 1 ? $this->model_extension_module_fx_sitemap->getProductsTotal() : $this->model_extension_module_fx_sitemap->getImgTotal();
					$url .= '&amp;img='. (int)$settings['img'];
				}else{
					$total_p = $this->model_extension_module_fx_sitemap->getProductsTotal();
				}
			}

			$limit = (isset($settings['limit']) && ((int)$settings['limit'] > 0)) ? (int)$settings['limit'] : 47999;
			//if ($total_p > $limit) {
				$total += $total_c = !$settings['categories_on'] ? 0 : $this->model_extension_module_fx_sitemap->getCategoriesTotal();
				$total += $total_m = !$settings['brands_on'] ? 0 : $this->model_extension_module_fx_sitemap->getManufacturersTotal();
				$total += !$settings['news_on'] ? 0 : $this->model_extension_module_fx_sitemap->getAllNewsTotal();
				$total += !$settings['blog_on'] ? 0 : $this->model_extension_module_fx_sitemap->getAllBlogTotal();
				$total += !$settings['mfp_on'] ? 0 : $this->model_extension_module_fx_sitemap->getMFPTotal();
				$total += !$settings['ocfilter_on'] ? 0 : $this->model_extension_module_fx_sitemap->getOCFilterTotal();
				$total += !$settings['informations_on'] ? 0 : $this->model_extension_module_fx_sitemap->getInformationsTotal();
				$total += !$settings['records_on'] ? 0 : $this->model_extension_module_fx_sitemap->getCMSBlogTotal();
				$total += !$settings['article_on'] ? 0 : $this->model_extension_module_fx_sitemap->getArticlesTotal();
				$total += !$settings['add_file_on'] ? 0 : count($plus);
				$total += !$settings['exclude_file_on'] ? 0 : -count($plus);
			//}
			
		}	
		
		if ($settings['multi']){
			
			unset($settings['multi']);
			
			$m_arrs = array("limit", "express", "express_cat", "ultra", "file", "key", "save", "img", "blog", "article", "news", "language", "prefix");

			$limit = (isset($settings['limit']) && ((int)$settings['limit'] > 0)) ? (int)$settings['limit'] : 47999;
			
			if (isset($get_arr['limit'])){
				$url .= '&amp;limit='. (int)$settings['limit'];
			}
			
			foreach ($m_arrs as $g) {
				if (isset($this->request->get[$g]))	$url .= '&amp;'. $g .'='. $this->request->get[$g];
			}
			
			$file_access = false;
			
			if(isset($settings['key']) && ($settings['key'] == $settings['key'])) $file_access = true;				
			if(!$settings['key']) $file_access = true;
			
			$output = '<?xml version="1.0" encoding="utf-8"?>';
			
			$output = '<?xml version="1.0" encoding="UTF-8"?><?xml-stylesheet type="text/xsl" href="../catalog/view/theme/default/stylesheet/fx_sitemap.xsl"?>';
			
			$output .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		
			if (($total + $total_p) > $limit) {
				$num = ceil($total / $limit);
				$num_p = ceil($total_p / $limit);
				$n = 1;
				
				do {
					$go = $host . 'index.php?route=extension/feed/fx_sitemap&amp;part=0.' . $n . str_replace("{n}", $n, $url);					
					$file = isset($settings['file']) ? $host . $settings['file']. '0.' . $n.'.xml' : $go;
					
					$output .= '<sitemap>';
					$output .= '<loc>' . $file . '</loc>';
					$output .= '</sitemap>';
					
					if (isset($settings['file']) && isset($settings['save']) && $file_access){
						$out = $this->cache->get('fx.sitemap.0.' . $n);
						if (!$out) {
							$out = $this->goSitemap($go);
							$this->cache->set('fx.sitemap.0.' . $n, $out);
						} else {							
							file_put_contents($settings['file'].'0.' . $n .'.xml', $out);
						}
					}
					
					$n++;
				} while ($n <= $num);
				
				$n = 1;
				
				do {
					$go = $host . 'index.php?route=extension/feed/fx_sitemap&amp;part=' . $n . str_replace("{n}", $n, $url);					
					$file = isset($settings['file']) ? $host . $settings['file'].$n.'.xml' : $go;
					
					$output .= '<sitemap>';
					$output .= '<loc>' . $file . '</loc>';
					$output .= '</sitemap>';
					
					if (isset($settings['file']) && isset($settings['save']) && $file_access){
						$out = $this->cache->get('fx.sitemap.' . $n);
						if (!$out) {
							$out = $this->goSitemap($go);
							$this->cache->set('fx.sitemap.' . $n, $out);
						} else {							
							file_put_contents($settings['file']. $n .'.xml', $out);
						}
					}
					
					$n++;
				} while ($n <= $num_p);

			}else{
				$url .= '&amp;multi=off';
				$go = $host . 'index.php?route=extension/feed/fx_sitemap' . $url;					
				$file = isset($settings['file']) ? $host . $settings['file'].'.xml' : $go;
				
				$output .= '<sitemap>';
				$output .= '<loc>' . $file . '</loc>';
				$output .= '</sitemap>';
				
				if (isset($settings['file']) && isset($settings['save']) && $file_access){
					$out = $this->goSitemap($go);
				}
			}

				$output .= '</sitemapindex>';
				
				$this->response->addHeader('Content-Type: application/xml');
				$this->response->setOutput($output);

			
		}else{
			
			$output  = '<?xml version="1.0" encoding="UTF-8"?>';
			$output = '<?xml version="1.0" encoding="UTF-8"?><?xml-stylesheet type="text/xsl" href="../catalog/view/theme/default/stylesheet/fx_sitemap.xsl"?>';
			if (!isset($settings['img'])){
				$output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
			}else{
				$output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';
			}
			
			$data['limit'] = $settings['limit'] ? (int)$settings['limit'] : 49499;
			
			$data['start'] = isset($settings['part']) ? ((int)$settings['part'] - 1) * (int)$data['limit'] : 0;
			
			if ($data['start'] < 0) $data['start'] = 0;
			
			$out = array();

			if ($settings['product_changefreq'] == 'off') $settings['product_changefreq'] = '';
			if ($settings['category_changefreq'] == 'off') $settings['category_changefreq'] = '';
			if ($settings['brands_changefreq'] == 'off') $settings['brands_changefreq'] = '';
			
			
			if ($settings['products_on'] && !((isset($settings['part'])) && (float)$settings['part'] < 1)){  ///// PRODUCTS
			
				if ($mode == 'express' || $this->settings['multilang']){   /////  EXPRESS
				
					$products = $this->model_extension_module_fx_sitemap->getProductsExpress($data);
					
					$date = '';
					
					foreach ($products as $product) {										
						if (!isset($product['keyword'])) {						
							$url = $host . 'index.php?route=product/product&amp;product_id=' . $product['product_id'];					
						}else {						
							$url = $host . $product['keyword'] . $settings['postfix'];			
						}
						
						if ($settings['product_lastmod']) $date = (int)$product['date_modified'] > 2000 ? substr($product['date_modified'], 0, 10) : '';
						
						$output .= $this->xml($url, $date, $settings['product_changefreq'], $settings['product_priority']);
					}
				}else if ($mode == 'ultra'){   /////  ULTRA
				
					$this->cacher();
					
					$products = $this->model_extension_module_fx_sitemap->getProductsUltra($data);
					
					$date = '';
					
					$categories_cache = $this->categories;
					
					foreach ($products as $product) {
						
						$category_url = $product_url = '';
						
						$in_category = !empty($product['category_id']) && isset($categories_cache[$product['category_id']]);
						
						$short = !empty($product['keyword']) && !$in_category;						
						
						$is_seo_url = $short || (!empty($product['keyword'] && $in_category && $categories_cache[$product['category_id']]['is_seo']));

						if (!$is_seo_url){ 
							$category_url = 'index.php?route=product/product&amp;product_id=';
							if ($in_category) $category_url = 'index.php?route=product/product&amp;path=' . $product['category_id'] . '&amp;product_id=';
							$product_url = $product['product_id'];
						} else {
							$category_url = $short ? '' : rtrim($categories_cache[$product['category_id']]['url'], '/') . '/';
							$product_url = $product['keyword'] . $settings['postfix'];					
						}
						
						$url = $host . $category_url . $product_url;
						
						if ($settings['product_lastmod']) $date = (int)$product['date_modified'] > 2000 ? substr($product['date_modified'], 0, 10) : '';
						
						$output .= $this->xml($url, $date, $settings['product_changefreq'], $settings['product_priority']);
					}
					
				}else{   /////  NORMAL
				
					if ( isset($settings['img']) && ($settings['img'] == 1) ){
						$products = $this->model_extension_module_fx_sitemap->getImgOne($data);
					}elseif (isset($settings['img'])){
						$products = $this->model_extension_module_fx_sitemap->getImg($data);
					}else{
						$products = $this->model_extension_module_fx_sitemap->getProducts($data);
					}
					
					$date = '';
					
					foreach ($products as $product) {
					
						if ($settings['product_lastmod']){
					
							$date = date_format( new DateTime($product['date_modified']), 'Y-m-d');
							if ($date == '-0001-11-30') $date = date_format( new DateTime($product['date_added']), 'Y-m-d'); 
							if ($date == '-0001-11-30') $date = ''; 
						
						}
						
						$url= str_replace("&product_id=", "&amp;product_id=", $this->url->link('product/product', 'product_id=' . $product['product_id']));
						
						$output .= $this->xml($url, $date, $settings['product_changefreq'], $settings['product_priority'], isset($settings['img']) ? $product : array());

					}
				}
			}	
		
			if (!isset($settings['part']) || ((int)$settings['part'] == 0)) {
				
				$subpart = 1;
				
				$out = array();
				
				$i = 1;
				
				if (isset($settings['part'])) $subpart = (int)(str_replace('0.', '', (string)$settings['part']));
				$subpart = $subpart ? $subpart : 1;
				
				$limit = $data['limit'] = (int)$settings['limit'] > 0 ? (int)$settings['limit'] : 49499;
			
				$start = $data['start'] = ($subpart - 1) * $data['limit'];
				
				$max = isset($settings['part']) ? $data['start'] + $data['limit'] : $limit;
				
				//$i = $start > 0 ? $start : 1;
				
				while ($i <= $max){				
					
					if ($settings['categories_on']){ /////  CATEGORIES ADD
					
						$this->cacher();
						
						unset($this->categories[0]);

						//array_splice($this->categories, 0, $i);
						
						$date = '';
						
						foreach ($this->categories as $category){
							if ($i > $start){
								$url = $category['url'];
								$url = $host . (!$category['is_seo'] ? 'index.php?route=product/category&amp;path' . $url : $url);
								
								if ($settings['category_lastmod']) $date = $category['date_modified'];
								
								$out[$i] = $this->xml($url, $date, $settings['category_changefreq'], $settings['category_priority']);
							}
							$i++; if ($i > $max) break(2);								
						}
					}
					
					
					
					if ($settings['brands_on']){					
						$manufacturers = ($mode == 'express' || $mode == 'ultra') ? $this->model_extension_module_fx_sitemap->getManufacturersExpress() : $this->model_extension_module_fx_sitemap->getManufacturers();
						foreach ($manufacturers as $manufacturer) {
							if ($i > $start){
								$url= isset($manufacturer['keyword']) ? $host . $manufacturer['keyword'] . $settings['postfix'] : str_replace("&manufacturer_id=", "&amp;manufacturer_id=", $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer['manufacturer_id']));
								$out[$i] = $this->xml($url, '', $settings['brands_changefreq'], $settings['brands_priority']);
							}
							$i++; if ($i > $max) break(2);
						}
					}
					
					
					if ($settings['informations_on']){
						$informations = ($mode == 'express' || $mode == 'ultra') ? $this->model_extension_module_fx_sitemap->getInformationsExpress() : $this->model_extension_module_fx_sitemap->getInformations();
						foreach ($informations as $information) {
							if ($i > $start){
								$url= isset($information['keyword']) ? $host . $information['keyword'] . $settings['postfix'] : str_replace("&information_id=", "&amp;information_id=", $this->url->link('information/information', 'information_id=' . $information['information_id']));
								$out[$i] = $this->xml($url);
							}
							$i++; if ($i > $max) break(2);
						}
					}
					
					
					if ($settings['blog_on']){
						
						$route = $settings['records_on'] ? 'record/blog' : $settings['blog_route'];
						$blog = empty($route) ? array() : $this->model_extension_module_fx_sitemap->getAllBlog();
						foreach ($blog as $new) {
							if ($i > $start){
								$url= str_replace("&b", "&amp;b", $this->url->link( $route, 'blog_id=' . $new['blog_id']));
								$out[$i] = $this->xml($url); 
							}
							$i++; if ($i > $max) break(2);
						}
					}
					
					if ($settings['news_on']){
						
						$route = $settings['news_route'];
						$news = empty($route) ? array() : $this->model_extension_module_fx_sitemap->getAllNews();
						foreach ($news as $new) {
							if ($i > $start){
								$url= str_replace("&n", "&amp;n", $this->url->link( $route, 'news_id=' . $new['news_id']));
								$out[$i] = $this->xml($url);
							}
							$i++; if ($i > $max) break(2);
						}
					}
					
					if ($settings['article_on']){					
						$route = $settings['article_route'];
						$articles = empty($route) ? array() : $this->model_extension_module_fx_sitemap->getArticles();
						foreach ($articles as $new) {
							if ($i > $start){
								$url= str_replace("&ar", "&amp;ar", $this->url->link( $route, 'article_id=' . $new['article_id']));
								$out[$i] = $this->xml($url);
							}
							$i++; if ($i > $max) break(2);
						}					
					}
					
					if ($settings['records_on']){
						
						$blog = $this->model_extension_module_fx_sitemap->getCMSBlog();
						$route = 'record/record' ;
						foreach ($blog as $new) {
							if ($i > $start){					
								$url= str_replace("&r", "&amp;r", $this->url->link( $route, 'record_id=' . $new['record_id']));						
								$date = (int)$new['date_modified'] > 2000 ? substr($new['date_modified'], 0, 10) : '';						
								$out[$i] = $this->xml($url, $date);
							}
							$i++; if ($i > $max) break(2);
						}
					}
					
					$slash = $settings['slash'] ? '/' : '';
					
					if ($settings['mfp_on']){
						$mfp = $this->model_extension_module_fx_sitemap->getMFP();
						foreach ($mfp as $new) {
							if ($i > $start){
								$url= $host.$new['path'] . '/' . $new['alias'] . $slash;
								$out[$i] = $this->xml($url);
							}								
							$i++; if ($i > $max) break(2);
						}
					}
					
					if ($settings['ocfilter_on']){
						$ocf = $this->model_extension_module_fx_sitemap->getOCFilter();
						foreach ($ocf as $new){
							if ($i > $start){
								$url= str_replace("&p", "&amp;p", $this->url->link('product/category', 'path=' . $new['category_id'])) . $slash . $new['keyword'];
								$out[$i] = $this->xml($url);
							}
							$i++; if ($i > $max) break(2);
						}
					}

					if ($settings['filterpro_on']){				
						$fp = $this->model_extension_module_fx_sitemap->getFilterPro();
						foreach ($fp as $new) {
							if ($i > $start){
								$fdata = unserialize($new['data']);
								parse_str(str_replace('&amp;', '&', $fdata['url']), $un_data);
								if (isset($un_data['route'])){
									if($un_data['route'] == 'product/category') {
										$url = $this->url->link($un_data['route'], 'path=' . (isset($un_data['path']) ? $un_data['path'] : $un_data['category_id']) . '&' . $new['url']);
									} else if(strpos($un_data['route'], 'product/manufacturer/') !== false) {
										$url = $this->url->link($un_data['route'], 'manufacturer_id=' . $un_data['manufacturer_id'] . '&' . $new['url']);
									} else {
										$url = $this->url->link($un_data['route'], $new['url']);
									}
									
									$out[$i] = $this->xml($url);
								}
							}
							$i++; if ($i > $max) break(2);
						}
					}
					
					if ($settings['add_file_on']){	   ////// ADD			
						foreach ($plus as $p) {
							if ($i > $start) $out[$i] = $this->xml(str_replace("&", "&amp;", $p));
							$i++; if ($i > $max) break(2);
						}
					}
					
					break;
				}
			}
			
			//var_dump($out);
			
			$lines = $out;
			
			foreach ($lines as $line) {
				$output .= $line;
			}
			
			if (isset($settings['part']) && (int)$settings['part'] > 1 && !strpos($output, 'l>')){
				$output .= '<url><loc>'. $host .'</loc><changefreq>daily</changefreq><priority>1</priority></url>';
			}
			
			$output = $output . '</urlset>';
			
			$file_access = false;						
			if(isset($this->request->get['key']) && ($settings['key'] == $this->request->get['key'])) $file_access = true;							
			if(!$settings['key']) $file_access = true;
			
			
			if($settings['log']){
			
				$ip = isset($this->request->server['REMOTE_ADDR']) ? $this->request->server['REMOTE_ADDR'] : "N/A";
				$ua = isset($this->request->server['HTTP_USER_AGENT']) ? $this->request->server['HTTP_USER_AGENT'] : "N/A";
				$uri = isset($this->request->server['REQUEST_URI']) ? $this->request->server['REQUEST_URI'] : "N/A";
				
				if (strpos($ua, 'Googlebot')) $ua = 'Google Bot';
				if (strpos($ua, 'YandexBot')) $ua = 'Yandex Bot';
				if (strpos($ua, 'Mail.RU_Bot')) $ua = 'Mail.RU Bot';
				if (strpos($ua, 'bingbot')) $ua = 'Bing Bot';
				
				$end = (time() + (float)microtime());
				$time = round($end - $start_time, 5)*1000;

				file_put_contents(DIR_LOGS.'fx_sitemap.log', ("[" . date("d.m.Y H:i") . "] " . $ip . " : " . $ua . "\n" . $time . "мс  | " . $uri. "\n ------------ \n"), FILE_APPEND);
			}
			
			if (isset($settings['file'])){
				if ($file_access){
					file_put_contents(str_replace("{n}", '', $settings['file']).'.xml', $output);
				}else{
					echo('<h1 style="position: absolute; top:50%; left: 50%">Access Denied<h1>');					
					exit;
				}
				
			}
			$this->response->addHeader('Content-Type: application/xml');
			$this->response->setOutput($output);			
		}
		
	}

	protected function goSitemap($url){
	
		//$pagecode = file_get_contents($url);/*
		$headers = array('HTTP_ACCEPT: Something', 'HTTP_ACCEPT_LANGUAGE: ru, en, da, nl', 'HTTP_CONNECTION: Something');
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.1 (compatible; MSIE 6.0; Windows NT 5.1; FX Sitemap)');
		curl_setopt($ch, CURLOPT_URL, str_replace("&amp;", "&", $url));
		$pagecode = curl_exec( $ch );
		curl_close($ch);//*/
		return $pagecode;
	}
	
	protected function addhost($url, $host){
		$outurl = $host.$url;
		return $outurl;
	}
		
	protected function check($var){
		
		if (!file_exists(DIR_CONFIG.$var)) return array();
		
		$arr = mb_split(PHP_EOL, file_get_contents(DIR_CONFIG.$var));
		
		foreach ($arr as $key=>&$value) {
			if (strlen($value) < 10) {
				unset($arr[$key]);
			}
		}
		
		return $arr;
	}
	
	protected function getCategories($express = false) {
		
		if ($this->settings['multilang'] || $this->settings['express_cat']) return $this->getCategoriesExpress(); /////  EXPRESS
		
		if ($this->settings['categories_from_db']) return $this->getCategoriesDB();
		
		$results = $this->model_extension_module_fx_sitemap->getCategories();
		
		$total = count($results);
		$output ='';				
		$cat_cache = array();
		
		$urls_cache = $this->cache->get('fx.sitemap.categories');
		
		if ($urls_cache) $this->categories = $urls_cache;
		
		foreach ($results as $result) {
			
			$cat_cache[$result['category_id']]['date_modified'] = $date = (int)$result['date_modified'] > 2000 ? substr($result['date_modified'], 0, 10) : ((int)$result['date_added'] > 2000 ? substr($result['date_added'], 0, 10) : '');
			
			if ($urls_cache && isset($urls_cache[$result['category_id']])){				
				$url = $urls_cache[$result['category_id']]['url'];			
			} else {
			
				$p = $this->url->link('product/category', 'path=' . $result['category_id']);
			
				$parts = parse_url($p);
				
				if (isset($parts['query'])){
					parse_str($parts['query'], $gets);
					$url = '=' . $gets['amp;path'];
				} else {
					$url = ltrim($parts['path'], '/');
				}
				
				$cat_cache[$result['category_id']]['url'] = $url;	
			}
			
			//$output .= $this->xml($url, $date);
		}
		
		if (!$urls_cache) { 
			$this->cache->set('fx.sitemap.categories', $cat_cache);
			$this->categories = $cat_cache;
		}
		
		return $output;
	}
	
	protected function getCategoriesDB($express = false) {
		
		$results = $this->model_extension_module_fx_sitemap->getCatDB();
		
		$output ='';
		
		$urls = array();
		
		foreach ($results as $result) {

			$date = $urls[$result['category_id']]['date_modified'] = $result['date_modified'];
			
			$url = $urls[$result['category_id']]['url'] = $result['url'];
			
			$is_seo = $urls[$result['category_id']]['is_seo'] = $result['is_seo'];
			
			//$output .= $this->xml($this->host . $url, $date);
		}
		
		$this->categories = $urls;
		
		return $output;
	}
	
	protected function getCategoriesExpress(){
		
		$categories = $this->model_extension_module_fx_sitemap->getCategoriesExpress();
		
		foreach ($categories as $category) {										
			if (!isset($category['keyword'])) {						
				$url = $urls[$category['category_id']]['url'] = '=' . $category['category_id'];
				$url = $urls[$category['category_id']]['is_seo'] = 0;
			}else {						
				$url = $urls[$category['category_id']]['url'] = $category['keyword'];
				$url = $urls[$category['category_id']]['is_seo'] = 1;
			}
			
			$date = $urls[$category['category_id']]['date_modified'] = (int)$category['date_modified'] > 2000 ? substr($category['date_modified'], 0, 10) : '';
			
			//$output .= $this->xml($url, $date, false);
		}
		
		$this->categories = $urls;
		
		return $urls;
	}
	
	protected function xml($url, $date = '', $changefreq = 'weekly', $priority = '0.7', $data = array()) {
		
		if ($this->settings['only_seo_url'] && strpos($url, '=')) return '';
		
		if ($this->settings['exclude_file_on'] && in_array($url, $this->excluded)) return '';

		$output = '<url>';
		$output .= '<loc>' . $url . '</loc>';
		$output .= $date ? '<lastmod>' . $date . '</lastmod>' : '';
		$output .= $changefreq ? '<changefreq>' . $changefreq . '</changefreq>' : '';
		$output .= $priority ? '<priority>' . $priority . '</priority>' : '';
		
		if (!empty($data) && isset($data['image']) && !empty($data['image'])){
			$temp = explode(",", $data['image']);
			$name = htmlspecialchars($data['name'], ENT_QUOTES);
			foreach ($temp as $imgage) {
				$output .= '<image:image>';
				$output .= '<image:loc>' . $this->host . 'image/' . $imgage .'</image:loc>';
				$output .= '<image:caption>' . $name . '</image:caption>';
				$output .= '<image:title>' . $name . '</image:title>';
				$output .= '</image:image>';
			}
		}
		
		$output .= '</url>';
		
		return $output;
	}

	
	public function cacher() {
		
		if ($this->categories) return true;

		if ($this->settings['multilang'] || $this->settings['express_cat']) {
			$this->getCategoriesExpress(); /////  EXPRESS
			return true;			
		}
		
		if ($this->settings['categories_from_db']){
			$this->getCategoriesDB();
			return true;	
		}		
		
		$urls_cache = $this->cache->get('fx.sitemap.categories');

		$this->categories = $urls_cache;
		
		if ($urls_cache) return true;
		
		$domain = str_replace(array('http://', 'https://'), '', $this->config->get('config_url'));
		
		$start = (time() + (float)microtime());
		
		$this->load->model('extension/module/fx_sitemap');
		
		//$this->model_extension_module_fx_sitemap->lang();
		
		$results = $this->model_extension_module_fx_sitemap->getCategories();
		
		$cat_cache = array(0 => array('url' => '/'));

		foreach ($results as $result) {	
		
			$date = (int)$result['date_modified'] > 2000 ? substr($result['date_modified'], 0, 10) : '';
		
			if((float)VERSION >= 4){ //потом
	
				$this->load->model('localisation/language');
				$languages = $this->model_localisation_language->getLanguages();
				
				foreach ($languages as $language) {
				
					$is_seo = 1;
					
					$p = $this->url->link('product/category', ((float)VERSION >= 3.1 ? 'language=' . $this->config->get('config_language') . '&' : '') . 'path=' . $result['category_id']);
					
					$parts = parse_url($p);
					
					$url = ltrim($parts['path'], '/') . (isset($parts['query']) ? '?' . $parts['query'] : '');
					$url = str_replace('index.php?route=product/category&amp;path', '', $url, $no_seo);
					
					if ($no_seo) $is_seo = 0;
					
					$data[] = array(
						'category_id' => $result['category_id'],
						'url' => $url,
						'is_seo' => $is_seo,
						'store_id' => $result['store_id'],
						'date_modified' => $date,
						'language_id' => $language['language_id']
					);
				}
			} else {
				
				$p = $this->url->link('product/category', 'path=' . $result['category_id']);
				
				$parts = parse_url($p);
				
				$is_seo = 1;

				$url = ltrim($parts['path'], '/') . (isset($parts['query']) ? '?' . $parts['query'] : '');
				$url = str_replace('index.php?route=product/category&amp;path', '', $url, $no_seo);
				
				if ($no_seo) $is_seo = 0;
				
				$data[$result['category_id']] = array(
					'url' => $url,
					'is_seo' => $is_seo,
					'date_modified' => $date
				);
				
			}
			
		}
		
		$this->cache->set('fx.sitemap.categories', $data);

		$this->categories = $data;
		
		$end = (time() + (float)microtime());
		$time = round($end - $start, 5)*1000;		
		
		/*
		$this->response->addHeader('Content-Type: application/html');
		$this->response->setOutput($time . 'ms - ' . count($cat_cache) . ' values');
		
		return $time . 'ms - ' . count($cat_cache) . ' values';*/

	}
	
	protected function exclude($url){

		if (!$this->settings['exclude_file_on']) return false;
		
		foreach ($this->excluded as $ex){
			
			if (substr($ex, -1) == '*') {
				
				$find = trim(str_replace('*', '', $ex));
			
				if (strpos($url, $find) !== false) return true;
				
			}else{
			
				if ($url == $ex) return true;
			
			}

		}

		return false;
		
	}
	
	public function cat_db(){
		
		$data = array();
		
		$this->load->model('extension/module/fx_sitemap');
	
		$results = $this->model_extension_module_fx_sitemap->getCategoriesStore();
		
		$host = $this->config->get('config_secure') ? str_replace('http:/', 'https:/', $this->config->get('config_ssl')) : $this->config->get('config_url');
		
		foreach ($results as $result) {
			
			$date = date_format( new DateTime($result['date_modified']), 'Y-m-d');
			if ($date == '-0001-11-30') $date = date_format( new DateTime($result['date_added']), 'Y-m-d');
			if ($date == '-0001-11-30') $date = date('Y-m-d');			
			
			if((float)VERSION >= 3){
			
				$this->load->model('localisation/language');
				$languages = $this->model_localisation_language->getLanguages();
				
				foreach ($languages as $language) {
				
					$is_seo = 1;
					
					//$this->model_extension_module_fx_sitemap->lang($language['language_id']);
					
					$p = $this->url->link('product/category', ((float)VERSION >= 3.1 ? 'language=' . $this->config->get('config_language') . '&' : '') . 'path=' . $result['category_id']);
					
					$parts = parse_url($p);
					
					$url = ltrim($parts['path'], '/') . (isset($parts['query']) ? '?' . $parts['query'] : '');
					$url = str_replace('index.php?route=product/category&amp;path', '', $url, $no_seo);
					
					if ($no_seo) $is_seo = 0;
					
					$data[] = array(
						'category_id' => $result['category_id'],
						'url' => $url,
						'is_seo' => $is_seo,
						'store_id' => $result['store_id'],
						'date' => $date,
						'language_id' => $language['language_id']
					);
				}
			} else {
				
				$p = $this->url->link('product/category', 'path=' . $result['category_id']);
				
				$parts = parse_url($p);
				
				$is_seo = 1;

				$url = ltrim($parts['path'], '/') . (isset($parts['query']) ? '?' . $parts['query'] : '');
				$url = str_replace('index.php?route=product/category&amp;path', '', $url, $no_seo);
				
				if ($no_seo) $is_seo = 0;
				
				$data[] = array(
					'category_id' => $result['category_id'],
					'url' => $url,
					'is_seo' => $is_seo,
					'store_id' => $result['store_id'],
					'date' => $date
				);
				
			}
		}
		
		$results = $this->model_extension_module_fx_sitemap->PasteCatDB($data);
		
		$this->response->setOutput(count($data).' rows');
		
	}
	
	public function category_path($category_id){
	

		return $path;
		
	}
	
	public function language_id(){
		
		if (!isset($this->request->get['language'])) return (int)$this->config->get('config_language_id');
		
		$language = $this->request->get['language'];
		
		$this->load->model('extension/module/fx_sitemap');
		
		$this->model_extension_module_fx_sitemap->lang();
		
		$language_id = $this->model_extension_module_fx_sitemap->getLanguageId($language);

		echo $language_id;

		return $language_id;
		
	}
	
	public function update_dates(){
	
		$dates = array();		
		
		$this->load->model('extension/module/fx_sitemap');
		
		$categories = $this->model_extension_module_fx_sitemap->getCategoriesExpress();
		
		foreach ($categories as $category) {										
			
			$this->model_extension_module_fx_sitemap->setCategoryDate($category['category_id']);
			
		}
		
		$i = count($categories);

		echo $i;

		return $i;
		
	}
	
	protected function settings($settings){	

		$keys = array('default', 'key', 'slash', 'log');
		
		foreach ($keys as $key){
			
			if (!isset($settings[$key])) $settings[$key] = false;

		}
		

		return $settings;
		
	}
	
	public function clearlog(){

		file_put_contents(DIR_LOGS.'fx_sitemap.log', '');
		
	}
	
	public function getMode(){
		print('<pre>');
		var_dump($this->settings);
		print('</pre>');
		
	}
}
