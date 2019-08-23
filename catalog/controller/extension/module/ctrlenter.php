<?php
class ControllerExtensionModuleCtrlenter extends Controller {
	public function index(&$route, &$data, &$output) {
		if ($this->config->get('module_ctrlenter_status')) {
			$this->language->load('extension/module/ctrlenter');
			$data['form'] =false;
			$data['text_message'] = $this->language->get('text_message');
			$data['config_template'] = $this->config->get('config_template');
		
			$template =  'extension/module/ctrlenter';
//			$this->load->view($template, $data);
			$output = str_replace('</body>',$this->load->view($template, $data) . '</body>', $output);
		}
	}

	public function send() {
		$this->language->load('extension/module/ctrlenter');
		$json = array();
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$ip = "";
			if(isset($HTTP_SERVER_VARS)){if(isset($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"])){$ip=$HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"];}elseif(isset($HTTP_SERVER_VARS["HTTP_CLIENT_IP"])) {$ip=$HTTP_SERVER_VARS["HTTP_CLIENT_IP"];}else{$ip=$HTTP_SERVER_VARS["REMOTE_ADDR"];}}else{if(getenv('HTTP_X_FORWARDED_FOR')){$ip=getenv('HTTP_X_FORWARDED_FOR');}elseif (getenv('HTTP_CLIENT_IP')){$ip=getenv('HTTP_CLIENT_IP');}else{$ip=getenv('REMOTE_ADDR');}}
			$referer ="not detected";if(isset($_SERVER['HTTP_REFERER'])){$referer=$_SERVER['HTTP_REFERER'];}
			$browser = "-";if(isset($_SERVER['HTTP_USER_AGENT'])){$browser = $_SERVER['HTTP_USER_AGENT'];}

			$datetime = date("Y-m-d G:i:s");
			$ctrlenter_errors =''; $message=''; $url='';
			if (isset ($this->request->post['ctrlenter'])) $ctrlenter_errors=$this->request->post['ctrlenter'];
			if (isset ($this->request->post['message'])) $message=$this->request->post['message'];
			if (isset ($this->request->post['url'])) $url=$this->request->post['url'];
			
			
			if (utf8_strlen($this->request->post['ctrlenter']) < 5) {
				$json['error'] = $this->language->get('text_errors');
				$data['message'] = $json['error'];
				$json['form'] = $this->load->view('extension/module/ctrlenter_error', $data);
			}

			if (empty($this->request->post['ctrlenter']) && utf8_strlen($this->request->post['message']) > 10) {
				unset ($json['error']);
			}

			if (!isset($json['error'])) {
				$result = $this->db->query("
				INSERT INTO `" . DB_PREFIX . "ctrlenter` 
				SET error = '" . $this->db->escape($ctrlenter_errors) . "',
					comment = '" . $this->db->escape($message) . "',
					language_id = '" . (int)$this->config->get('config_language_id') . "',
					store_id = '" . (int)$this->config->get('config_store_id') . "',
					ip = '" . $this->db->escape($ip) . "',
					referer = '" .$this->db->escape($referer) . "',
					browser = '" . $this->db->escape($browser) ."',
					location = '". $this->db->escape($url)     ."',
					date_added = NOW(),
					customer_id = '". (int)$this->customer->getID() . "'");

				$json['success'] = $this->language->get('text_success');
				$data['message'] =  $json['success'];
				$json['form'] = $this->load->view('extension/module/ctrlenter_success', $data);

				$text = str_replace('\n',"\n",sprintf($this->language->get('text_mail_message'), $url, $ctrlenter_errors, $message ));
				$text .= "\nIP:". $ip . "\n";
				$text .="referer: $referer \n";
				$text .="browser: $browser \n";
				$text .="date: $datetime";
				$subject = $this->language->get('text_new_subject');

				$mail = new Mail($this->config->get('config_mail_engine'));
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
				$mail->setTo($this->config->get('config_email'));
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));				
				$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
				$mail->setText(html_entity_decode($text, ENT_QUOTES, 'UTF-8'));
				$mail->send();
				if ($this->config->get('module_ctrlenter_email_add')) {
					$mail->setTo($this->config->get('ctrlenter_email_add'));
					$mail->send();
				}
			}
		}
		$this->response->setOutput(json_encode($json));
	}

	public function get() {
		$this->language->load('extension/module/ctrlenter');
		$data['ctrlenter_title'] = $this->language->get('ctrlenter_title');
		$data['ctrlenter_error'] = $this->language->get('ctrlenter_error');
		$data['ctrlenter_comment'] = $this->language->get('ctrlenter_comment');
		$data['ctrlenter_close'] = $this->language->get('ctrlenter_close');
		$data['ctrlenter_send'] = $this->language->get('ctrlenter_send');
		$text ='';
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$text=$this->request->post['ctrlenter'];
		}
		$data['form'] =true;
		$data['ctrlenter'] = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
		
		$template = 'extension/module/ctrlenter';
		$this->response->setOutput($this->load->view($template, $data));
	}
}
