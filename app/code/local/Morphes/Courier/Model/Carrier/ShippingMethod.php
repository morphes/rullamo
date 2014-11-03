<?php
class Morphes_Courier_Model_Carrier_ShippingMethod extends Mage_Shipping_Model_Carrier_Abstract
{
	protected $_code = 'morphescourier';

	public function collectRates(Mage_Shipping_Model_Rate_Request $request)
	{

		// skip if not enabled
		if(Mage::getStoreConfig('carriers/'.$this->_code.'/active')) {
			return false; 
        }

$session = Mage::getSingleton('checkout/session');
$sum = 0;
foreach ($session->getQuote()->getAllVisibleItems() as $item) {
     $sum += $item->getQty()*$item->getWeight();
}
if($sum<20) {
    $session = Mage::getSingleton('core/session');
    $session_geo_ip = $session->getGeoIp();
    if(isset($session_geo_ip['code'])) {
                $ship_data = Mage::getModel('freaks_quotes/quote')->loadByName($session_geo_ip['code']);
                if(!isset($ship_data['name'])) {return false;} else {
                	$diap = array('20','15','10','5','3','1');
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
                        }
		        $result = Mage::getModel('shipping/rate_result');
		        $method = Mage::getModel('shipping/rate_result_method');
		        $method->setCarrier($this->_code);
		        $method->setCarrierTitle('Доставка курьером');
		        $method->setMethod('pickup0');
		        $method->setMethodTitle('Доставка курьером');
                        $method->setCost($cost_cour);
		        $method->setPrice($cost_cour+$cost_dop_km);    	
		        $result->append($method);
                        $addresses = explode(';', $ship_data['addressess']);
                        $count = 1;
                        foreach($addresses as $address) {
		             $method = Mage::getModel('shipping/rate_result_method');
                             $method->setCarrier($this->_code);
		             $method->setCarrierTitle('Im-Logistics Пункт Самовывоза');
		             $method->setMethod('pickup'.$count);
		             $method->setMethodTitle($address);
                             $method->setCost($cost_sam);
		             $method->setPrice($cost_sam);    	
		             $result->append($method);
                             $count++;
                        }
                  }} else {
        return false;
    }
    return $result; 
} else {
return false;
}
	 }
 }
