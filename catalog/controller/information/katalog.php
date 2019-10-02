<?php
class ControllerInformationKatalog extends Controller {
	public function index() {
		$this->load->language('information/katalog');
		$data['heading_title'] = $this->language->get('heading_title');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->setDescription('В интернет-магазине Compogas.ru представлен большой ассортимент композитных и стальных баллонов и сопутствующего оборудования. Выгодные цены. Оптовые и розничные продажи.');
		$this->document->setKeywords('Каталог, газовые баллоны, стальные баллоны, композитный баллоны, комплектующие, цены, купить');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('information/katalog')
		);

		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');

		$data['categories'] = array();
		$categories = array();

		// Получаем список всех категорий
		// $categories = $this->model_catalog_category->getCategories(0);

		// Список категорий для вывода
		$categories_number = [133,79,100,99,101,102,148,145,146,144,62,147,132,80,87,88,92,93,95,96,98,116,82,70,65,124,143,90,119];

		// Цикл для получения информации по категриям из списка
		foreach ($categories_number as $cat) {
			// Получаем данные по одной категории
			$category_temp = $this->model_catalog_category->getCategory($cat);

			// Если категория существует добавляем в массив
			(!empty($category_temp)) ? $categories[] = $category_temp : false;
		}

		foreach ($categories as $category) {

			if ($category['image']) {
				$image = $this->model_tool_image->resize($category['image'], 300, 300);
			} else {
				$image = $this->model_tool_image->resize('placeholder.png', 300, 300);
			}

			if ( !empty($category['meta_h1'])) {
				$name = $category['meta_h1'];
			} else {
				$name = $category['name'] ?? '';
			}

			$data['categories'][] = array(
				'name'     => $name,
				'image' => $image,
				'href'     => $this->url->link('product/category', 'path=' . $category['category_id'])
			);
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('information/katalog', $data));
	}
}