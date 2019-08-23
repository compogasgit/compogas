<?php
class ControllerExtensionModuleCategoryWall extends Controller {

    public function index() {
        $this->load->language('extension/module/category_wall');

        $data['heading_title'] = $this->language->get('heading_title');

        if (isset($this->request->get['path'])) {
            $parts = explode('_', (string) $this->request->get['path']);
        } else {
            $parts = array();
        }

        if (isset($parts[0])) {
            $data['category_id'] = $parts[0];
        } else {
            $data['category_id'] = 0;
        }

        if (isset($parts[1])) {
            $data['child_id'] = $parts[1];
        } else {
            $data['child_id'] = 0;
        }

        $this->load->model('catalog/category');
        $this->load->model('catalog/product');

        $data['categories'] = array();

        $categories = $this->model_catalog_category->getCategories(0);

        // echo '<pre>';
        // var_dump($categories);
        // echo '</pre>';
        // exit();

        $this->load->model('tool/image');

        foreach ($categories as $category) {
            $children_data = array();

            $children = $this->model_catalog_category->getCategories($category['category_id']);
                $count = 0;
                foreach ($children as $child) {
                    $count++;
                    if ($count > 4) continue;
					$filter_data = array(
						'filter_category_id'  => $child['category_id'],
						'filter_sub_category' => true
					);

					$children_data[] = array(
						'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
						'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
					);
				}

            if ($category['image']) {
                $image = $this->model_tool_image->resize($category['image'], 170, 170);
                // $image = $this->model_tool_image->resize($category['image'], $this->config->get($this->config->get('config_theme') . '_image_category_width'), $this->config->get($this->config->get('config_theme') . '_image_category_height'));
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', 170, 170);
                // $image = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_category_width'), $this->config->get($this->config->get('config_theme') . '_image_category_height'));
            }
            

            $data['categories'][] = array(
                'description' => $category['description'],
                'name' => $category['name'],
                'image' => $image,
                'children' => $children_data,
                'href' => $this->url->link('product/category', 'path=' . $category['category_id'])
                );
        }

        
        return $this->load->view('extension/module/category_wall', $data);
    }
}