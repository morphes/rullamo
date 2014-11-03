<?php
class Mba_BlankShipping_Model_Carrier_ShippingMethod extends Mage_Shipping_Model_Carrier_Abstract
{
	protected $_code = 'blankshippingmodule';

	public function collectRates(Mage_Shipping_Model_Rate_Request $request)
	{


		// skip if not enabled
		if(!Mage::getStoreConfig('carriers/'.$this->_code.'/active'))
			return false;
$config =  Mage::getStoreConfig('carriers/'.$this->_code);

$session = Mage::getSingleton('checkout/session');
$sum = 0;

foreach ($session->getQuote()->getAllVisibleItems() as $item) {
     $sum += $item->getQty()*$item->getWeight();
}
if(($sum>20)&&($sum<25)) {
    $session = Mage::getSingleton('core/session');
    $session_geo_ip = $session->getGeoIp();
    if(isset($session_geo_ip['code'])) { 
                $ship_data = Mage::getModel('freaks_quotes/quote')->loadByName($session_geo_ip['code']);
                if(!isset($ship_data['name'])||($ship_data['dscr']=='')) {return false;} else { 
                        if($ship_data['name']=='2137') {
                           $cost = $config['moscow_ship'];
                        } else {
                           if($ship_data['name']=='2344') {
                               $cost = $config['piter_ship'];
                           } else {
                               $km = $ship_data['dscr'];
                               if($km<5) {
                                   $cost = $config['mkad_shipdo5'];
                               } else {
                                    if(($km>=5)&&($km<10)) {
                                          $cost = $config['mkad_shipdo10'];
                                     } else {
                                          $cost = $ship_data['dscr']*$config['mkad_shipb10'];
                                     }
                               }
                           }
                        }
                        
                        





                	/*$diap = array('20','15','10','5','3','1');
                	$costs = explode(';', $ship_data['costs']);
                	$costs = array_reverse($costs);
                	$count = 0;                  
                	foreach($diap as $value) {
                 	    if($sum<(int)$value) {
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
                        }*/
		        $result = Mage::getModel('shipping/rate_result');
		        $method = Mage::getModel('shipping/rate_result_method');
//Стоимость курьера
		        $method->setCarrier($this->_code);
		        $method->setCarrierTitle('Доставка службой Курьер.ру');
		        $method->setMethod('pickup1');
		        $method->setMethodTitle('Курьер');
                        $method->setCost($cost);
		        $method->setPrice($cost);    	
		        $result->append($method);
//Стоимость самовывоза
                        $method = Mage::getModel('shipping/rate_result_method');
		        $method->setCarrier($this->_code);
		        $method->setCarrierTitle('Самовывоз в пункте Курьер.ру г.Москва');
		        $method->setMethod('pickup2');
		        $method->setMethodTitle('Самовывоз в пункте Курьер.ру г.Москва');
                        $method->setCost($config['ship_sam']);
		        $method->setPrice($config['ship_sam']);    	
		        $result->append($method);

                        $addresses = explode(';', $ship_data['addressess']);
                        $count = 1;
                       /* foreach($addresses as $address) {
		             $method = Mage::getModel('shipping/rate_result_method');
                             $method->setCarrier($this->_code);
		             $method->setCarrierTitle('Курьер Доставка');
		             $method->setMethod('pickup'.$count);
		             $method->setMethodTitle($address);
                             $method->setCost($cost_sam);
		             $method->setPrice($cost_sam);    	
		             $result->append($method);
                             $count++;
                        }*/
                  }} else {
        return false;
    }
    return $result; 
} else {
return false;
}
	 }
 }
