<?php
class ControllerExtensionModuleFramethemeFTContacts extends Controller {
	public function index() {
		
		$this->load->language('extension/module/frametheme/ft_contacts');
		
		$data['theme_dir'] = $this->config->get('theme_frame_directory');
		
		$this->load->model('tool/image');
		
		$this->load->model('setting/setting');
			
		$ft_settings = array();
		$ft_settings = $this->model_setting_setting->getSetting('theme_frame', $this->config->get('config_store_id'));
		$language_id = $this->config->get('config_language_id');

		if (isset($ft_settings['t1_contact_main_toggle']) && $ft_settings['t1_contact_main_toggle']){
			$data['contacts_status'] = $ft_settings['t1_contact_main_toggle'];
		} else {
			$data['contacts_status'] = false;
		}
		
		$data['all_contacts_link'] = $this->url->link('information/contact', '', true);

		if (isset($ft_settings['t1_contact_main_phone'][$language_id]) && $ft_settings['t1_contact_main_phone'][$language_id]){
			$data['main_phone'] = $ft_settings['t1_contact_main_phone'][$language_id];
		} else {
			$data['main_phone'] = $this->config->get('config_telephone');
		}
		
		if (isset($ft_settings['t1_contact_main_phone_link'][$language_id]) && $ft_settings['t1_contact_main_phone_link'][$language_id]){
			$data['main_phone_href'] = $ft_settings['t1_contact_main_phone_link'][$language_id];
		} else {
			$data['main_phone_href'] = '';
		}
		
		if (isset($ft_settings['t1_contact_hint'][$language_id]) && $ft_settings['t1_contact_hint'][$language_id]){
			$data['main_hint'] = $ft_settings['t1_contact_hint'][$language_id];
		} else {
			$data['main_hint'] = '';
		}

		if (isset($ft_settings['t1_callback_form_toggle']) && $ft_settings['t1_callback_form_toggle']){
			$data['callback_status'] = $ft_settings['t1_callback_form_toggle'];
		} else {
			$data['callback_status'] = false;
		}

		if (isset($ft_settings['t1_contact_add_toggle']) && $ft_settings['t1_contact_add_toggle']){
			$data['additional_contacts_status'] = $ft_settings['t1_contact_add_toggle'];
		} else {
			$data['additional_contacts_status'] = false;
		}
		
		if (isset($ft_settings['t1_callback_form_button_text'][$language_id]) && $ft_settings['t1_callback_form_button_text'][$language_id]){
			$data['callback_button_text'] = $ft_settings['t1_callback_form_button_text'][$language_id];
		} else {
			$data['callback_button_text'] = $this->language->get('ft_callback_tab');
		}
		
		if (isset($ft_settings['t1_callback_hint'][$language_id]) && $ft_settings['t1_callback_hint'][$language_id]){
			$data['callback_hint'] = $ft_settings['t1_callback_hint'][$language_id];
		} else {
			$data['callback_hint'] = '';
		}
		
		if (isset($ft_settings['t1_callback_phone_mask']) && $ft_settings['t1_callback_phone_mask']){
			$data['phone_mask'] = $ft_settings['t1_callback_phone_mask'];
		} else {
			$data['phone_mask'] = '';
		}
		
		
		if (isset($this->request->post['customer_name'])) {
			$data['customer_name'] = $this->request->post['customer_name'];
		} else {
			$data['customer_name'] = $this->customer->getFirstName();
		}
		
		if (isset($this->request->post['customer_telephone'])) {
			$data['customer_telephone'] = $this->request->post['customer_telephone'];
		} else {
			$data['customer_telephone'] = $this->customer->getTelephone();
		}

		if (isset($ft_settings['t1_additional_number']) && !empty($ft_settings['t1_additional_number'])) {
			$additional_phones = $ft_settings['t1_additional_number'];
		} else {
			$additional_phones = array();
		}

		$data['other_contacts_show'] = false;

		if (isset($ft_settings['t1_additional_contact']) && !empty($ft_settings['t1_additional_contact'])) {
			$other_contacts = $ft_settings['t1_additional_contact'];
		} else {
			$other_contacts = array();
		}

		if (!empty($additional_phones)){
			foreach ($additional_phones as $key => $value) {
				$additional_phones_sorted[$key] = $value['sort'];
			} 
			array_multisort($additional_phones_sorted, SORT_ASC, $additional_phones);
		}

		foreach ($additional_phones as $phone) {
			$data['additional_phones'][] = array(
				'title'        => $phone['title'][$language_id],
				'href'         => $phone['link'][$language_id],
				'hint_text'    => $phone['hint'][$language_id],
				'hint_show'    => false,
				'image' 		   => $this->model_tool_image->resize($phone['image'], 24*1, 24*1),
				'image2x' 	   => $this->model_tool_image->resize($phone['image'], 24*2, 24*2),
				'image3x' 	   => $this->model_tool_image->resize($phone['image'], 24*3, 24*3),
				'image4x' 	   => $this->model_tool_image->resize($phone['image'], 24*4, 24*4),
				'sort'         => $phone['sort'],
			);
		}

		if (!empty($other_contacts)){
			foreach ($other_contacts as $key => $value) {
				$other_contacts_sorted[$key] = $value['sort'];
			} 
			array_multisort($other_contacts_sorted, SORT_ASC, $other_contacts);
		}

		foreach ($other_contacts as $contact) {
			$data['other_contacts'][] = array(
				'title'        => $contact['title'][$language_id],
				'href'         => $contact['link'][$language_id],
				'target'       => false,
				'hint'         => $contact['hint'][$language_id],
				'image' 		   => $this->model_tool_image->resize($contact['image'], 16*1, 16*1),
				'image2x' 	   => $this->model_tool_image->resize($contact['image'], 16*2, 16*2),
				'image3x' 	   => $this->model_tool_image->resize($contact['image'], 16*3, 16*3),
				'image4x' 	   => $this->model_tool_image->resize($contact['image'], 16*4, 16*4),
				'sort'         => $contact['sort'],
			);
		}

		return $this->load->view('extension/module/frametheme/ft_contacts', $data);
	}
	
	private $error = array();
	
	public function callback() {
		
		$this->load->language('extension/module/frametheme/ft_contacts');

			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

				$name = $this->request->post['c_name'];
				$phone = $this->request->post['c_phone'];
				$comment = $this->request->post['c_comment'];
				
				
				
				$this->load->model('setting/setting');
			
				$ft_settings = array();
				$ft_settings = $this->model_setting_setting->getSetting('theme_frame', $this->config->get('config_store_id'));
				$language_id = $this->config->get('config_language_id');
				

				if (isset($ft_settings['t1_callback_mail']) && $ft_settings['t1_callback_mail']) {
					$recipient_mail = $ft_settings['t1_callback_mail'];
				} else {
					$recipient_mail = $this->config->get('config_email');
				}
				
				$sender = $this->config->get('config_name');
				
				$sender_mail = 'robot@' . preg_replace('#^www\.(.+)#i', '$1', $_SERVER['HTTP_HOST']);

				$subject = sprintf($this->language->get('ft_text_mail_subject'), $name);

				$message = sprintf($this->language->get('ft_text_mail_message'), $name, $phone);
				
				if ($comment) {
					$message .= sprintf($this->language->get('ft_text_message_comment'), $comment);
				}

				$mail = new Mail($this->config->get('config_mail_engine'));
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
				$mail->setTo($recipient_mail);
				$mail->setFrom($sender_mail);
				$mail->setReplyTo($recipient_mail);
				$mail->setSender(html_entity_decode($sender, ENT_QUOTES, 'UTF-8'));
				$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
				$mail->setHtml(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
				$mail->send();

				if (isset($ft_settings['t1_callback_form_success_text'][$language_id]) && $ft_settings['t1_callback_form_success_text'][$language_id]){
					$json['success'] = $ft_settings['t1_callback_form_success_text'][$language_id];
				} else {
					$json['success'] = $this->language->get('ft_text_success');
				}

			}
			
			if (isset($this->error['name'])) {
				$json['error']['name'] = $this->error['name'];
			}

			if (isset($this->error['phone'])) {
				$json['error']['phone'] = $this->error['phone'];
			}

			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
	}
	
	protected function validate() {
		if ((utf8_strlen($this->request->post['c_name']) < 2) || (utf8_strlen($this->request->post['c_name']) > 32)) {
			$this->error['name'] = $this->language->get('ft_error_name');
		}

		if (!$this->request->post['c_phone']) {
			$this->error['phone'] = $this->language->get('ft_error_phone');
		}

		return !$this->error;
	}
	
}
