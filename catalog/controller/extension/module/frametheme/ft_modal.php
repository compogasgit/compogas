<?php
class ControllerExtensionModuleFramethemeFTModal extends Controller {
	public function index() {
		$this->load->model('setting/setting');
			
		$ft_settings = array();
		$ft_settings = $this->model_setting_setting->getSetting('theme_frame', $this->config->get('config_store_id'));
		$language_id = $this->config->get('config_language_id');

		if (isset($ft_settings['t1_modal_status']) && $ft_settings['t1_modal_status']){
			$data['status'] = $ft_settings['t1_modal_status'];
		} else {
			$data['status'] = false;
		}
		
		if (isset($ft_settings['t1_modal_cookie_days']) && $ft_settings['t1_modal_cookie_days']){
			$data['cookie_days'] = $ft_settings['t1_modal_cookie_days'];
		} else {
			$data['cookie_days'] = false;
		}
		
		if (isset($ft_settings['t1_modal_size']) && $ft_settings['t1_modal_size']){
			$data['size'] = $ft_settings['t1_modal_size'];
		} else {
			$data['size'] = false;
		}
		
		if (isset($ft_settings['t1_modal_heading']) && $ft_settings['t1_modal_heading']){
			$data['heading'] = html_entity_decode($ft_settings['t1_modal_heading'][$language_id], ENT_QUOTES, 'UTF-8');
		} else {
			$data['heading'] = false;
		}
		
		if (isset($ft_settings['t1_modal_content']) && $ft_settings['t1_modal_content']){
			$data['content'] = html_entity_decode($ft_settings['t1_modal_content'][$language_id], ENT_QUOTES, 'UTF-8');
		} else {
			$data['content'] = false;
		}

		return $this->load->view('extension/module/frametheme/ft_modal', $data);
	}
}