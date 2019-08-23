<?php
class ControllerExtensionModuleFramethemeFTFooter extends Controller {
	public function index() {
		$this->load->language('extension/module/frametheme/ft_global');
		$this->load->language('extension/module/frametheme/ft_footer');


		$this->load->model('setting/setting');

		$ft_settings = array();
		$ft_settings = $this->model_setting_setting->getSetting('theme_frame', $this->config->get('config_store_id'));
		$language_id = $this->config->get('config_language_id');

		if (isset($ft_settings['t1_pay_icons_toggle']) && $ft_settings['t1_pay_icons_toggle']){
			$data['pay_icons_status'] = $ft_settings['t1_pay_icons_toggle'];
		} else {
			$data['pay_icons_status'] = false;
		}

		if (isset($ft_settings['t1_pay_icons_banner_id']) && $ft_settings['t1_pay_icons_banner_id']){
			$banner_id = $ft_settings['t1_pay_icons_banner_id'];
		} else {
			$banner_id = false;
		}


		$this->load->model('catalog/information');

		$data['informations'] = array();

		foreach ($this->model_catalog_information->getInformations() as $result) {
			if ($result['bottom']) {
				$data['informations'][] = array(
					'title' => $result['title'],
					'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
				);
			}
		}

		$data['contact'] = $this->url->link('information/contact');
		$data['return'] = $this->url->link('account/return/add', '', true);
		$data['sitemap'] = $this->url->link('information/sitemap');
		$data['tracking'] = $this->url->link('information/tracking');
		$data['manufacturer'] = $this->url->link('product/manufacturer');
		$data['voucher'] = $this->url->link('account/voucher', '', true);
		$data['affiliate'] = $this->url->link('affiliate/login', '', true);
		$data['special'] = $this->url->link('product/special');
		$data['account'] = $this->url->link('account/account', '', true);
		$data['order'] = $this->url->link('account/order', '', true);
		$data['wishlist'] = $this->url->link('account/wishlist', '', true);
		$data['newsletter'] = $this->url->link('account/newsletter', '', true);

		$data['powered'] = sprintf($this->language->get('text_powered'), $this->config->get('config_name'), date('Y', time())).$this->language->get('g_theme_powered');

		$this->load->model('design/banner');
		$this->load->model('tool/image');

		$data['pay_icons'] = array();
		$pay_icons = $this->model_design_banner->getBanner($banner_id);

		foreach ($pay_icons as $pay_icon) {
			if (is_file(DIR_IMAGE . $pay_icon['image'])) {
				$data['pay_icons'][] = array(
					'title' 		=> $pay_icon['title'],
					'link'  		=> $pay_icon['link'],
					'image' 		=> $this->model_tool_image->resize($pay_icon['image'], 48, 30),
					'image2x' 	=> $this->model_tool_image->resize($pay_icon['image'], 48*2, 30*2),
					'image3x' 	=> $this->model_tool_image->resize($pay_icon['image'], 48*3, 30*3),
					'image4x' 	=> $this->model_tool_image->resize($pay_icon['image'], 48*4, 30*4)
				);
			}
		}

		return $this->load->view('extension/module/frametheme/ft_footer', $data);
	}
}
