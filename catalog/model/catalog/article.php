<?php
class ModelCatalogArticle extends Model {
	public function getArticle($article_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "xl_article a LEFT JOIN " . DB_PREFIX . "xl_article_description ad ON (a.article_id = ad.article_id) LEFT JOIN " . DB_PREFIX . "xl_article_to_store a2s ON (a.article_id = a2s.article_id) WHERE a.article_id = '" . (int)$article_id . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND a2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND a.status > '0'");

		return $query->row;
	}

	public function getArticles($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "xl_article a LEFT JOIN " . DB_PREFIX . "xl_article_description ad ON (a.article_id = ad.article_id) LEFT JOIN " . DB_PREFIX . "xl_article_to_store a2s ON (a.article_id = a2s.article_id) WHERE a.parent_id = '" . (int)$parent_id . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND a2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND a.status = '1' ORDER BY a.sort_order, LCASE(ad.name)");

		return $query->rows;
	}

	public function getCategories($data = array()) {

		if ($this->config->get('module_article_show_tree') == 1) {

		$sql = "SELECT * FROM " . DB_PREFIX . "xl_article a LEFT JOIN " . DB_PREFIX . "xl_article_description ad ON (a.article_id = ad.article_id) LEFT JOIN " . DB_PREFIX . "xl_article_to_store a2s ON (a.article_id = a2s.article_id) WHERE EXISTS (SELECT parent_id FROM " . DB_PREFIX . "xl_article b WHERE b.parent_id = a.article_id) AND a.parent_id = '" . (int)$data['parent_id'] . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND a2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND a.status = '1' ORDER BY a.sort_order, LCASE(ad.name)";

		} else {

		$sql = "SELECT * FROM " . DB_PREFIX . "xl_article a LEFT JOIN " . DB_PREFIX . "xl_article_description ad ON (a.article_id = ad.article_id) LEFT JOIN " . DB_PREFIX . "xl_article_to_store a2s ON (a.article_id = a2s.article_id) WHERE a.parent_id = '" . (int)$data['parent_id'] . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND a2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND a.status = '1' ORDER BY a.sort_order, LCASE(ad.name)";

		}

		if (isset($data['start']) && isset($data['limit'])) {
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getArticlesNoCategory($data = array()) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "xl_article a LEFT JOIN " . DB_PREFIX . "xl_article_description ad ON (a.article_id = ad.article_id) LEFT JOIN " . DB_PREFIX . "xl_article_to_store a2s ON (a.article_id = a2s.article_id) WHERE a.parent_id = '0' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND a2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND a.status = '1' ORDER BY a.sort_order, a.date_added DESC, LCASE(ad.name) LIMIT " . (int)$data['start'] . "," . (int)$data['limit']);

			return $query->rows;
	}

	public function getArticlesList($data = array()) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "xl_article a LEFT JOIN " . DB_PREFIX . "xl_article_description ad ON (a.article_id = ad.article_id) LEFT JOIN " . DB_PREFIX . "xl_article_to_store a2s ON (a.article_id = a2s.article_id) WHERE NOT EXISTS (SELECT parent_id FROM " . DB_PREFIX . "xl_article b WHERE b.parent_id = a.article_id) AND a.parent_id = '" . (int)$data['article_id'] . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND a2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND a.status = '1' ORDER BY a.sort_order, a.date_added DESC, LCASE(ad.name) LIMIT " . (int)$data['start'] . "," . (int)$data['limit']);

		return $query->rows;
	}

	public function getLatestArticles($data = array()) {
		if ($data['parent_id'] == '0') {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "xl_article a LEFT JOIN " . DB_PREFIX . "xl_article_description ad ON (a.article_id = ad.article_id) LEFT JOIN " . DB_PREFIX . "xl_article_to_store a2s ON (a.article_id = a2s.article_id) WHERE NOT EXISTS (SELECT parent_id FROM " . DB_PREFIX . "xl_article b WHERE b.parent_id = a.article_id) AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND a2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND a.status = '1' ORDER BY a.date_added DESC LIMIT " . (int)$data['start'] . "," . (int)$data['limit']);
		} else {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "xl_article a LEFT JOIN " . DB_PREFIX . "xl_article_description ad ON (a.article_id = ad.article_id) LEFT JOIN " . DB_PREFIX . "xl_article_to_store a2s ON (a.article_id = a2s.article_id) WHERE NOT EXISTS (SELECT parent_id FROM " . DB_PREFIX . "xl_article b WHERE b.parent_id = a.article_id) AND a.parent_id = '" . (int)$data['parent_id'] . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND a2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND a.status = '1' ORDER BY a.date_added DESC LIMIT " . (int)$data['start'] . "," . (int)$data['limit']);	
		}
				
		if ($query->num_rows) {
			return $query->rows;
		}

	}

	public function getArticlesToCategory($data = array()) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "xl_article a LEFT JOIN " . DB_PREFIX . "xl_article_description ad ON (a.article_id = ad.article_id) LEFT JOIN " . DB_PREFIX . "xl_article_to_category a2c ON (a.parent_id = a2c.article_id) LEFT JOIN " . DB_PREFIX . "xl_article_to_store a2s ON (a.article_id = a2s.article_id) WHERE NOT EXISTS (SELECT parent_id FROM " . DB_PREFIX . "xl_article b WHERE b.parent_id = a.article_id) AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND a2c.category_id = '" . (int)$data['category_id'] . "' AND a2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND a.status = '1' ORDER BY a.date_added DESC LIMIT " . (int)$data['start'] . "," . (int)$data['limit']);
				
		if ($query->num_rows) {
			return $query->rows;
		}
	}

	public function getArticlesToProduct($data = array()) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "xl_article a LEFT JOIN " . DB_PREFIX . "xl_article_description ad ON (a.article_id = ad.article_id) LEFT JOIN " . DB_PREFIX . "xl_article_to_product a2p ON (a.parent_id = a2p.article_id) LEFT JOIN " . DB_PREFIX . "xl_article_to_store a2s ON (a.article_id = a2s.article_id) WHERE NOT EXISTS (SELECT parent_id FROM " . DB_PREFIX . "xl_article b WHERE b.parent_id = a.article_id) AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND a2p.product_id = '" . (int)$data['product_id'] . "' AND a2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND a.status = '1' ORDER BY a.date_added DESC LIMIT " . (int)$data['start'] . "," . (int)$data['limit']);
				
		if ($query->num_rows) {
			return $query->rows;
		}
	}

	public function getProductRelated($article_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "xl_article WHERE article_id = '" . (int)$article_id . "'");

		if ($query->row) {
			return unserialize($query->row['product']);
		} else {
			return false;	
		}
	}

	public function getSimilarArticles($article_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "xl_article WHERE article_id = '" . (int)$article_id . "'");

		if ($query->row) {
			return unserialize($query->row['similar_articles']);
		} else {
			return false;	
		}
	}

	public function getArticleLayoutId($article_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "xl_article_to_layout WHERE article_id = '" . (int)$article_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return 0;
		}
	}

	public function getTotalArticlesByArticleId($parent_id = 0) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "xl_article a LEFT JOIN " . DB_PREFIX . "xl_article_to_store a2s ON (a.article_id = a2s.article_id) WHERE NOT EXISTS (SELECT parent_id FROM " . DB_PREFIX . "xl_article b WHERE b.parent_id = a.article_id) AND a.parent_id = '" . (int)$parent_id . "' AND a2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND a.status = '1'");

		return $query->row['total'];
	}

	public function getTotalArticles() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "xl_article a LEFT JOIN " . DB_PREFIX . "xl_article_to_store a2s ON (a.article_id = a2s.article_id) WHERE NOT EXISTS (SELECT parent_id FROM " . DB_PREFIX . "xl_article b WHERE b.parent_id = a.article_id) AND a2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND a.status = '1'");

		return $query->row['total'];
	}

	public function getParentToCategory($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "xl_article_to_category WHERE category_id = '" . (int)$category_id . "'");

		if ($query->num_rows) {
			return $query->row['article_id'];
		} else {
			return 0;
		}
	}

	public function getParentToProduct($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "xl_article_to_product WHERE product_id = '" . (int)$product_id . "'");

		if ($query->num_rows) {
			return $query->row['article_id'];
		} else {
			return 0;
		}
	}

	public function updateViewed($article_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "xl_article SET viewed = (viewed + 1) WHERE article_id = '" . (int)$article_id . "'");
	}

	public function getFullPath($article_id) {
		$article_id = (int)$article_id;
		if ($article_id < 1) return false;

		static $path = null;
		if (!is_array($path)) {
			$path = $this->cache->get('article2.seopath');
			if (!is_array($path)) $path = array();
		}

		if (!isset($path[$article_id])) {
			$max_level = 10;

			$sql = "SELECT CONCAT_WS('_'";
			for ($i = $max_level-1; $i >= 0; --$i) {
				$sql .= ",t$i.article_id";
			}
			$sql .= ") AS path FROM " . DB_PREFIX . "xl_article t0";
			for ($i = 1; $i < $max_level; ++$i) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "xl_article t$i ON (t$i.article_id = t" . ($i-1) . ".parent_id)";
			}
			$sql .= " WHERE t0.article_id = '" . $article_id . "'";

			$query = $this->db->query($sql);

			$path[$article_id] = $query->num_rows ? $query->row['path'] : false;

			$this->cache->set('article2.seopath', $path);
		}

		return $path[$article_id];
	}
}
