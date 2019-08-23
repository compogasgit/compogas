<?php
class ControllerExtensionModuleFramethemeFTLogo extends Controller {
	public function index() {
		$this->load->model('setting/setting');

		$ft_settings = array();
		$ft_settings = $this->model_setting_setting->getSetting('theme_frame', $this->config->get('config_store_id'));
		$language_id = $this->config->get('config_language_id');

		if (isset($ft_settings['t1_logo']) && $ft_settings['t1_logo']){
			$logo = $ft_settings['t1_logo'];
		} else {
			$logo = false;
		}

		if (isset($ft_settings['t1_logo_width']) && $ft_settings['t1_logo_width']){
			$width = $ft_settings['t1_logo_width'];
		} else {
			$width = 0;
		}

		if (isset($ft_settings['t1_logo_height']) && $ft_settings['t1_logo_height']){
			$height = $ft_settings['t1_logo_height'];
		} else {
			$height = 0;
		}

		if (isset($ft_settings['t1_svg_logo_status']) && $ft_settings['t1_svg_logo_status']){
			$data['svg_logo_status'] = $ft_settings['t1_svg_logo_status'];
		} else {
			$data['svg_logo_status'] = false;
		}

		if (isset($ft_settings['t1_svg_logo']) && $ft_settings['t1_svg_logo']){
			$data['svg_logo'] = html_entity_decode($ft_settings['t1_svg_logo'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');
		} else {
			$data['svg_logo'] = '';
		}

		if (isset($this->request->get['route'])) {
			$route = $this->request->get['route'];
		} else {
			$route = 'common/home';
		}

		if ($route == 'common/home') {
			$data['on_home'] = true;
		} else {
			$data['on_home'] = false;
		}


		$this->load->model('tool/image');

		$data['logo'] = $logo;
		$data['name'] = $this->config->get('config_name');
		$data['home'] = $this->url->link('common/home');

		$data['logo1x'] = $this->model_tool_image->resize($logo, $width, $height);
		$data['logo2x'] = $this->model_tool_image->resize($logo, $width*2, $height*2);
		$data['logo3x'] = $this->model_tool_image->resize($logo, $width*3, $height*3);
		$data['logo4x'] = $this->model_tool_image->resize($logo, $width*4, $height*4);


		return $this->load->view('extension/module/frametheme/ft_logo', $data);
	}
}
