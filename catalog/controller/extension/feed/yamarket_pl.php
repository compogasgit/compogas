<?php
/*
+authors: invays and rodigy
*/
class ControllerFeedYamarketPl extends Controller {
    
    private $mpn_format = true; // формат кол-ва для npm: false - одинаковое количество для всех
    //private $cron = false; // использовать крон: true - да, false - нет

    public function index($second_run = false) {
        if (strlen($this->config->get('ya_market_pl_yml_url_key'))) {
            if (!isset($this->request->get['pr_key']) || $this->request->get['pr_key'] != $this->config->get('ya_market_pl_yml_url_key'))
                exit('You don\'t have access here');
        }
        
        if ($this->config->get('ya_market_pl_cron') == 1) {
             if (is_file(DIR_DOWNLOAD . 'ymarket_pl.yml')) {
                $content = file_get_contents(DIR_DOWNLOAD . 'ymarket_pl.yml');

                $this->response->addHeader('Content-Type: application/xml; charset=utf-8');
                $this->response->setOutput($content);
            }
            else if (!$second_run) {
                $this->getYML();
                $this->index(true);
            }
        }
        else {
            $this->response->addHeader('Content-Type: application/xml; charset=utf-8');
            $this->response->setOutput($this->getXML());
        }
    }

    public function getYML() {
        file_put_contents(DIR_DOWNLOAD . 'ymarket_pl.yml', $this->getXML());
    }

    private function getXML()
    {
        set_time_limit(0);

        $for23 = (version_compare(VERSION, "2.3.0", '>='))?"extension/":"";
        $this->load->model($for23.'yamodel/yamarket_pl');
        $this->load->model('catalog/product');
        $this->load->model('tool/image');
        $this->load->model('localisation/currency');

        // correct the default opencart timezone
        if ($this->config->get('ya_market_pl_timezone'))
			date_default_timezone_set($this->config->get('ya_market_pl_timezone'));

        $categories = $this->{"model_".str_replace("/","_",$for23)."yamodel_yamarket_pl"}->getCategories();
        $allow_cat_array = explode(',', $this->config->get('ya_market_pl_categories'));
        if (!empty($allow_cat_array) || $this->config->get('ya_market_pl_catall')){
            $ids_cat = ($this->config->get('ya_market_pl_catall')) ? '': implode(',', $allow_cat_array);
        } else {
            die("Need select categories");
        }

       if ($this->config->get('ya_market_pl_repair_yml') == 0) {
				$products = $this->{"model_".str_replace("/","_",$for23)."yamodel_yamarket_pl"}->getProducts($ids_cat, true);
				if (count($products)) $products = $this->{"model_".str_replace("/","_",$for23)."yamodel_yamarket_pl"}->getProducts($ids_cat, false);
		}else{
				 $products = $this->{"model_".str_replace("/","_",$for23)."yamodel_yamarket_pl"}->getProducts($ids_cat, false);
				 if (count($products)) $products = $this->{"model_".str_replace("/","_",$for23)."yamodel_yamarket_pl"}->getProducts($ids_cat, false);
		};
		
        //$products = $this->{"model_".str_replace("/","_",$for23)."yamodel_yamarket_pl"}->getProducts($ids_cat, true);
       // if (count($products)) $products = $this->{"model_".str_replace("/","_",$for23)."yamodel_yamarket_pl"}->getProducts($ids_cat, false);
        
        // filtering product by manufacturers
        $market_manufacturers = $this->config->get('ya_market_pl_manufacturers');
        if (!empty($market_manufacturers)) {
            $products = array_filter($products, function($el) use ($market_manufacturers) {
                if (!$el['manufacturer_id']) {
                    return true;
                }
                else {
                    return in_array($el['manufacturer_id'], $market_manufacturers);
                }
            });
        }
        
        $currencies = $this->model_localisation_currency->getCurrencies();
        $shop_currency = $this->config->get('config_currency');
		$default_currency = $this->config->get('ya_market_pl_default_currency');
        $offers_currency = $default_currency;
        $currency_default = $this->{"model_".str_replace("/","_",$for23)."yamodel_yamarket_pl"}->getCurrencyByISO($offers_currency);
        if (!isset($currency_default['value'])) die("Not exist currency");

        // get atributes
        if ($this->config->get('ya_market_pl_features')) {
            $product_ids = array_map(function($el) {return $el['product_id'];}, $products);
            $attributes = $this->{"model_" . str_replace("/","_",$for23) . "yamodel_yamarket_pl"}->getProductsAttributes($product_ids);
        }

        $decimal_place = 2;
        $currencies = array_intersect_key($currencies, array_flip(array('RUR', 'RUB', 'USD', 'EUR', 'UAH', 'BYN')));
        $yamarket = new YandexMarket($this->config);
        $yamarket->yml('utf-8');
        $yamarket->set_shop(
            $this->config->get('ya_market_pl_shopname'),
            $this->config->get('config_name'),
            $this->config->get('config_url')
        );
        $data = array();
        if ($this->config->get('ya_market_pl_allcurrencies')){
            foreach ($currencies as $currency)
                if ($currency['status'] == 1)
                    $yamarket->add_currency($currency['code'], ((float)$currency_default['value']/(float)$currency['value']));
        }
        else
            $yamarket->add_currency($currency_default['code'], ((float)$currency_default['value']));

        foreach ($categories as $category)
        {
            if (!$this->config->get('ya_market_pl_catall'))
                if(!in_array($category['category_id'], $allow_cat_array))
                    continue;

            $yamarket->add_category($category['name'], $category['category_id'], $category['parent_id']);
        }

        $data_product =array();
        foreach ($products as $product)
        {
            if ($this->config->get('ya_market_pl_available') && $product['quantity'] < 1)   continue;

            $available = false;
            if ($this->config->get('ya_market_pl_set_available') == 1)
                $available = true;
            elseif ($this->config->get('ya_market_pl_set_available') == 2)
            {
                if ($product['quantity'] > 0) $available = true;
            }
            elseif ($this->config->get('ya_market_pl_set_available') == 3)
            {
                $available = true;
                if ($product['quantity'] == 0) continue;
				if ($product['nazakaz'] == 1) $available = false;
            }
            elseif ($this->config->get('ya_market_pl_set_available') == 4){
                $available = false;
            }
            $data = array();
            $data['id'] = $product['product_id'];
            $data['available'] = $available;
            $data['url'] = htmlspecialchars_decode($this->url->link('product/product', 'product_id=' . $product['product_id']));

            if ($this->config->get('ya_market_pl_valuta') == 1) {
			if ($product['base_price'] > 0.0000){
				$data['price'] = $product['base_price'];
			};
			if ($product['base_price'] == 0.0000){

			$data['price'] = round(floatval($product['price']), 2);
				if ($this->config->get('ya_market_pl_oldpricev') == 1) {
				if ($product['special'] && $product['special'] < $product['price']){
					$data['price'] = round(floatval($product['special']), 2);
					$data['oldprice'] = round(floatval($product['price']), 2);
				}
				}
			};
			if (utf8_strlen($product['base_currency_code'])) {
				$data['currencyId'] = explode(',', $product['base_currency_code']);
			}
			}else{
            $data['price'] = round(floatval($product['price']), 2);
			if ($this->config->get('ya_market_pl_oldprice') == 1) {
            if ($product['special'] && $product['special'] < $product['price']){
                $data['price'] = round(floatval($product['special']), 2);
                $data['oldprice'] = round(floatval($product['price']), 2);
            }
			}
			$data['currencyId'] = $currency_default['code'];
			}
            $data['categoryId'] = $product['category_id'];
            $data['vendor'] = $product['manufacturer'];
            $data['vendorCode'] = $product['model'];
			$data['description'] = $product['description'];
           
			$data['country_of_origin'] = $product['country'];
			
			if ($this->config->get('ya_market_pl_sales_note') == 1) {
			$data['sales_notes'] = $product['sales'];
			}else{
				$data['sales_notes'] = $this->config->get('ya_market_pl_sales_note_text');
			}
			
			if ( $product['shipping'] == 1) {
			$data['delivery'] = 'true';
			};
			if ( $product['shipping'] == 0) {
			$data['delivery'] = 'false';
			};
			
			if ( $product['pickup'] == 1) {
			$data['pickup'] = 'true';
			};
			if ( $product['pickup'] == 0) {
			$data['pickup'] = 'false';
			};
			
			
			if ( $product['store'] == 1) {
			$data['store'] = 'true';
			};
			if ( $product['store'] == 0) {
			$data['store'] = 'false';
			};
			
			if ($this->config->get('ya_market_pl_garanty') == 1) {
			if($product['garanty'] == 1){
				$data['manufacturer_warranty'] = 'true';
			};
			if($product['garanty'] == 0){
				$data['manufacturer_warranty'] = 'false';
			};
			}
			
			if ($this->config->get('ya_market_pl_adult') == 1) {
			if($product['adult'] == 1){
				$data['adult'] = 'true';
			};
			if($product['adult'] == 0){
				$data['adult'] = 'false';
			};
			}
			if ($this->config->get('ya_market_pl_barcode') == 1) {
			if (utf8_strlen($product['barcode'])) {
                $data['barcode'] = explode(',', $product['barcode']);
            };
			}
			
			if ($this->config->get('ya_market_pl_productcode_ind') == 1) {
			if ($this->config->get('ya_market_pl_productcode') == 0) {
			if ($this->config->get('ya_market_pl_avtoprice') == 1) {
				$data['vendorCode'] = $product['sku'];
			}else{
				$data['vendorCode'] = $product['model'];
			};

			}else{
			if ($this->config->get('ya_market_pl_avtoprice') == 1) {
				$data['model'] = $product['sku'];
			}else{
				$data['model'] = $product['model'];
			};
			};
			}else{
			if ($this->config->get('ya_market_pl_avtoprice') == 1) {
				$data['vendorCode'] = $product['sku'];
				$data['model'] = $product['sku'];
			}else{
				$data['vendorCode'] = $product['model'];
				$data['model'] = $product['model'];
			};
			}
            
			///Включить если нужен
            // Оutlets
            /*if (!empty($product['mpn'])) {
                $stocks = explode(',', $product['mpn']);
                $instocks = explode(',', $product['jan']);

                if (count($instocks) != count($stocks) || !$this->mpn_format) {
                    $instocks = array_fill(0, count($stocks), $product['quantity']);
                }

                foreach ($stocks as $i => $value) {
                    $data['outlet'][] = array(
                        'mpn' => $value,
                        'instock' => $instocks[$i]
                    );
                }
            }*/

            // attributes replace the attributes of yandex
            if ($this->config->get('ya_market_pl_features')) {
                if (isset($attributes[$product['product_id']])) {
                    foreach ($attributes[$product['product_id']] as $attribute) {
                        $data['param'][] = array(
                            'id' => $attribute['attribute_id'],
                            'name' => $attribute['name'],
                            'value' => $attribute['text']
                        );
                    }
                }
            }

            // Custom tags
            /*foreach ($this->config->get('ya_market_pl_custom_market_tags') as $product_param => $tag) {
                if (strlen($tag) && strlen($product[$product_param])) {
                    $data['custom_market_tags'][$tag] = $product[$product_param];
                }
            }*/

            // Delivery
            $stock_id = $product['stock_status_id'];
            $delivery_cost_params = explode(',', $this->config->get('ya_market_pl_stock_cost')[$stock_id]);
			
            if ($this->config->get('ya_market_pl_delivery_ind') == 1) {
            if ($this->config->get('ya_market_pl_delivery_options') && $product['quantity'] < 1) {
                $delivery_days = $this->config->get('ya_market_pl_stock_days');
                $delivery_times = $this->config->get('ya_market_pl_stock_times');
                $delivery_days_params = explode(',', $delivery_days[$stock_id]);
                $delivery_times_params = explode(',', $delivery_times[$stock_id]);
                
                // modified by rodigy: a few values for one status
                if (count($delivery_cost_params) == count($delivery_days_params)) {
                    foreach ($delivery_cost_params as $i => $d_val) {
                        $do_param = array(
                            'cost' => $d_val,
                            'days' => $delivery_days_params[$i]
                        );

                        if (isset($delivery_times_params[$i]) && $delivery_times_params[$i] != '') {
                            $do_param['order-before'] = $delivery_times_params[$i];
                        }

                        $data['delivery-options'][] = $do_param;
                    }
                }
                else {
                    // to do throw error
                    $data['delivery-options'][] = array(
                        'cost' => $delivery_cost_params[0],
                        'days' => $delivery_days_params[0]
                    ); 
                }
            }
			}


            if ($this->config->get('ya_market_pl_local_cost_delivery')) {
                // For mail.ru price
                if (!empty($delivery_cost_params)) { // product out in stock
                    $data['local_delivery_cost'] = $delivery_cost_params[0];
                }
                else {
                    $data['local_delivery_cost'] = explode (',', $this->config->get('ya_market_pl_stock_cost'))[0];
                }
            }
            
            if ($this->config->get('ya_market_pl_imagechange_yml') == 0) {
			$data['picture'] = array();
            if (isset($product['image'])) $data['picture'][] = str_replace(" ", "%20", $this->model_tool_image->resize($product['image'], 600, 600));
            foreach ($this->model_catalog_product->getProductImages($data['id']) as $pic){
                if (count($data['picture'])<=9) $data['picture'][] = str_replace(array('&amp;',' '),array('&', '%20'), $this->model_tool_image->resize($pic['image'], 600, 600));
            }
			}else{
			$data['image'] = array();
            if (isset($product['image'])) $data['image'][] = str_replace(" ", "%20", $this->model_tool_image->resize($product['image'], 600, 600));
            foreach ($this->model_catalog_product->getProductImages($data['id']) as $pic){
                if (count($data['image'])<=9) $data['image'][] = str_replace(array('&amp;',' '),array('&', '%20'), $this->model_tool_image->resize($pic['image'], 600, 600));
            }
			}

            if ($this->config->get('ya_market_pl_prostoy'))
            {
                $data['price'] = number_format($this->currency->convert($this->tax->calculate($data['price'], $product['tax_class_id'], $this->config->get('config_tax')), $shop_currency, $offers_currency), $decimal_place, '.', '');
                $data['name'] = $product['name'];
                if ($data['price'] > 0)
                    $yamarket->add_offer($data['id'], $data, $data['available']);
            }
            else
            {
                $data['model'] = $product['name'];
                if ($product['weight'] > 0)
                    $data['weight'] = number_format($product['weight'], 1, '.', '');
                if ($this->config->get('ya_market_pl_dimensions') && $product['length'] > 0 && $product['width'] > 0 && $product['height'] > 0)
                    $data['dimensions'] = number_format($product['length'], 1, '.', '').'/'.number_format($product['width'], 1, '.', '').'/'.number_format($product['height'], 1, '.', '');
                //$data['downloadable'] = 'false';
                //$data['rec'] = explode(',', $product['rel']);
				$data['typeprefix'] = $product['typeprefix'];
                $data['param'][] = array('id' => 'weight', 'name' => 'Вес', 'value' => number_format($product['weight'], 1, '.', ''), 'unit' => $product['weight_unit']);
                /* old attributes
                if ($this->config->get('ya_market_pl_features'))
                {
                    $attributes = $this->model_catalog_product->getProductAttributes($data['id']);
                    if (count($attributes))
                        foreach ($attributes as $attr)
                            foreach ($attr['attribute'] as $val)
                                $data['param'][] = array('id' => $val['attribute_id'], 'name' => $val['name'], 'value' => $val['text']);
                }
                */

                if (!$this->makeOfferCombination($data, $product, $shop_currency, $offers_currency, $decimal_place, $yamarket))
                {
                    $data['price'] = number_format($this->currency->convert($this->tax->calculate($data['price'], $product['tax_class_id'], $this->config->get('config_tax')), $shop_currency, $offers_currency), $decimal_place, '.', '');
                    if ($data['price'] > 0)
                        $yamarket->add_offer($data['id'], $data, $data['available']);
                }
            }
        }

        //$this->response->addHeader('Content-Type: application/xml; charset=utf-8');
        //$this->response->setOutput($yamarket->get_xml());

        return $yamarket->get_xml();
    }

    public function makeOfferCombination($data, $product, $shop_currency, $offers_currency, $decimal_place, $object)
    {
        $colors = array();
        $sizes = array();
        $for23 = (version_compare(VERSION, "2.3.0", '>='))?"extension/":"";
        if (count($this->config->get('ya_market_pl_color_options')))
            $colors = $this->{"model_".str_replace("/","_",$for23)."yamodel_yamarket_pl"}->getProductOptions($this->config->get('ya_market_pl_color_options'), $product['product_id']);
        if (count($this->config->get('ya_market_pl_size_options')))
            $sizes = $this->{"model_".str_replace("/","_",$for23)."yamodel_yamarket_pl"}->getProductOptions($this->config->get('ya_market_pl_size_options'), $product['product_id']);
        if (!count($colors) && !count($sizes))
            return false;

        $options_init = $this->config->get('ya_market_pl_product_options_unit');

        if(count($colors))
        {
            foreach ($colors as $option)
            {
                $data_temp = $data;
                $data_temp['model'].= ', '.$option['option_name'].' '.$option['name'];
                
                $param_arr = array(
                    'name' => $option['option_name'],
                    'value' => $option['name']
                );
                if (isset($options_init[$option['option_id']]) && utf8_strlen($options_init[$option['option_id']]))
                     $param_arr['unit'] = $options_init[$option['option_id']];

                $data_temp['param'][] = $param_arr;

                $data_temp['id'] = $product['product_id'].'c'.$option['option_value_id'];
                $data_temp['available'] = $data['available'];
                if ($option['price_prefix'] == '+') {
                    $data_temp['price']+= $option['price'];
                    if (isset($data_temp['oldprice']))
                        $data_temp['oldprice']+= $option['price'];
                }
                elseif ($option['price_prefix'] == '-') {
                    $data_temp['price']-= $option['price'];
                    if (isset($data_temp['oldprice']))
                        $data_temp['oldprice']-= $option['price'];
                }
                elseif ($option['price_prefix'] == '=') {
                    $data_temp['price'] = $option['price'];
                }
                $data_temp = $this->setOptionedWeight($data_temp, $option);
                $data_temp['url'].= '#'.$option['product_option_value_id'];
                $colors_array[] = $data_temp;
            }
        }
        else
        {
            $colors_array[] = $data;
        }

        unset($data_temp);
        unset($option);
        foreach($colors_array as $i => $data)
            if(count($sizes))
            {
                foreach ($sizes as $option)
                {
                    $data_temp = $data;
                    $data_temp['id'] .= 'c'.$option['option_value_id'];
                    $data_temp['model'].= ', '.$option['option_name'].' '.$option['name'];
                   
                    $param_arr = array(
                        'name' => $option['option_name'],
                        'value' => $option['name']
                    );
                    if (isset($options_init[$option['option_id']]) && utf8_strlen($options_init[$option['option_id']]))
                         $param_arr['unit'] = $options_init[$option['option_id']];

                    $data_temp['param'][] = $param_arr;

                    $data_temp['available'] = $data['available'];
                    if ($option['price_prefix'] == '+') {
                        $data_temp['price']+= $option['price'];
                        if (isset($data_temp['oldprice']))
                            $data_temp['oldprice']+= $option['price'];
                    }
                    elseif ($option['price_prefix'] == '-') {
                        $data_temp['price']-= $option['price'];
                        if (isset($data_temp['oldprice']))
                            $data_temp['oldprice']-= $option['price'];
                    }
                    elseif ($option['price_prefix'] == '=') {
                        $data_temp['price'] = $option['price'];
                    }

                    $data_temp = $this->setOptionedWeight($data_temp, $option);
                    if (count($colors))
                        $data_temp['url'].= '-'.$option['product_option_value_id'];
                    else
                        $data_temp['url'].= '#'.$option['product_option_value_id'];

                    $data_temp['price'] = number_format($this->currency->convert($this->tax->calculate($data_temp['price'], $product['tax_class_id'], $this->config->get('config_tax')), $shop_currency, $offers_currency), $decimal_place, '.', '');
                    if (isset($data_temp['oldprice']))
                        $data_temp['oldprice'] = number_format($this->currency->convert($this->tax->calculate($data_temp['oldprice'], $product['tax_class_id'], $this->config->get('config_tax')), $shop_currency, $offers_currency), $decimal_place, '.', '');
                    if ($data['price'] > 0) {
                        $object->add_offer($data_temp['id'], $data_temp, $data_temp['available'], $product['product_id']);
                    }
                    unset($data_temp);
                }
            }
            else
            {
                $data['price'] = number_format($this->currency->convert($this->tax->calculate($data['price'], $product['tax_class_id'], $this->config->get('config_tax')), $shop_currency, $offers_currency), $decimal_place, '.', '');
                if ($data['price'] > 0) {
                    $object->add_offer($data['id'], $data, $data['available'], $product['product_id']);
                }
            }

        return true;
    }

    protected function setOptionedWeight($product, $option) {
        if (isset($option['weight']) && isset($option['weight_prefix'])) {
            foreach ($product['param'] as $i=>$param) {
                if (isset($param['id']) && ($param['id'] == 'WEIGHT')) {
                    if ($option['weight_prefix'] == '+')
                        $product['param'][$i]['value']+= $option['weight'];
                    elseif ($option['weight_prefix'] == '-')
                        $product['param'][$i]['value']-= $option['weight'];
                    break;
                }
            }
        }
        return $product;
    }

    public function apiTokenCallback() {
        if (isset($this->request->get['code'])) {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, "https://oauth.yandex.ru/token");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
                'grant_type' => 'authorization_code',
                'code' => $this->request->get['code'],
                'client_id' => $this->config->get('ya_market_pl_api_client_id'),
                'client_secret' => $this->config->get('ya_market_pl_api_secret')
            )));

            $response = curl_exec($ch);

            if ($response !== null) {
                $content = json_decode($response, true);

                if ($content) {
                    if (empty($content['error'])) {
                        $date = date('Y-m-d H:i:s', time() + $content['expires_in']);

                        $this->db->query("DELETE FROM " . DB_PREFIX . "setting 
                                            WHERE `key` IN ('ya_market_pl_api_token_refresh', 'ya_market_pl_api_token', 'ya_market_pl_api_token_expires_date') 
                                                AND `store_id` = '{$this->config->get('config_store_id')}'");

                        $this->db->query("INSERT INTO " . DB_PREFIX . "setting(`store_id`, `code`, `key`, `value`, `serialized`) VALUES 
                                            ('{$this->config->get('config_store_id')}', 'ya_market_pl', 'ya_market_pl_api_token', '{$this->db->escape($content['access_token'])}', 0), 
                                            ({$this->config->get('config_store_id')}, 'ya_market_pl', 'ya_market_pl_api_token_refresh', '{$this->db->escape($content['refresh_token'])}', 0),
                                            ({$this->config->get('config_store_id')}, 'ya_market_pl', 'ya_market_pl_api_token_expires_date', '{$this->db->escape($date)}', 0)");
                        echo "Токен успешно получен";
                    } else {
                        exit(sprintf('Error code: %s, %s', $content['error'], $content['error_description']));
                    }
                }
            } else {
                exit(curl_error($ch));
            }
        }
    }
}

class YandexMarket{
    private $config;
    var $from_charset = 'windows-1251';
    var $shop = array('name' => '', 'company' => '', 'url' => '', 'platform' => 'ya_opencart');
    var $currencies = array();
    var $categories = array();
    var $offers = array();

    public function __construct(&$config){
        $this->config = $config;
    }

    function yml($from_charset = 'windows-1251')
    {
        $this->from_charset = trim(strtolower($from_charset));
    }


    function convert_array_to_tag($arr)
    {
        $s = '';
        foreach($arr as $tag => $val)
        {
            if($tag == 'weight' && (int)$val == 0)
                continue;

            if($tag == 'picture')
            {
                foreach ($val as $v){
                    $s .= '<'.$tag.'>'.$v.'</'.$tag.'>';
                    $s .= PHP_EOL;
                }
            }
			elseif($tag == 'image')
            {
                foreach ($val as $v){
                    $s .= '<'.$tag.'>'.$v.'</'.$tag.'>';
                    $s .= PHP_EOL;
                }
            }
            elseif($tag == 'param')
            {
                foreach ($val as $v){
                    $unit = isset($v['unit']) ? 'unit="' . $this->prepare_field($v['unit']) . '"' : '';
                    $s .= '<param name="'.$this->prepare_field($v['name']).'" ' . $unit . '>'.$this->prepare_field($v['value']).'</param>';
                    $s .= PHP_EOL;
                }
            }/* elseif ($tag == 'custom_market_tags') {
                foreach($val as $custom_tag => $tag_val) {
                    $s .= "<{$custom_tag}>{$tag_val}</{$custom_tag}>" . PHP_EOL;
                }            
            } */elseif($tag == 'delivery-options') { 
                $s .= '<delivery-options>' . PHP_EOL;
                foreach ($val as $v){
                    $time = isset($v['order-before']) ? 'order-before="' . $v['order-before'] . '"' : '';
                    $s .= '<option cost="'.$v['cost'].'" days="'.$v['days'].'" ' . $time . '/>'.PHP_EOL;
                }
                $s .= '</delivery-options>' . PHP_EOL;
                unset($time);
            } elseif($tag == 'local_delivery_cost') { 
                $s .= '<local_delivery_cost>' . $val . '</local_delivery_cost>' . PHP_EOL;
                unset($time);
            } elseif ($tag == 'barcode') {
                foreach ($val as $v){
                    $s .= '<barcode>' . $v . '</barcode>' . PHP_EOL;
                }
            }/* elseif ($tag == 'outlet') {
                $s .= '<outlets>';
                foreach ($val as $v){
                    $s .= '<outlet id="' . $v['mpn'] . '" instock="' . $v['instock'] . '"/>' . PHP_EOL;
                }
                $s .= '</outlets>' . PHP_EOL;
            }*/ elseif ($tag == 'description') {
                $s .= '<description><![CDATA[' . $val . ']]></description>' . PHP_EOL;
            } else {
                $s .= '<'.$tag.'>'.$val.'</'.$tag.'>';
                $s .= PHP_EOL;
            }
        }

        return $s;
    }

    function convert_array_to_attr($arr, $tagname, $tagvalue = '')
    {
        $s = '<'.$tagname.' ';
        foreach($arr as $attrname=>$attrval)
            $s .= $attrname . '="'.$attrval.'" ';

        $s .= ($tagvalue!='') ? '>'.$tagvalue.'</'.$tagname.'>' : '/>';
        $s .= PHP_EOL;
        return $s;
    }

    function prepare_field($s)
    {
        $from = array('"', '&', '>', '<', '\'');
        $to = array('&quot;', '&amp;', '&gt;', '&lt;', '&apos;');
        $s = str_replace($from, $to, $s);
        $s=preg_replace('!<[^>]*?>!', ' ', $s);
        // if ($this->from_charset!='windows-1251') $s = iconv($this->from_charset, 'windows-1251', $s);
        $s = preg_replace('#[\x00-\x08\x0B-\x0C\x0E-\x1F]+#is', ' ', $s);
        return trim($s);
    }

    function set_shop($name, $company, $url){
        $this->shop['name'] = $this->prepare_field($name);
        $this->shop['name'] = mb_substr(mb_convert_encoding($this->shop['name'], "UTF-8"), 0, 20);
        $this->shop['company'] = $this->prepare_field($company);
        $this->shop['url'] = $this->prepare_field($url);
    }

    function add_currency($id, $rate = 'CBRF', $plus = 0)
    {
        $rate = strtoupper($rate);
        $plus = str_replace(',', '.', $plus);
        if ($rate=='CBRF' && $plus>0)
            $this->currencies[] = array('id'=>$this->prepare_field(strtoupper($id)), 'rate'=>'CBRF', 'plus'=>(float)$plus);
        else
        {
            $rate = str_replace(',', '.', $rate);
            $this->currencies[] = array('id'=>$this->prepare_field(strtoupper($id)), 'rate'=>(float)$rate);
        }
        return true;
    }

    function add_category($name, $id, $parent_id = -1)
    {
        if ((int)$id<1||trim($name)=='') return false;
        if ((int)$parent_id>0)
            $this->categories[] = array('id'=>(int)$id, 'parentId'=>(int)$parent_id, 'name'=>$this->prepare_field($name));
        else
            $this->categories[] = array('id'=>(int)$id, 'name'=>$this->prepare_field($name));
        return true;
    }

    function add_offer($id, $data, $available = true, $group_id = 0)
    {
        
        $allowed = array(
            'url', 'price', 'oldprice', 'currencyId', 'categoryId', 'picture', 'store', 'pickup', 'delivery',
            'name', 'vendor', 'vendorCode', 'model', 'description', 'sales_notes','oldprice',
            'delivery-options','downloadable', 'weight', 'dimensions', 'param', 'country_of_origin',
            'barcode', 'outlet', 'local_delivery_cost', // local_delivery_cost for mail.ru price, 
            'custom_market_tags','adult','manufacturer_warranty','image'
        );
        foreach($data as $k => $v)
        {
            if(!in_array($k, $allowed)) unset($data[$k]);
            //  if ($id == 48) {
            //var_dump($data, !in_array($k, $allowed));
            //exit;
            // }
            if(!in_array($k, array('picture','image','param','rec','description','delivery-options', 'barcode', 'outlet', 'custom_market_tags')))
                $data[$k] = strip_tags($this->prepare_field($v));
            if ($k=='description') {
                $data[$k] = preg_replace('|<[/]?[^>]+?>|', '', trim(html_entity_decode ($v)));
                $data[$k] = preg_replace("/&#?[a-z0-9]+;/i", '', $data[$k]);
                if (strlen($data[$k])>=3000) {
                    $iCut = strpos($data[$k], ' ', 2950);
                    $data[$k] = substr($data[$k], 0, $iCut);
                }
            }
        }
        $tmp = $data;
        $data = array();
        foreach($allowed as $key)
            if (isset($tmp[$key]) && !empty($tmp[$key]))
                $data[$key] = $tmp[$key];

        $vendor_mode = $this->config->get('ya_market_pl_vendor_mode');
        $out = array('id' => $id, 'data' => $data, 'available' => ($available) ? 'true' : 'false');
        if ($group_id>0) $out['group_id'] = $group_id;
        if(!$this->config->get('ya_market_pl_prostoy'))
            $out['type'] = $vendor_mode;
        $this->offers[] = $out;
    }

    function get_xml_header()
    {
        $catalog_yml = $this->config->get('ya_market_pl_catalog_yml');
		return '<?xml version="1.0" encoding="utf-8"?>'.
        '<'.$catalog_yml.' date="'.date('Y-m-d H:i').'">';
    }

    function get_xml_shop()
    {
		$shop_yml = $this->config->get('ya_market_pl_shop_yml');
		$currencies_yml = $this->config->get('ya_market_pl_currencies_yml');
		$currency_yml = $this->config->get('ya_market_pl_currency_yml');
		$categories_yml = $this->config->get('ya_market_pl_categories_yml');
		$category_yml = $this->config->get('ya_market_pl_category_yml');
		$offers_yml = $this->config->get('ya_market_pl_offers_yml');
		$offer_yml = $this->config->get('ya_market_pl_offer_yml');
		
		$s = '<'.$shop_yml.'>' . PHP_EOL;
        $s .= $this->convert_array_to_tag($this->shop);
        $s .= '<'.$currencies_yml.'>' . PHP_EOL;
        foreach($this->currencies as $currency)
            $s .= $this->convert_array_to_attr($currency, $currency_yml);

		$s .= '</'.$currencies_yml.'>' . PHP_EOL;
        $s .= '<'.$categories_yml.'>' . PHP_EOL;
        foreach($this->categories as $category)
        {
            $category_name = $category['name'];
            unset($category['name']);
            $s .= $this->convert_array_to_attr($category,  $category_yml, $category_name);
        }
        $s .= '</'.$categories_yml.'>' . PHP_EOL;

        if ($this->config->get('ya_market_pl_delivery_options'))  {
            $localShippingCost = explode (',', $this->config->get('ya_market_pl_localcoast'));
            $localShippingDays = explode (',', $this->config->get('ya_market_pl_localdays'));
            $localShippingTimes = explode (',', $this->config->get('ya_market_pl_localtimes'));
            if (count($localShippingCost) != count ($localShippingDays)) throw new Exception("'Стоимость доставки в домашнем регионе' и/или 'Срок доставки в домашнем регионе' заполнены с ошибкой");
            $s .= '<delivery-options>'. PHP_EOL;
            foreach ($localShippingCost as $key=>$value){
                if (isset($localShippingTimes[$key]) && $localShippingTimes[$key] != '') {
                    $time = 'order-before="' . $localShippingTimes[$key] . '"';
                }
                else {
                     $time = '';
                }
                
                $s .= '<option cost="'.$value.'" days="'.$localShippingDays[$key].'" ' . $time . '/>'. PHP_EOL;
            }
            unset($time);
            $s .=  '</delivery-options>' . PHP_EOL;
        }

        $s .= '<'.$offers_yml.'>' . PHP_EOL;
        foreach($this->offers as $offer)
        {
            $data = $offer['data'];
            unset($offer['data']);
            $s .= $this->convert_array_to_attr($offer, $offer_yml, $this->convert_array_to_tag($data));
        }
        $s .= '</'.$offers_yml.'>' . PHP_EOL;
        $s .= '</'.$shop_yml.'>';
        return $s;
    }

    function get_xml_footer()
    {
        $catalog_yml = $this->config->get('ya_market_pl_catalog_yml');
       return '</'.$catalog_yml.'>';
    }

    function get_xml()
    {
        $xml = $this->get_xml_header();
        $xml .= $this->get_xml_shop();
        $xml .= $this->get_xml_footer();
        return $xml;
    }
}

class ControllerExtensionFeedYamarketPl extends ControllerFeedYamarketPl {}
