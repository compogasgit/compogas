<?php
class ModelExtensionTotalCodCdekTotal extends Model {
	public function getTotal($total) {
		
		if (!empty($this->session->data['payment_method']['code']) && $this->session->data['payment_method']['code'] == 'cod_cdek') {
			
			$price_info = $this->config->get('cod_cdek_price');
			
			if ($price_info['value'] && is_numeric($price_info['value'])) {
				
				$value = (float)$price_info['value'];
				
				switch ($price_info['mode']) {
					case 'percent':
						$value = ($this->cart->getSubTotal() / 100) * $value;
						break;
					case 'percent_total':
						$value = ($total['total'] / 100) * $value;
						break;
				}
				
				$title_info = $this->config->get('cod_cdek_total_title');
							
				if (!empty($title_info[$this->config->get('config_language_id')])) {
					$title = $title_info[$this->config->get('config_language_id')];
				} else {
					$this->load->language('extension/total/cod_cdek_total');
					$title = $this->language->get('text_title');
				}
				
				$currency = '';
				if($this->session->data['currency']) {
					$currency = $this->session->data['currency'];
				}

				$total['totals'][] = array( 
					'code'       => 'cod_cdek_total',
					'title'      => $title,
					'text'       => $this->currency->format($price_info['prefix'] . $value, $currency),
					'value'      => $price_info['prefix'] . $value,
					'sort_order' => $this->config->get('cod_cdek_total_sort_order')
				);
				
				if ($price_info['prefix'] == '+') {
					$total['total'] += (float)$value;
				} else {
					$total['total'] -= (float)$value;
				}
				
			}
			
		}
	}
}
?>