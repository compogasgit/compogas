<?php
class ControllerExtensionModuleXDSCustomBlocks extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/xds_custom_blocks');

		$this->load->model('tool/image');
		
		$language_id = $this->config->get('config_language_id');
		
		$data['columns'] = $setting['columns'];
		
		$results = $setting['cust_blocks_item'];
		
		foreach ($results as $result) {
			$data['blocks'][] = array(
				'image' => $this->model_tool_image->resize($result['image'], 50, 50),
				'image2x' => $this->model_tool_image->resize($result['image'], 50*2, 50*2),
				'image3x' => $this->model_tool_image->resize($result['image'], 50*3, 50*3),
				'image4x' => $this->model_tool_image->resize($result['image'], 50*4, 50*4),
				'title' => $result['title'][$language_id],
				'description' => $result['description'][$language_id],
				'link'  => $result['link'][$language_id],
				'html'  => html_entity_decode($result['html'], ENT_QUOTES, 'UTF-8'),
				'sort'  => $result['sort']
			);	
		}
		
		if (!empty($data['blocks'])){
			foreach ($data['blocks'] as $key => $value) {
				$sort[$key] = $value['sort'];
			} 
			array_multisort($sort, SORT_ASC, $data['blocks']);
		}

		return $this->load->view('extension/module/xds_custom_blocks', $data);
	}
}