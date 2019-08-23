<?php
class ControllerExtensionEventSlasoftBrokenLinks extends Controller {
	public function index(&$view, &$data, &$output) {
		if (!strstr($view,'error/not_found')) return;
		if (!$this->config->get('slasoft_broken_links_enable'))
			return;
		if (in_array($this->request->get['route'], array('checkout/cart')))
			return;
		
		$ip = $this->getRealIpAddr();

		if (isset($this->request->server['HTTP_REFERER'])) {
			$referer = $this->request->server['HTTP_REFERER'];
		} else {
			$referer ="Referer not detected";
		}

		if (isset($this->request->server['HTTP_USER_AGENT'])) {
			$browser = $this->request->server['HTTP_USER_AGENT'];
		} else {
			$browser = "User Agent not detected";
		}

		if (isset($this->request->server['REQUEST_URI'])) {
			$request_uri = $this->config->get('config_url') . ltrim($this->request->server['REQUEST_URI'],'/');
		} else {
			$request_uri = "Uri not detected";
		}

		if (isset($this->request->server['QUERY_STRING'])) {
			$query_string = $this->request->server['QUERY_STRING'];
		} else {
			$query_string = "";
		}

		$datetime = date("Y-m-d G:i:s");

		$data_db = array(
			'ip'          => $ip,
			'browser'     => $browser,
			'referer'     => $referer,
			'request_uri' => $request_uri,
			'query_string'=> $query_string
		);
		$this->db->query("
			INSERT INTO `" . DB_PREFIX . "broken_notfound` 
			SET `date_record` = NOW(),
				`ip` = '" . $this->db->escape($data_db['ip']) . "',
				`browser` = '" .  $this->db->escape($data_db['browser']) . "',
				`referer` = '" .  $this->db->escape($data_db['referer']) . "',
				`request_uri` = '" .  $this->db->escape($data_db['request_uri']) . "'"
		);
	}

	private function getRealIpAddr() {
		if (!empty($this->request->server['HTTP_CLIENT_IP'])) { 
			$ip=$this->request->server['HTTP_CLIENT_IP']; 
		}  elseif (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) { 
			$ip=$this->request->server['HTTP_X_FORWARDED_FOR'];
		} else { 
			$ip=$this->request->server['REMOTE_ADDR'];
		}
		return $ip;
	}
}			
