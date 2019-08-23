<?php
class ControllerExtensionModuleFramethemeFTLinks extends Controller {
	public function index() {

		// $this->load->model('tool/image');
		
		$this->load->model('setting/setting');
			
		$ft_settings = array();
		$ft_settings = $this->model_setting_setting->getSetting('theme_frame', $this->config->get('config_store_id'));
		$language_id = $this->config->get('config_language_id');

		if (isset($ft_settings['t1_main_menu_toggle']) && $ft_settings['t1_main_menu_toggle']){
			$data['links_status'] = $ft_settings['t1_main_menu_toggle'];
		} else {
			$data['links_status'] = false;
		}
		
		if (isset($ft_settings['t1_main_menu_item']) && $ft_settings['t1_main_menu_item']){
			$links = $ft_settings['t1_main_menu_item'];
		} else {
			$links = array();
		}

		if (!empty($links)){
			foreach ($links as $key => $value) {
				$sorted_links[$key] = $value['sort'];
			} 
			array_multisort($sorted_links, SORT_ASC, $links);
		}
		
		$data['links'] = array();
		
		foreach ($links as $link) {
			$data['links'][] = array(
				'title'        => html_entity_decode($link['title'][$language_id], ENT_QUOTES, 'UTF-8'),
				'href'         => $link['link'][$language_id],
				'sort'         => $link['sort'],
			);
		}

		return $this->load->view('extension/module/frametheme/ft_links', $data);
	}
}
