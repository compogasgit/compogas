<?php
class ModelExtensionModuleFXSitemap extends Model{

private $is_multi = false;

private $language_id = false;
    
	public function isMulti($set = '') {
		
		if ($set) $this->is_multi = true;
		
		if ($set == false) $this->is_multi = false;
		
		

		/*$test = $this->db->query("SELECT store_id FROM " . DB_PREFIX . "store");
		
		$this->is_multi = $test->num_rows < 1 ? 0 : 1;*/
	
		return $this->is_multi;
	}
	
	public function lang($id = false) {
		
		if ($id == false) return false;
		
		$this->language_id = $id ? $id : (int)$this->config->get('config_language_id');
		
	}
	
	public function getLanguageId($language) {
		
		$query = $this->db->query("SELECT language_id FROM " . DB_PREFIX . "language WHERE code = '" . $language . "'");
		
		$language_id = !empty($query->row['language_id']) ? $query->row['language_id'] : $this->language_id;
		
		return $language_id;
		
	}
	
/*-------------------------------------------------------*/	
    
	public function getProducts($data = array()) {

		$query = "SELECT product_id, date_added, date_modified FROM " . DB_PREFIX . "product WHERE status = '1' LIMIT ".(int)$data['start'].", ".(int)$data['limit'];
		
		$query_m = "SELECT p.product_id, p.date_added, p.date_modified FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT ".(int)$data['start'].", ".(int)$data['limit'];
		
		if ($this->isMulti()) $query = $query_m;
		
		$query = $this->db->query($query);
	
		return $query->rows;
	}	
	
	public function getProductsTotal($data = array()) {

		$query = "SELECT COUNT(product_id) AS total FROM " . DB_PREFIX . "product WHERE status = '1'";	
		
		$query_m = "SELECT COUNT(p.product_id) AS total FROM " . DB_PREFIX . "product p INNER JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
		
		if ($this->isMulti()) $query = $query_m;
		
		$query = $this->db->query($query);
	
		return $query->row['total'];
	}
	
	public function getProductsExpress($data = array()) {
		
		if((float)VERSION >= 3) return $this->Express3($data);

		$query = "SELECT u.keyword, p.date_added, p.date_modified, p.product_id FROM " . DB_PREFIX . "product p INNER JOIN " . DB_PREFIX . "url_alias u ON (u.query = CONCAT('product_id=', p.product_id))  WHERE p.status = '1' LIMIT ".(int)$data['start'].", ".(int)$data['limit'];
		
		$query_m = "SELECT u.keyword, p.date_added, p.date_modified, p.product_id FROM " . DB_PREFIX . "product p INNER JOIN " . DB_PREFIX . "url_alias u ON (u.query = CONCAT('product_id=', p.product_id)) INNER JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT ".(int)$data['start'].", ".(int)$data['limit'];
		
		if ($this->isMulti()) $query = $query_m;
		
		$query = $this->db->query($query);
	
		return $query->rows;
	}
	
	public function Express3($data = array()) {
		
		$query = "SELECT u.keyword, p.date_added, p.date_modified, p.product_id 
			FROM " . DB_PREFIX . "product p 	
			INNER JOIN " . DB_PREFIX . "seo_url u ON (u.query = CONCAT('product_id=', p.product_id))			
			WHERE p.status = '1'";
		
		if ($this->isMulti()) $query .= " AND u.store_id = '" . (int)$this->config->get('config_store_id');
		
		if ($this->language_id != false) $query .= " AND u.language_id = '" . $this->language_id;
		
		$query .= 	" LIMIT ".(int)$data['start'].", ".(int)$data['limit'];
		
		$query = $this->db->query($query);
	
		return $query->rows;
	}

	public function getProductsUltra($data = array()) {

		if((float)VERSION >= 3) return $this->Ultra3($data);
		
		$query = "SELECT DISTINCT p.product_id, u.keyword, p.date_added, p.date_modified, 
			(SELECT category_id FROM " . DB_PREFIX . "product_to_category WHERE product_id=p.product_id ORDER BY main_category DESC LIMIT 1) as category_id 	
			FROM " . DB_PREFIX . "product p
			INNER JOIN " . DB_PREFIX . "url_alias u ON (u.query = CONCAT('product_id=', p.product_id))			
			WHERE p.status = '1'";

		$query .= 	" LIMIT ".(int)$data['start'].", ".(int)$data['limit'];

		if ($this->config->get('config_seo_url_type') != 'seo_pro') $query = str_replace(' ORDER BY main_category DESC', '', $query);
		
		$query = $this->db->query($query);
		
		return $query->rows;	
	}

	public function Ultra3($data = array()) {			
	
		$query = "SELECT DISTINCT u.keyword, p.date_added, p.date_modified, p.product_id,
			(SELECT category_id FROM " . DB_PREFIX . "product_to_category WHERE product_id=p.product_id ORDER BY main_category DESC LIMIT 1) as category_id 
			FROM " . DB_PREFIX . "product p 		
			INNER JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)		
			INNER JOIN " . DB_PREFIX . "seo_url u ON (u.query = CONCAT('product_id=', p.product_id))			
			WHERE p.status = '1'";
		
		if ($this->isMulti()) $query .= " AND u.store_id = '" . (int)$this->config->get('config_store_id');
		
		if ($this->language_id != false) $query .= " AND u.language_id = '" . $this->language_id;
		
		$query .= 	" LIMIT ".(int)$data['start'].", ".(int)$data['limit'];		
		
		if ($this->config->get('config_seo_url_type') != 'seo_pro') $query = str_replace(' ORDER BY main_category DESC', '', $query);
		
		$query = $this->db->query($query);			
		return $query->rows;	
	}

/*-------------------------------------------------------*/	
	public function getCategories() {
		$query = "SELECT category_id, date_added, date_modified FROM " . DB_PREFIX . "category WHERE status = '1'";
		
		$query_m = "SELECT c.category_id, c.date_added, c.date_modified FROM " . DB_PREFIX . "category c INNER JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1'";
		
		if ($this->isMulti()) $query = $query_m;
		
		$query = $this->db->query($query);

		return $query->rows;
	}
	
	public function getCategoriesLite() {		
		$query = "SELECT category_id FROM " . DB_PREFIX . "category WHERE status = '1'";				
		$query_m = "SELECT c.category_id FROM " . DB_PREFIX . "category c INNER JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1'";				
		
		if ($this->isMulti()) $query = $query_m;				
		
		$query = $this->db->query($query);		
		
		return $query->rows;	
		
	}
	
	public function getCategoriesExpress($data = array()) {
		
		if((float)VERSION >= 3) return $this->getCategoriesExpress3($data);

		$query = "SELECT u.keyword, c.date_added, c.date_modified, c.category_id FROM " . DB_PREFIX . "category c INNER JOIN " . DB_PREFIX . "url_alias u ON (u.query = CONCAT('category_id=', c.category_id)) WHERE c.status = '1'";
		
		$query_m = "SELECT u.keyword, c.date_added, c.date_modified, c.category_id FROM " . DB_PREFIX . "category c INNER JOIN " . DB_PREFIX . "url_alias u ON (u.query = CONCAT('category_id=', c.category_id)) INNER JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1'";
		
		if ($this->isMulti()) $query = $query_m;
		
		$query = $this->db->query($query);
	
		return $query->rows;
	}
	
	public function getCategoriesExpress3($data = array()) {
		
		$query = "SELECT u.keyword, c.date_added, c.date_modified, c.category_id 
			FROM " . DB_PREFIX . "category c 	
			INNER JOIN " . DB_PREFIX . "seo_url u ON (u.query = CONCAT('category_id=', c.category_id))			
			WHERE c.status = '1'";
		
		if ($this->isMulti()) $query .= " AND u.store_id = '" . (int)$this->config->get('config_store_id');
		
		if ($this->language_id != false) $query .= " AND u.language_id = '" . $this->language_id;
		
		$query = $this->db->query($query);
	
		return $query->rows;
	}
	
	public function getCategoriesStore() {
		
		$query = "SELECT c.category_id, c2s.store_id, c.date_added, c.date_modified FROM " . DB_PREFIX . "category c INNER JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.status = '1'";

		$query = $this->db->query($query);
		
		return $query->rows;
	}
	
	public function getCategoriesTotal() {
		$query = "SELECT COUNT(category_id) AS total FROM " . DB_PREFIX . "category WHERE status = '1'";
		
		$query_m = "SELECT COUNT(c.category_id) AS total FROM " . DB_PREFIX . "category c INNER JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1'";
		
		if ($this->isMulti()) $query = $query_m;
		
		$query = $this->db->query($query);

		return $query->row['total'];
	}
	
	public function getCatDB() {

		$query = "SELECT category_id, url, date_modified, is_seo FROM " . DB_PREFIX . "fx_sitemap_categories";
		
		$query_m = "SELECT category_id, url, date_modified, is_seo FROM " . DB_PREFIX . "fx_sitemap_categories WHERE store_id = '" . (int)$this->config->get('config_store_id') . "'";
		
		if ($this->isMulti()) $query = $query_m;
		
		$query = $this->db->query($query);

		return $query->rows;
	}
	
	public function getCatDBTotal() {
		$query = "SELECT COUNT(category_id) FROM " . DB_PREFIX . "fx_sitemap_categories";
		
		$query_m = "SELECT COUNT(category_id) FROM " . DB_PREFIX . "fx_sitemap_categories WHERE store_id = '" . (int)$this->config->get('config_store_id') . "'";
		
		if ($this->isMulti()) $query = $query_m;
		
		$query = $this->db->query($query);

		return $query->rows;
	}
	
	public function CreateCatDB() {	
		$query = "CREATE TABLE  `" . DB_PREFIX . "fx_sitemap_categories` (
		  `category_id` mediumint(8) NOT NULL,
		  `url` varchar(512) NOT NULL,
		  `is_seo` tinyint(1) NOT NULL,
		  `date_modified` date NOT NULL,
		  `store_id` smallint(3) NOT NULL,";
		
		if((float)VERSION >= 3){		
			$query .= " `language_id` smallint(2) NOT NULL,
			  KEY `store_id` (`store_id`),
			  KEY `language_id` (`language_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
		} else {
			$query .= "
			  KEY `store_id` (`store_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
		}
		
		$query = $this->db->query($query);	
		
		return true;
	}
	
	public function PasteCatDB($data){	
		
		$test = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "fx_sitemap_categories'");
		if($test->num_rows < 1) $this->CreateCatDB();
		
		
		$this->db->query("TRUNCATE TABLE " . DB_PREFIX . "fx_sitemap_categories");
		
		$i = 0;
		
		if((float)VERSION >= 3) return $this->PasteCatDB3($data);
		

		foreach ($data as $category){			
			$this->db->query("INSERT INTO " . DB_PREFIX . "fx_sitemap_categories 
			(`category_id`, `url`, `is_seo`, `date_modified`, `store_id`) 
			VALUES (" . (int)$category['category_id'] . ", '" . $this->db->escape($category['url']) . "', '" . $category['is_seo'] . "', '" . $category['date'] . "', '" . $category['store_id'] . "' )");
			$i++;
		}
		
		return $i;
	}
	
	public function PasteCatDB3($data){
		
		$i = 0; //count($data);

		foreach ($data as $category){			
			$this->db->query("INSERT INTO " . DB_PREFIX . "fx_sitemap_categories 
			(`category_id`, `url`, `is_seo`, `date_modified`, `store_id`, `language_id`) 
			VALUES (" . (int)$category['category_id'] . ", '" . $this->db->escape($category['url']) . "', '" . $category['is_seo'] . "', '" . $category['date'] . "', '" . $category['store_id'] . "', '" . $category['language_id'] . "' )");
			$i++;
		}
		
		return $i;
	}
	
	public function setCategoryDate($category_id){
	
		$p = $this->db->query("SELECT p.date_modified as date FROM " . DB_PREFIX . "product p INNER JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) WHERE p2c.category_id = '" . (int)$category_id . "' ORDER by p.date_modified DESC LIMIT 1");	
		
		if($p->num_rows < 1) return Array();
		
		$c = $this->db->query("SELECT date_modified as date FROM " . DB_PREFIX . "category WHERE category_id = '" . (int)$category_id . "'");
		
		
		if($c->num_rows < 1 || $p->row['date'] > $c->row['date']) $this->db->query("UPDATE " . DB_PREFIX . "category SET date_modified = '" . $p->row['date'] . "' WHERE category_id = '" . (int)$category_id . "'");
		
		return true;
	}

/*-------------------------------------------------------*/	
	
	public function getManufacturers() {
		
		$query = "SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer";
		
		$query_m = "SELECT m.manufacturer_id FROM " . DB_PREFIX . "manufacturer m INNER JOIN " . DB_PREFIX . "manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id) WHERE m2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		if ($this->isMulti()) $query = $query_m;
		
		$query = $this->db->query($query);			
		
		return $query->rows;
	}

	public function getManufacturersTotal() {
		
		$query = "SELECT COUNT(manufacturer_id) AS total FROM " . DB_PREFIX . "manufacturer";
		
		$query_m = "SELECT COUNT(m.manufacturer_id) AS total FROM " . DB_PREFIX . "manufacturer m INNER JOIN " . DB_PREFIX . "manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id) WHERE m2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		if ($this->isMulti()) $query = $query_m;
		
		$query = $this->db->query($query);	
		
		return $query->row['total'];
	}
		
	public function getManufacturersExpress($data = array()) {
		
		if((float)VERSION >= 3) return $this->getManufacturersExpress3($data);

		$query = "SELECT u.keyword, m.manufacturer_id FROM " . DB_PREFIX . "manufacturer m INNER JOIN " . DB_PREFIX . "url_alias u ON (u.query = CONCAT('manufacturer_id=', m.manufacturer_id))";
		
		$query_m = "SELECT u.keyword, m.manufacturer_id FROM " . DB_PREFIX . "manufacturer m INNER JOIN " . DB_PREFIX . "url_alias u ON (u.query = CONCAT('manufacturer_id=', m.manufacturer_id)) INNER JOIN " . DB_PREFIX . "manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id) WHERE m2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
		
		if ($this->isMulti()) $query = $query_m;
		
		$query = $this->db->query($query);
	
		return $query->rows;
	}
	
	public function getManufacturersExpress3($data = array()) {
		
		$query = "SELECT u.keyword, m.manufacturer_id
			FROM " . DB_PREFIX . "manufacturer m
			INNER JOIN " . DB_PREFIX . "seo_url u ON (u.query = CONCAT('manufacturer_id=', m.manufacturer_id))";
		
		if ($this->isMulti()) $query .= " AND u.store_id = '" . (int)$this->config->get('config_store_id');
		
		if ($this->language_id != false) $query .= " AND u.language_id = '" . $this->language_id;
		
		$query = $this->db->query($query);
	
		return $query->rows;
	}

/*-------------------------------------------------------*/		
	public function getAllNews() {
		$test = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "news'");
		if($test->num_rows < 1) return Array();
		
		$test = $this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . "news LIKE 'status'");
		$status = $test->num_rows < 1 ? "" : " WHERE status = '1'";
	
		$query = $this->db->query("SELECT news_id FROM " . DB_PREFIX . "news " . $status);	
		return $query->rows;
	}
	
	public function getAllNewsTotal() {
		$test = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "news'");
		if($test->num_rows < 1) return 0;
		
		$test = $this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . "news LIKE 'status'");
		$status = $test->num_rows < 1 ? "" : " WHERE status = '1'";
	
		$query = $this->db->query("SELECT COUNT(news_id) AS total FROM " . DB_PREFIX . "news " . $status);	
		return $query->row['total'];
	}
/*-------------------------------------------------------*/	
	
	public function getAllBlog() {
		$test = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "blog'");
		if($test->num_rows < 1) return Array();
		
		$test = $this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . "blog LIKE 'status'");
		$status = $test->num_rows < 1 ? "" : " WHERE status = '1'";
		
		$query = $this->db->query("SELECT blog_id FROM " . DB_PREFIX . "blog " . $status);
		return $query->rows;
	}
	
	public function getAllBlogTotal() {
		$test = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "blog'");
		if($test->num_rows < 1) return 0;
		
		$test = $this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . "blog LIKE 'status'");
		$status = $test->num_rows < 1 ? "" : " WHERE status = '1'";
		
		$query = $this->db->query("SELECT COUNT(blog_id) AS total FROM " . DB_PREFIX . "blog " . $status);
		return $query->row['total'];
	}
/*-------------------------------------------------------*/	
	
	public function getMFP() {
		$test = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "mfilter_url_alias'");
		if($test->num_rows < 1) return Array();
		
		$query = $this->db->query("SELECT path, alias FROM " . DB_PREFIX . "mfilter_url_alias");
		return $query->rows;
	}
	
	public function getMFPTotal() {
		$test = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "mfilter_url_alias'");
		if($test->num_rows < 1) return 0;
		
		$query = $this->db->query("SELECT COUNT(path) AS total FROM " . DB_PREFIX . "mfilter_url_alias");
		return $query->row['total'];
	}
/*-------------------------------------------------------*/	
	
	public function getOCFilter() {
		$test = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "ocfilter_page'");
		if($test->num_rows < 1) return Array();
		
		$query = $this->db->query("SELECT category_id, keyword FROM " . DB_PREFIX . "ocfilter_page WHERE status = '1'");
		return $query->rows;
	}
	
	public function getOCFilterTotal() {
		$test = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "ocfilter_page'");
		if($test->num_rows < 1) return 0;
		
		$query = $this->db->query("SELECT COUNT(keyword) AS total FROM " . DB_PREFIX . "ocfilter_page WHERE status = '1'");
		return $query->row['total'];
	}
/*-------------------------------------------------------*/	
	
	public function getInformations() {
		
		$query = "SELECT information_id FROM " . DB_PREFIX . "information WHERE status = '1'";
		
		$query_m = "SELECT i.information_id FROM " . DB_PREFIX . "information i INNER JOIN " . DB_PREFIX . "information_to_store i2s ON (i.information_id = i2s.information_id) WHERE i2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND i.status = '1'";
		
		if ($this->isMulti()) $query = $query_m;
		
		$query = $this->db->query($query);	
		
		return $query->rows;
	}
	
	public function getInformationsTotal() {
		
		$query = "SELECT COUNT(information_id) AS total FROM " . DB_PREFIX . "information WHERE status = '1'";
		
		$query_m = "SELECT COUNT(i.information_id) FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_to_store i2s ON (i.information_id = i2s.information_id) WHERE i2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND i.status = '1'";
		
		if ($this->isMulti()) $query = $query_m;
		
		$query = $this->db->query($query);	
		
		return $query->row['total'];
	}
	
	public function getInformationsExpress($data = array()) {
		
		if((float)VERSION >= 3) return $this->getInformationsExpress3($data);

		$query = "SELECT u.keyword, i.information_id FROM " . DB_PREFIX . "information i INNER JOIN " . DB_PREFIX . "url_alias u ON (u.query = CONCAT('information_id=', i.information_id)) WHERE i.status = '1'";
		
		$query_m = "SELECT u.keyword, i.information_id FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "url_alias u ON (u.query = CONCAT('information_id=', i.information_id)) LEFT JOIN " . DB_PREFIX . "information_to_store i2s ON (i.information_id = i2s.information_id) WHERE i2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND i.status = '1'";
		
		if ($this->isMulti()) $query = $query_m;
		
		$query = $this->db->query($query);
	
		return $query->rows;
	}
	
	public function getInformationsExpress3($data = array()) {
		
		$query = "SELECT u.keyword, i.information_id 
			FROM " . DB_PREFIX . "information i
			INNER JOIN " . DB_PREFIX . "seo_url u ON (u.query = CONCAT('information_id=', i.information_id))			
			WHERE i.status = '1'";
		
		if ($this->isMulti()) $query .= " AND u.store_id = '" . (int)$this->config->get('config_store_id');
		
		if ($this->language_id) $query .= " AND u.language_id = '" . $this->language_id;
		
		$query = $this->db->query($query);
	
		return $query->rows;
	}
		
/*-------------------------------------------------------*/	
    
	public function getCMSBlog($data = array()) {
		$test = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "record'");
		if($test->num_rows < 1) {return Array();}
		
		
		$test = $this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . "record LIKE 'index_page'");
		$status = $test->num_rows < 1 ? "" : "  AND index_page NOT LIKE '%noindex%'";

		$query = $this->db->query("SELECT record_id, date_modified FROM " . DB_PREFIX . "record WHERE status = '1'" . $status);
	
		return $query->rows;
	}	
	
	public function getCMSBlogTotal($data = array()) {
		$test = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "record'");
		if($test->num_rows < 1) {return 0;}
		
		$test = $this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . "record LIKE 'index_page'");
		$status = $test->num_rows < 1 ? "" : "  AND index_page NOT LIKE '%noindex%'";

		$query = $this->db->query("SELECT COUNT(record_id) AS total FROM " . DB_PREFIX . "record WHERE status = '1'" . $status);
	
		return $query->row['total'];
	}
/*-------------------------------------------------------*/	
    
	public function getArticles($data = array()) {
		$test = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "article'");
		if($test->num_rows < 1) {return Array();}

		$query = $this->db->query("SELECT article_id FROM " . DB_PREFIX . "article WHERE status = '1'");
	
		return $query->rows;
	}	
	
	public function getArticlesTotal($data = array()) {
		$test = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "article'");
		if($test->num_rows < 1) {return 0;}

		$query = $this->db->query("SELECT COUNT(article_id) AS total FROM " . DB_PREFIX . "article WHERE status = '1'");
	
		return $query->row['total'];
	}
/*-------------------------------------------------------*/	
    
	public function getFilterPro($data = array()) {
		$test = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "filterpro_seo'");
		if($test->num_rows < 1) {return Array();}

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "filterpro_seo");
	
		return $query->rows;
	}	
	
	public function getFilterProTotal($data = array()) {
		$test = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "filterpro_seo'");
		if($test->num_rows < 1) {return 0;}

		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "filterpro_seo");
	
		return $query->row['total'];
	}
/*-------------------------------------------------------*/	
    
	public function getImgOne($data = array()) {
		$query = $this->db->query(
		"SELECT p.product_id, p.date_added, p.date_modified, pd.name
		FROM " . DB_PREFIX . "product p
		LEFT JOIN " . DB_PREFIX . "product_description pd ON ( pd.product_id = p.product_id )
		WHERE p.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'
		LIMIT ".(int)$data['start'].", ".(int)$data['limit']);
		
		return $query->rows;
	}
	
	public function getImg($data = array()) {
		$query = $this->db->query(
		"SELECT p.product_id, p.date_added, p.date_modified, pd.name, CONCAT_WS( ',', p.image, GROUP_CONCAT( DISTINCT pi.image ) ) AS image
		FROM " . DB_PREFIX . "product p
		LEFT JOIN " . DB_PREFIX . "product_image pi ON ( pi.product_id = p.product_id ) 
		LEFT JOIN " . DB_PREFIX . "product_description pd ON ( pd.product_id = p.product_id ) 
		WHERE p.status =  '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'
		GROUP BY p.product_id
		LIMIT ".(int)$data['start'].", ".(int)$data['limit']);
	
		return $query->rows;
	}	
	
	public function getImgTotal($data = array()) {
		$query = $this->db->query("SELECT COUNT(product_id) AS total FROM " . DB_PREFIX . "product_image GROUP BY product_id");
	
		return $query->row['total'];
	}
}
?>