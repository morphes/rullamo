<?php

class Morphes_Tabs_Block_Tabs extends Mage_Catalog_Block_Product_View_Tabs {

    protected $_tabs = array();
    protected $_product = null;

    function getProduct()
    {
        if (!$this->_product) {
            $this->_product = Mage::registry('product');
        }
        return $this->_product;
    }
    function addTab($alias, $title, $block, $template) {

        if (!$title || !$block || !$template) {
            return false;
        }

        $this->_tabs[] = array(
            'alias' => $alias,
            'title' => $title
        );

        $this->setChild($alias, $this->getLayout()->createBlock($block, $alias)
                        ->setTemplate($template)
        );
    }

    function getTabs() {
        unset($this->_tabs[1]);
        return $this->_tabs;
    }

    function getCity() {
        $city_info = Mage::getSingleton('core/session')->getGeoIp();
        $code = Mage::getModel('directory/region')->load($city_info['code']);
        return $code['name'];

    }

    function ManualPOST($url, $post) {
	$config = Mage::getStoreConfig('carriers/edost');
        if($config['active']==0) {
		return false;
	}
        $_product = $this->getProduct();
        $url = $config['gateway_url'].'/edost_calc_kln.php';
	$city_info = Mage::getSingleton('core/session')->getGeoIp();
        $code = Mage::getModel('directory/region')->load($city_info['code']);
        if($_product->getSpecialPrice==NULL) $price = $_product->getPrice(); else $price = $_product->getSpecialPrice();
        $post = 'to_city='.$code['code'].'&strah='.$price.'&id='.$config['id'].'&p='.$config['password'].'&weight='.$_product->getWeight().'&ln='.$_product->getLength().'&wd='.$_product->getWidth().'&hg='.$_product->getHeight().'&zip=';


        $parse_url = parse_url($url);
        $path = $parse_url["path"];
        $host = $parse_url["host"];
        $rez = "";
        set_error_handler(array('edost_class', 'my_error_handler'));
        $fp = fsockopen($host, 80, $errno, $errstr, 4); 
        restore_error_handler();
        if ($errno == 13)
            return('Err14'); 
        if ($fp) {
        $out = "POST " . $path . " HTTP/1.0\n" .
                "Host: " . $host . "\n" .
                "Referer: " . $url . "\n" .
                "Content-Type: application/x-www-form-urlencoded\n" .
                "Content-Length: " . strlen($post) . "\n\n" .
                $post . "\n\n";
        fputs($fp, $out);
        $q = 0;
        while ($gets = fgets($fp, 512)) {
              $rez.= $gets;
              $q++;
        }

        fclose($fp);
        $rez = stristr($rez, "<?xml version=");
        }
        $data = simplexml_load_string($rez);
        $data = (array) $data;
        return $data;
    }


function getLogistic() {
$weight = $this->getProduct()->getWeight();
if($weight<20) {
    $session = Mage::getSingleton('core/session');
    $session_geo_ip = $session->getGeoIp();
    if(isset($session_geo_ip['code'])) {
                $ship_data = Mage::getModel('freaks_quotes/quote')->loadByName($session_geo_ip['code']);
                if(isset($ship_data['name'])) {
                   $diap = array('20','15','10','5','3','1');
	           $costs = explode(';', $ship_data['costs']);
     	           $costs = array_reverse($costs);
     	           $count = 0;                  
     	           	foreach($diap as $value) {
                   	  if($weight<(int)$value) {
                	          $cost_cour = $ship_data['km'.$value];  //Курьерская стоимость
               		           $cost_sam = $costs[$count];            //Стоимость самовывоза
               		      }
              		       $count++;
             	   }
            	    $diap_km=array('50'=>'500','45'=>'450','40'=>'400','35'=>'350','30'=>'300','25'=>'250','20'=>'200','15'=>'150','10'=>'100','5'=>'50');
            	    if($ship_data['dscr']!='') {
            	        foreach($diap_km as $km=>$cost) {
            	            if($ship_data['dscr']<=$km) $cost_dop_km = $cost;
            	        }
            	    } else {
            	        $cost_dop_km = 0;
            	    }
			
			$result[0]['name'] = 'Доставка курьером';
			$result[0]['cost'] = $cost_cour+$cost_dop_km;
         	       $addresses = explode(';', $ship_data['addressess']);
         	       $count = 1;
         	       foreach($addresses as $address) {
			$result[$count]['name'] = 'Im-Logistics Пункт Самовывоза: '.$address;
			$result[$count]['cost'] = $cost_sam;
      		              $count++;
         	       }
    	} else {
    	    return false;
   	 }
   	 return $result; 
	} else {
	return false;
}
}
	 
}

}
