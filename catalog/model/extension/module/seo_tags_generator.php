<?php

/**
 * @category   OpenCart
 * @package    SEO Tags Generator
 * @copyright  © Serge Tkach, 2017, http://sergetkach.com/
 */

class ModelExtensionModuleSeoTagsGenerator extends Controller {


	/* Common Helpers for admin & catalog
	--------------------------------------------------------------------------- */

	public function getSTGSettingsByCatId($category_id) {
		if (!$category_id) return false;

		// Запрашиваем формулы текущей категории
		$sql = "SELECT `setting` FROM `" . DB_PREFIX . "seo_tags_generator_category_setting` WHERE `category_id` = '" . (int)$category_id . "'";

		$result = $this->db->query($sql);

		if ($result) {
			if (isset($result->row['setting']) && !empty($result->row['setting'])) {
				// Для текущей категории, у которой заданы формулы просто возращаем эти формулы
				return json_decode($result->row['setting'], true);
			}
		}

		// Если настроек для текущей категории нет, то скрипт продолжается
		$limit = 5; // Лимит, на случай, если не будет настроек и для родителей
		$i		 = 1;

		$stop = false;

		$previous_category = $category_id;

		while ($limit >= $i && !$stop) {
			// Проверяем, есть ли у данной категории родитель
			$follow_category_id = $this->getParentCategoryByCategoryId($previous_category);

			if ($follow_category_id == $previous_category) {
				$stop = true;
			}

			if (!$stop) {
				// Родитель есть, запрашиваем его настройки
				$sql = "SELECT `setting` FROM `" . DB_PREFIX . "seo_tags_generator_category_setting` WHERE `category_id` = '" . (int)$follow_category_id . "'";

				$result = $this->db->query($sql);

				if ($result) {
					if (isset($result->row['setting']) && !empty($result->row['setting'])) {
						$stop = true;
						// Возращаем раннее полученные формулы
						return json_decode($result->row['setting'], true);
					}
				}
			}

			$previous_category = $follow_category_id;
			$i++;
		}

		return false;
	}

	public function getSTGFormulasByCatId($category_id, $language_id, $key) {
		// Сначала нам нужно понять, есть ли у этой категории свои формулы
		// Если формулы есть - выдаем их
		// Если формул нет нет - то нам теперь нужно отталкиваться настройки наследования
		// Настройка наследования есть:
		//  - глобальная в модулей
		//  - локальная в отдельной взятой категории
		// В любом случае, перед тем, как понять, есть ли формулы и настройки нам все равно нужно выяснить крайнего родителя...
		// Если у категории ( крайнего родителя ) нет прописанных формул, значит настройка наследования для нее не применяются - не будем же мы наследовать пустоту...
		// Отталкиваемся от глобальной формулы
		// Если у категории ( крайнего родителя ) есть формулы, то отталкиваемся от настройки наследования данной формулы
		// Запрашиваем первого родителя
		// Если родитель есть, но формул для него нет, запрашиваем прародителя и т.д.

		if (!$category_id)
			return false;
		if (!$language_id)
			return false;
		if (!$key)
			return false;

		// Запрашиваем формулы текущей категории
		$sql = "SELECT `value` FROM `" . DB_PREFIX . "seo_tags_generator` WHERE `category_id`=" . (int)$category_id . " AND `language_id`='" . (int)$language_id . "' AND `key`='" . $this->db->escape($key) . "';";

		$result = $this->db->query($sql);

		if ($result) {
			if (isset($result->row['value']) && !empty($result->row['value'])) {
				// Для текущей категории, у которой заданы формулы просто возращаем эти формулы
				return json_decode($result->row['value'], true);
			}
		}

		// Если формулы для текущей категории нет, то скрипт продолжается

		$limit = 5; // Лимит, на случай, если не будет настроек и для родителей
		$i		 = 1;

		$stop = false;

		$previous_category = $category_id;

		while ($limit >= $i && !$stop) {
			// Проверяем, есть ли у данной категории родитель
			$follow_category_id = $this->getParentCategoryByCategoryId($previous_category);

			if ($follow_category_id == $previous_category) {
				$stop = true;
			}

			if (!$stop) {
				// Родитель есть, запрашиваем его формулы
				$sql = "SELECT `value` FROM `" . DB_PREFIX . "seo_tags_generator` WHERE `category_id`=" . (int)$follow_category_id . " AND `language_id`='" . (int)$language_id . "' AND `key`='" . $this->db->escape($key) . "';";

				$result = $this->db->query($sql);

				if ($result) {
					if (isset($result->row['value']) && !empty($result->row['value'])) {
						// Хотя все равно выполнение прекратится после возрата...
						$stop = true;

						// Когда формула для текущей категории есть, то нас волнует настройка наследования
						$sql = "SELECT `setting` FROM `" . DB_PREFIX . "seo_tags_generator_category_setting` WHERE `category_id` = '" . (int)$follow_category_id . "'";
						$query2	= $this->db->query($sql);

						if ($query2->row) {
							$setting = json_decode($query2->row['setting'], true);
							if ($setting['inheritance']) {
								// Возращаем раннее полученные формулы
								return json_decode($result->row['value'], true);
							}
						}
					}
				}
			}

			$previous_category = $follow_category_id;
			$i++;
		}

		return false;
	}


	public function getProductSales($product_id) {
		$sql = "SELECT COUNT(*) AS number FROM " . DB_PREFIX . "order_product WHERE product_id = '" . (int)$product_id . "' ";

		$query = $this->db->query($sql);

		return $query->row['number'];
	}


	public function getMinPriceInCat($category_id) {
		// Внимание!
		// Ищем товары данной категории и всех ее дочерних категорий!

		$res = false;

		// Самая дешевая базовая цена
		$sql = "SELECT"
			. " p2c.product_id,"
			. " p.price,"
			. " p.tax_class_id"
			. " FROM " . DB_PREFIX . "product_to_category p2c"
			. " LEFT JOIN " . DB_PREFIX . "product p ON (p.product_id = p2c.product_id)"
			. " WHERE p2c.category_id IN (SELECT cp.category_id FROM " . DB_PREFIX . "category_path cp WHERE path_id = '" . (int)$category_id . "')"
			. " AND p.price > 0" // чтобы не отображать товары с ценой 0, как делают магазины, которые наполняются...
			. " ORDER BY p.price ASC"
			. " LIMIT 1";

		$query = $this->db->query($sql);

		$a_min_price = array();

		if (isset($query->row['price'])) {
			$res = $query->row['price'];

			$a_min_price['product_id'] = $query->row['product_id'];
			$a_min_price['price'] = $query->row['price'];

			$tax_class_id	= $query->row['tax_class_id'];
		} else {
			// all prices are 0...
			$a_min_price['product_id'] = false;
			$a_min_price['price'] = false;
		}

		// Самая дешевая цена discount
		$sql = "SELECT"
			. " p2c.product_id,"
			. " pd2.price AS discount,"
			. " p.tax_class_id"
			. " FROM " . DB_PREFIX . "product_to_category p2c"
			. " LEFT JOIN " . DB_PREFIX . "product p ON (p.product_id = p2c.product_id)"
			. " LEFT JOIN  " . DB_PREFIX . "product_discount pd2 ON (pd2.product_id = p2c.product_id)"
			. " WHERE p2c.category_id IN (SELECT cp.category_id FROM " . DB_PREFIX . "category_path cp WHERE path_id = '" . (int)$category_id . "')"
			. " AND pd2.price > '0' AND pd2.quantity = '1' AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((pd2.date_start = '' OR pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '' OR pd2.date_end = '0000-00-00' OR pd2.date_end > NOW()))"
			. " ORDER BY pd2.price ASC, pd2.priority ASC"
			. " LIMIT 1";

		$query = $this->db->query($sql);

		// Discount - замещает базовую цену по стандарту OpenCart
		if (isset($query->row['discount'])) {
			if ((float)$query->row['discount'] < (float)$a_min_price['price'] || false === $a_min_price['price']) {
				$res = $query->row['discount'];

				$a_min_price['product_id'] = $query->row['product_id'];
				$a_min_price['price'] = $query->row['discount'];

				$tax_class_id	= $query->row['tax_class_id'];
			}
		}

		// Самая дешевая цена special
		$sql = "SELECT"
			. " p2c.product_id,"
			. " ps.price AS special,"
			. " p.tax_class_id"
			. " FROM " . DB_PREFIX . "product_to_category p2c"
			. " LEFT JOIN " . DB_PREFIX . "product p ON (p.product_id = p2c.product_id)"
			. " LEFT JOIN  " . DB_PREFIX . "product_special ps ON (ps.product_id = p2c.product_id)"
			. " WHERE p2c.category_id IN (SELECT cp.category_id FROM " . DB_PREFIX . "category_path cp WHERE path_id = '" . (int)$category_id . "')"
			. " AND ps.price > '0' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '' OR ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '' OR ps.date_end = '0000-00-00' OR ps.date_end > NOW()))"
			. " ORDER BY ps.price ASC, ps.priority ASC"
			. " LIMIT 1";

		$query = $this->db->query($sql);

		if (isset($query->row['special'])) {
			// Сравнимаем минимальную цену special с минимальной ценой (price:discount)
			if ($query->row['special'] < $a_min_price['price'] || (false === $a_min_price['price'] && $query->row['special'] > $a_min_price['price'])) {
				$res = $query->row['special'];

				// Если это разные товары, то нам нужен tax_class_id более дешевого товара со скидкой
				if ($query->row['product_id'] != $a_min_price['product_id']) {
					$tax_class_id = $query->row['tax_class_id'];
				}
			}
		}

		if ($res) {
			$res = $this->currency->format($this->tax->calculate($res, $tax_class_id, $this->config->get('config_tax')), $this->session->data['currency']);
		}

		return $res;
	}


	public function getMaxPriceInCat($category_id) {
		// Внимание!
		// Ищем товары данной категории и всех ее дочерних категорий!

		$res = false;

		// Самая дорогая базовая цена
		$sql = "SELECT"
			. " p2c.product_id,"
			. " p.price,"
			. " p.tax_class_id"
			. " FROM " . DB_PREFIX . "product_to_category p2c"
			. " LEFT JOIN " . DB_PREFIX . "product p ON (p.product_id = p2c.product_id)"
			. " WHERE p2c.category_id IN (SELECT cp.category_id FROM " . DB_PREFIX . "category_path cp WHERE path_id = '" . (int)$category_id . "')"
			. " ORDER BY p.price DESC"
			. " LIMIT 1";

		$query = $this->db->query($sql);

		$a_max_price = array();

		if (isset($query->row['price'])) {
			$res = $query->row['price'];

			$a_max_price['product_id'] = $query->row['product_id'];
			$a_max_price['price'] = $query->row['price'];

			$tax_class_id	= $query->row['tax_class_id'];
		} else {
			// all prices are 0...
			$a_min_price['product_id'] = false;
			$a_min_price['price'] = false;
		}

		// Самая дорогая цена discount
		$sql = "SELECT"
			. " p2c.product_id,"
			. " pd2.price AS discount,"
			. " p.tax_class_id"
			. " FROM " . DB_PREFIX . "product_to_category p2c"
			. " LEFT JOIN " . DB_PREFIX . "product p ON (p.product_id = p2c.product_id)"
			. " LEFT JOIN  " . DB_PREFIX . "product_discount pd2 ON (pd2.product_id = p2c.product_id)"
			. " WHERE p2c.category_id IN (SELECT cp.category_id FROM " . DB_PREFIX . "category_path cp WHERE path_id = '" . (int)$category_id . "')"
			. " AND pd2.price > '0' AND pd2.quantity = '1' AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((pd2.date_start = '' OR pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '' OR pd2.date_end = '0000-00-00' OR pd2.date_end > NOW()))"
			. " ORDER BY pd2.price DESC, pd2.priority ASC"
			. " LIMIT 1";

		$query = $this->db->query($sql);

		// Discount - замещает базовую цену по стандарту OpenCart
		if (isset($query->row['discount'])) {
			if ((float)$query->row['discount'] > (float)$a_max_price['price'] || false === $a_min_price['price']) {
				$res = $query->row['discount'];

				$a_max_price['product_id'] = $query->row['product_id'];
				$a_max_price['price'] = $query->row['discount'];

				$tax_class_id	= $query->row['tax_class_id'];
			}
		}

		// В принципе, это абсурдно, чтобы special был больше, чем базовая цена и цена дисконта
		/*
		// Самая дорогая цена special
		$sql = "SELECT"
			. " p2c.product_id,"
			. " ps.price AS special,"
			. " p.tax_class_id"
			. " FROM " . DB_PREFIX . "product_to_category p2c"
			. " LEFT JOIN " . DB_PREFIX . "product p ON (p.product_id = p2c.product_id)"
			. " LEFT JOIN  " . DB_PREFIX . "product_special ps ON (ps.product_id = p2c.product_id)"
			. " WHERE p2c.category_id IN ( (SELECT cp.category_id FROM " . DB_PREFIX . "category_path cp WHERE path_id = '" . (int)$category_id . "') )"
			. " AND ps.price > '0' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '' OR ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '' OR ps.date_end = '0000-00-00' OR ps.date_end > NOW()))"
			. " ORDER BY ps.price DESC, ps.priority ASC"
			. " LIMIT 1";

		$query = $this->db->query($sql);

		if (isset($query->row['special'])) {
			// Сравнимаем максимальную цену special с максимальной ценой (price:discount)
			if ($query->row['special'] > $a_max_price['price'] || (false === $a_min_price['price'] && $query->row['special'] < $a_min_price['price'])) {
				$res = $query->row['special'];

				// Если это разные товары, то нам нужен tax_class_id более дешевого товара со скидкой
				if ($query->row['product_id'] != $a_max_price['product_id']) {
					$tax_class_id = $query->row['tax_class_id'];
				}
			}
		}
		 */

		if ($res) {
			$res = $this->currency->format($this->tax->calculate($res, $tax_class_id, $this->config->get('config_tax')), $this->session->data['currency']);
		}

		return $res;
	}


	public function getCategoryLevel($category_id) {
		$sql = "SELECT level FROM " . DB_PREFIX . "category_path WHERE category_id = '" . (int)$category_id . "' AND path_id = '" . (int)$category_id . "'";

		$query = $this->db->query($sql);

		if ($query->row) {
			return $query->row['level'];
		}

		return false;
	}


	public function getCategoriesNestedNames($current_category_id, $lang_id) {
		// is different from admin getCategoryNested() without lang parameter ?? так ли это??
		$sql = "SELECT cp.*, cd.name FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category_description cd ON (cd.category_id = cp.path_id)WHERE cp.category_id = '" . (int)$current_category_id . "' AND cd.language_id = '" . (int)$lang_id . "' ORDER BY cp.level DESC";

		$query = $this->db->query($sql);

		if ($query->rows) {
			return $query->rows;
		}

		return false;
	}


	public function getCategoryDescriptionByIdAndLang($category_id, $lang_id) {
		// is different from admin getCategoryName() without lang parameter
		$sql	 = "SELECT * FROM  " . DB_PREFIX . "category_description WHERE`category_id` = '" . (int)$category_id . "' AND language_id = '" . (int)$lang_id . "'";
		$query = $this->db->query($sql);

		if ($query->row) {
			$result = array();

			$result['name'] = $query->row['name'];

			if (isset($query->row['h1'])) {
				$result['h1'] = $query->row['h1'];
			} elseif (isset($query->row['meta_h1'])) {
				$result['h1'] = $query->row['meta_h1'];
			} else {
				$result['h1'] = '';
			}

			return $result;
		}

		return false;
	}

	public function getCategoryDeclension($category_id, $lang_id) {
		// is different from admin getCategoryDeclensionForEdit() without lang parameter
		$query = "SELECT "
			. "`category_name_plural_nominative`, "
			. "`category_name_plural_genitive`, "
			. "`category_name_singular_nominative` "
			. "FROM `" . DB_PREFIX . "seo_tags_generator_category_declension`"
			. "WHERE `category_id` = '" . (int)$category_id . "' AND `language_id` = '" . (int)$lang_id . "' ";

		$result = $this->db->query($query);

		if ($result->row) {
			// Проверяем падежи на пустоту
			if (empty($result->row['category_name_plural_nominative']) || empty($result->row['category_name_plural_genitive']) || empty($result->row['category_name_singular_nominative'])) {
				return false;
			}

			return $result->row;
		}

		return false;
	}


	public function getProductModelSynonym($product_id) {
		// model_synonym присутствует в $product_info, если установлен модификатор!
		if (!$this->existFieldModelSynonym()) {
			return false;
		}

		$query = "SELECT model_synonym FROM " . DB_PREFIX . "product WHERE product_id ='" . (int)$product_id . "'";
		$result = $this->db->query($query);

		if ($result->rows) {
			if ($result->row) {
				return $result->row['model_synonym'];
			}
		}

		return false;
	}


	public function existFieldModelSynonym() {
		$exist = false;

		$sql = "SHOW COLUMNS FROM " . DB_PREFIX . "product";
		$result = $this->db->query($sql);

		foreach ($result->rows as $field) {
			if ('model_synonym' == $field['Field']) {
				$exist = true;
				break;
			}
		}

		return $exist;
	}


	public function getManufacturerDescription($manufacturer_id) {
		// В зависимости от версии системы, искать синоним в разных таблицах
		$target_table = $this->cache->get('stg_manufacturer_target_table');

		if (!$target_table) {
			$target_table = $this->defineTargetTable();
		}

		$sql		 = "SELECT * FROM " . DB_PREFIX . $target_table . " WHERE manufacturer_id = '" . (int)$manufacturer_id . "'";
		$result	 = $this->db->query($sql);

		if ($result->num_rows) {
			return $result->row;
		}

		return false;
	}


	public function defineTargetTable() {
		$target_table = false;

		// ocStore - самая популярная система
		$sql		 = "SHOW TABLES FROM " . DB_DATABASE . " like '" . DB_PREFIX . "manufacturer_description'";
		$result	 = $this->db->query($sql);

		if ($result->num_rows > 0) {
			$target_table = 'manufacturer_description';
			$this->cache->set('stg_manufacturer_target_table', $target_table);
			return $target_table;
		} else {
			$target_table = 'manufacturer';
			$this->cache->set('stg_manufacturer_target_table', $target_table);
			return $target_table;
		}
	}


	public function getParentCategoryByCategoryId($category_id) {
		$sql = "SELECT parent_id FROM " . DB_PREFIX . "category WHERE category_id = '" . (int)$category_id . "'";

		$result = $this->db->query($sql);

		if ($result) {
			if ($result->row['parent_id']) {
				return $result->row['parent_id'];
			} else {
				return $category_id; // Если никуда не вложена
			}
		}

		return false;
	}


	public function getParentCategoryByProductId($product_id) {
		$category_id					 = false;
		$exist_main_cat_field	 = false;

		$sql		 = "SHOW COLUMNS FROM " . DB_PREFIX . "product_to_category;";
		$result	 = $this->db->query($sql);

		// Изначально в таблице 2 поля
		if ($result->num_rows > 2) {
			foreach ($result->rows as $field) {
				if ('main_category' == $field['Field']) {
					$exist_main_cat_field = true;
				}
			}
		}

		if (true === $exist_main_cat_field) {
			$sql		 = "SELECT category_id FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "' AND main_category = '1'";
			$result	 = $this->db->query($sql);

			if ($result->num_rows) {
				$category_id = (int)$result->row['category_id'];
			}
		}

		if (!$category_id) {
			$sql		 = "SELECT category_id FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'";
			$result	 = $this->db->query($sql);

			// Если только 1 родительская категория, то возвращаем ее
			if (1 == $result->num_rows) {
				$category_id = $result->row['category_id'];
			}

			if ($result->num_rows > 1) {
				// Эмпирически определяем главную категорию товара
				// Главнее та, что глубже уровня вложенности
				foreach ($result->rows as $item) {
					if ($item['category_id'] > $category_id) {
						$category_id = $item['category_id'];
					}
				}
			}
		}

		return $category_id;
	}


	public function notUseAutoCategory($id) {
		$res = $this->db->query("SELECT `id` FROM `" . DB_PREFIX . "seo_tags_generator_not_use` WHERE `id` = '" . (int)$id . "' AND `essence_id` = '2'");
		if ($res) {
			if (0 < $res->num_rows) {
				return true;
			}
		}
		return false;
	}


	public function notUseAutoProduct($id) {
		$res = $this->db->query("SELECT `id` FROM `" . DB_PREFIX . "seo_tags_generator_not_use` WHERE `id` = '" . (int)$id . "' AND `essence_id` = '1'");
		if ($res) {
			if (0 < $res->num_rows) {
				return true;
			}
		}
		return false;
	}


}
