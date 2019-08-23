<?php
class ControllerExtensionModuleFramethemeFTFmap extends Controller {
	public function index() {
		$this->load->language('extension/module/frametheme/ft_fmap');

		$this->load->model('setting/setting');

		$ft_settings = array();
		$ft_settings = $this->model_setting_setting->getSetting('theme_frame', $this->config->get('config_store_id'));
		$language_id = $this->config->get('config_language_id');

		if (isset($ft_settings['t1_footer_map_toggle']) && $ft_settings['t1_footer_map_toggle']){
			$data['fmap_status'] = $ft_settings['t1_footer_map_toggle'];
		} else {
			$data['fmap_status'] = '';
		}

		if (isset($ft_settings['t1_footer_map_brand']) && $ft_settings['t1_footer_map_brand']){
			$data['fmap_brand'] = $ft_settings['t1_footer_map_brand'];
		} else {
			$data['fmap_brand'] = 'google';
		}

		if (isset($ft_settings['t1_footer_map_apikey']) && $ft_settings['t1_footer_map_apikey']){
			$data['fmap_api_key'] = $ft_settings['t1_footer_map_apikey'];
		} else {
			$data['fmap_api_key'] = '';
		}

		if (isset($ft_settings['t1_footer_map_geocode']) && $ft_settings['t1_footer_map_geocode']){
			$data['geocode'] = $ft_settings['t1_footer_map_geocode'];
		} else {
			$data['geocode'] = $this->config->get('config_geocode');
		}

		if (isset($ft_settings['t1_footer_map_mobile_hide']) && $ft_settings['t1_footer_map_mobile_hide']){
			$data['mobile_hide'] = $ft_settings['t1_footer_map_mobile_hide'];
		} else {
			$data['mobile_hide'] = false;
		}

		$data['language'] = $this->language->get('code');

		$detect = new Mobile_Detect;

		$data['mobile'] = $detect->isMobile();
		$data['tablet'] = $detect->isTablet();

		return $this->load->view('extension/module/frametheme/ft_fmap', $data);
	}
}
