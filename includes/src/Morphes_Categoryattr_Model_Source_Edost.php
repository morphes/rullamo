<?php
/**
* Magento
*
* 08.04.2013
* Ìîäóëü äëÿ ðàñ÷åòà äîñòàâêè edost.ru
* Âåðñèÿ 1.07
* Àâòîð ÎÎÎ "Àéñäåí"
*
* Èñïîëüçóåòñÿ îíëàéí êàëüêóëÿòîð eDost.ru.
* Ãîðîä îòêóäà è êîìïàíèè äîñòàâêè çàäàþòñÿ â Ëè÷íîì êàáèíåòå íà ñåðâåðå eDost.ru (â íàñòðîéêàõ)
*
* @category    Edost
* @package     Edost_Rus
* @copyright   Copyright (c) 2011-2013 eDost.ru (http://www.eDost.ru)
*/

define("EDOST_PAGE", 'edost_calc_kln.php');
define("EDOST_ADDR", 'http://www.edost.ru/edost_calc_kln.php');
include_once(Mage::getBaseDir('lib')."/cdek/CalculatePriceDeliveryCdek.php");

class Edost_Rus_Model_Shipping_Carrier_Edost
extends Mage_Usa_Model_Shipping_Carrier_Abstract
implements Mage_Shipping_Model_Carrier_Interface
{

	protected $_code = 'edost';

	protected $_request = null;

	protected $_result = null;

	//äëÿ âûâîäà îòëàäî÷íîé èíôîðìàöèè
	public function PrintDebug($s)
	{
		$result = Mage::getModel('shipping/rate_result_error');
		$result->setCarrier('edost');
		$result->setCarrierTitle($this->getConfigData('title'));
		$result->setErrorMessage($s);
		return $result;
	}


	public function collectRates(Mage_Shipping_Model_Rate_Request $request)
	{


		if (!$this->getConfigFlag('active')) {
			return false;
		}
		if ($request->getDestCountry()) {
			$destCountry = $request->getDestCountry();
		}

		$zip = substr($request->getDestPostcode(),0,8);
		//echo "zip=(".$zip.")";
		//$request->getDestCity() - Ââåäåíûé Ãîðîä: "Âëàäèâîñòîê"
		//$request->getDestCountryId(); //RU

		//echo '<br>****destCountry='.$destCountry,'<br>****getDestCity='.$request->getDestCity().' getDestRegionId='.$request->getDestRegionId();
		//return $this->PrintDebug( '<br>****destCountry='.$request->getDestCountryId() ); //Âûâîä îòëàäî÷íîé èíôîðìàöèè

		$county_edost=array(1960=>'AU',1961=>'AT',1962=>'AZ',1963=>'AL',1964=>'DZ',1965=>'AS',1966=>'AI',1968=>'AO',1969=>'AD',1970=>'AG',1971=>'AN',1972=>'AR',1973=>'AM',1974=>'AW',1975=>'AF',1976=>'BS',1977=>'BD',1978=>'BB',1979=>'BH',1980=>'BY',1981=>'BZ',1982=>'BE',1983=>'BJ',1984=>'BM',1985=>'BG',1986=>'BO',1988=>'BA',1989=>'BW',1990=>'BR',1991=>'BN',1992=>'BF',1993=>'BI',1994=>'BT',1995=>'WF',1996=>'VU',1997=>'GB',1998=>'HU',1999=>'VE',2000=>'VG',2001=>'VI',2002=>'TL',2003=>'VN',2004=>'GA',2005=>'HT',2006=>'GY',2007=>'GM',2008=>'GH',2009=>'GP',2010=>'GT',2011=>'GN',2012=>'GQ',2013=>'GW',2014=>'DE',2016=>'GI',2017=>'HN',2018=>'HK',2019=>'GD',2020=>'GL',2021=>'GR',2022=>'GE',2023=>'GU',2024=>'DK',2026=>'DJ',2027=>'DM',2028=>'DO',2029=>'EG',2030=>'ZM',2031=>'CV',2032=>'ZW',2033=>'IL',2034=>'IN',2035=>'ID',2036=>'JO',2037=>'IQ',2038=>'IR',2039=>'IE',2040=>'IS',2041=>'ES',2042=>'IT',2043=>'YE',2044=>'KZ',2045=>'KY',2046=>'KH',2047=>'CM',2048=>'CA',2049=>'EQ',2050=>'QA',2051=>'KE',2052=>'CY',2053=>'KI',2054=>'CN',2055=>'CO',2056=>'KM',2057=>'CG',2058=>'CD',2059=>'KP',2060=>'KR',2062=>'CR',2063=>'CI',2064=>'CU',2065=>'KW',2066=>'CK',2067=>'KG',2069=>'LA',2070=>'LV',2071=>'LS',2072=>'LR',2073=>'LB',2074=>'LY',2075=>'LT',2076=>'LI',2077=>'LU',2078=>'MU',2079=>'MR',2080=>'MG',2081=>'YT',2082=>'MO',2083=>'MK',2084=>'MW',2085=>'MY',2086=>'ML',2087=>'MV',2088=>'MT',2089=>'MA',2090=>'MQ',2091=>'MH',2092=>'MX',2093=>'FM',2094=>'MZ',2095=>'MD',2096=>'MC',2097=>'MN',2098=>'MS',2099=>'MM',2100=>'NA',2101=>'NR',2102=>'KN',2103=>'NP',2104=>'NE',2105=>'NG',2106=>'NL',2107=>'NI',2108=>'NU',2109=>'NZ',2110=>'NC',2111=>'NO',2112=>'AE',2113=>'OM',2114=>'PK',2115=>'PW',2116=>'PA',2117=>'PG',2118=>'PY',2119=>'PE',2120=>'PL',2121=>'PT',2122=>'PR',2123=>'RE',2124=>'RW',2125=>'RO',2126=>'MP',2127=>'SV',2128=>'WS',2129=>'SM',2130=>'ST',2131=>'SA',2132=>'SZ',2134=>'SC',2136=>'SN',2137=>'VC',2138=>'KN',2139=>'KN',2140=>'LC',2145=>'SG',2146=>'SY',2147=>'SK',2148=>'SI',2149=>'SB',2150=>'SO',2152=>'SD',2153=>'SR',2154=>'US',2155=>'SL',2156=>'TJ',2157=>'TH',2158=>'PF',2159=>'TW',2160=>'TZ',2161=>'TG',2162=>'TO',2163=>'TT',2164=>'TV',2165=>'TN',2166=>'TM',2167=>'TC',2168=>'TR',2169=>'UG',2170=>'UZ',2171=>'UA',2172=>'UY',2174=>'FO',2175=>'FJ',2176=>'PH',2177=>'FI',2178=>'FK',2179=>'FR',2180=>'GF',2181=>'PF',2182=>'HR',2183=>'CF',2184=>'TD',2186=>'CZ',2187=>'CL',2188=>'CH',2189=>'SE',2191=>'LK',2192=>'EC',2193=>'ER',2194=>'EE',2195=>'ET',2196=>'ZA',2197=>'JM',2198=>'JP',0=>'RU');
		$country = array_search($request->getDestCountryId(), $county_edost);

        //echo '<br>****getDestCountryId='.$request->getDestCountryId().' - edost_id='.$country; //RU
        //echo '<br>****getDestRegionId='.$request->getDestRegionId(); //êîä ãîðîäà èëè ðåãèîíà edost

		$edost_calc = new edost_class ();
		//ïàðîëü è id ìàãàçèíà
		$edost_calc -> edost_id =  $this->getConfigData('id');
		$edost_calc -> edost_pswd =  $this->getConfigData('password');
		$edost_calc -> SetSiteUTF();
		$strah = $request->getPackageValue(); //îöåíêà

		$host = trim( strtolower($this->getConfigData('gateway_url')) );
		if (substr($host,0,7) == "http://") $host = substr($host,7,100);
		//if (substr($host,0,4) == "www.") $host = substr($host,4,100);

		if ($host=='')
			$edost_calc -> adr = EDOST_ADDR;
		else {
			$edost_calc -> adr = "http://".$host."/".EDOST_PAGE;
		}

		//îïðåäåëÿåì ñòðàíó è ãîðîä êëèåíòà
		$country_hide = false;
		if ($country_hide) $country=0; //ò.ê. ïî óìîë÷àíèþ ïðèñâàèâàåò US, åñëè íå âûáðàíà ñòðàíà
		if (!$country) $country=0; //RU

		if(isset($_SESSION['city_for_cart'])) {
             $to_city = Mage::getModel('directory/region')->load( $_SESSION['city_for_cart'] )->getCode(); //ãîðîä èëè ðåãèîí   
             $city_code =    Mage::getModel('directory/region')->load( $_SESSION['city_for_cart'] )->getCdekCode();       
         } else {
         	if($country==0) {
			$to_city = Mage::getModel('directory/region')->load( $request->getDestRegionId() )->getCode(); //ãîðîä èëè ðåãèîí
			$city_code =    Mage::getModel('directory/region')->load($request->getDestRegionId() )->getCdekCode();   
		} 
		else {
			$to_city = $country;
 $city_code = '0';
}

	}		
/*		if($country==0)
			$to_city = Mage::getModel('directory/region')->load( $request->getDestRegionId() )->getCode(); //ãîðîä èëè ðåãèîí
		else
			$to_city = $country;
*/
		//$weight = $this->getTotalNumOfBoxes($request->getPackageWeight());


/*        $packageParams = $request->getPackageParams();
        $height = $packageParams->getHeight();
        $width = $packageParams->getWidth();
        $length = $packageParams->getLength();
		echo "<br>*** height=$height,  width=$width,  length=$length";
*/		//echo $cart = Mage::helper('checkout/cart')->getCart()->getItemsCount();

/*		Mage::getSingleton('core/session', array('name'=>'frontend'));
		$cart = Mage::getModel('checkout/cart');
		$ids = $cart->getProductIds();
		print_r($ids);
		foreach ($ids as $i=>$productId) {
			echo "<br>id=$id ($i)";
			$product = Mage::getModel('catalog/product')->load($productId);
			$attributes = $product->getAttributes();

			foreach ($attributes as $attribute) {
				$attributeCode = $attribute->getAttributeCode();
				if ($attributeCode == 'length') {
					$value = $attribute->getFrontend()->getValue($product);
					echo $attributeCode . '-' . '-' . $value;
				}
			}

		}
*/
		$session = Mage::getSingleton('checkout/session');
		$output = "<br>";

		$weight = 0;
		$size_in = null;
		$weight_zero = false;
		$k = 0;         
		
		if(isset($_SESSION['product_for_cart'])) {
			$products_in_session[0] = $_SESSION['product_for_cart'];
		} else {
			$products_in_session = $session->getQuote()->getAllVisibleItems(); 				
		}		

		$sdbg = ''; //getAllItems() - íåëüçÿ, ò.ê. ïîÿâëÿþòñÿ äóáëè òîâàðîâ â êîðçèíå!
		foreach ($products_in_session as $item) {	
			if(isset($_SESSION['productid'])) {
				$productId = $_SESSION['productid'];
				$item = Mage::getModel('catalog/product')->load($productId); 
				$price_final = $item->getFinalPrice();
				$qty = '1';
			} else {
				$productId = $item->getProductId();
				$price_final = $item->getBaseCalculationPrice();
				$qty = $item->getQty();
			}
			$sdbg .= '<br>************* Sku='.$item->getSku()."<br>Name=".$item->getName()."<br>".$item->getDescription()."<br>BaseCalculationPrice=".
			$price_final."<br>ProductId=".$productId."<br>Weight=".$item->getWeight() . "<br>Qty=".$qty;		
            	//."<br><pre>".print_r($item, true)."</pre>"
		    //echo "<br>************************ <pre>".print_r($item, true)."</pre>";

			$weight += $item->getWeight() * $qty;
			if (!($item->getWeight() > 0)) $weight_zero = true;


			$product = Mage::getModel('catalog/product')->load($productId);
			$attributes = $product->getAttributes();

			$x = 0; $y = 0; $z = 0;
			foreach ($attributes as $attribute) {
				$attributeCode = $attribute->getAttributeCode();
				if ($attributeCode == 'length') $x = $attribute->getFrontend()->getValue($product);
				if ($attributeCode == 'width') $y = $attribute->getFrontend()->getValue($product);
				if ($attributeCode == 'height') $z = $attribute->getFrontend()->getValue($product);
			}

			$sdbg .= '<br>******'."<br>productId = $productId , length=$x , width=$y , height=$z";		
			//echo "<br>length=$x , width=$y , height=$z";
			if ($x>0 && $y>0 && $z>0) {
				// ðàñ÷åò ãàáàðèòîâ òîâàðà (åñëè äàííîãî òîâàðà íåñêîëüêî åäèíèö, òîãäà îíè âñå ñêëàäûâàþòñÿ â îäèí ÿùèê)
				$size_one = $edost_calc-> SumSizeOneGoods( $x, $y, $z, $item->getQty() );
				$size_in[] = array('X' => $size_one['X'], 'Y' => $size_one['Y'], 'Z' => $size_one['Z']);

				//$sdbg .= '<br>******'."<br>length=".$size_in[$k]['X']." , width=".$size_in[$k]['Y']." , height=".$size_in[$k]['Z'];

				$k++;
			}
			else $weight_zero = true;

		}

		if ($weight_zero) $weight = 0;

        //return $this->PrintDebug( '<br>****='.$sdbg.' = '.'<br>' ); //Âûâîä îòëàäî÷íîé èíôîðìàöèè

        //return $this->PrintDebug( '<br>****to_city='.$to_city.' = '.$weight ); //Âûâîä îòëàäî÷íîé èíôîðìàöèè

        //echo '<br>*** to_city='.$to_city.' , WEIGHT='.$weight.' , strah='.$strah;
        //echo '<br>*** to_city='.$to_city.' , WEIGHT='.$weight.' , strah='.$strah.' , id='.$this->getConfigData('id').' , ps='.$this->getConfigData('password');


		//Ñóììèðóåì ðàçìåðû ãðóçà
		
		$size = $edost_calc -> SumSize($size_in);
		$length	= $size['length'];
		$width	= $size['width'];
		$height	= $size['height'];
		//echo "<br>length=$length , width=$width , height=$height";
        //return $this->PrintDebug( '<br>****='."<br>length=$length , width=$width , height=$height".' = '.'<br>'."<br><pre>".print_r($size_in, true)."</pre>" ); //Âûâîä îòëàäî÷íîé èíôîðìàöèè
		$result = Mage::getModel('shipping/rate_result');
		$r = $edost_calc -> edost_calc($to_city, $weight, $strah, $length, $width, $height, $zip);

		if($city_code!='0') {
		$dop_tarif_mag = $edost_calc -> calculateMag($city_code, $weight, $length, $width, $height);
		$dop_tarif_ekonom = $edost_calc -> calculateEkonom($city_code, $weight, $length, $width, $height);
		if($dop_tarif_mag['result']) {
			$method = Mage::getModel('shipping/rate_result_method');
								$method->setCarrier('edost');
								$method->setCarrierTitle('Магистральный экспресс склад-склад');
								$method->setMethod('dop1');
								$method->setMethodTitle('Магистральный экспресс склад-склад');
								$method->setCost($dop_tarif_mag['result']['price']);
								$method->setPrice($dop_tarif_mag['result']['price']);    	
								$result->append($method);
		}
				if($dop_tarif_ekonom['result']) {
			$method = Mage::getModel('shipping/rate_result_method');
								$method->setCarrier('edost');
								$method->setCarrierTitle('Экономичный экспресс   склад-склад');
								$method->setMethod('dop2');
								$method->setMethodTitle('Экономичный экспресс   склад-склад');
								$method->setCost($dop_tarif_ekonom['result']['price']);
								$method->setPrice($dop_tarif_ekonom['result']['price']);    	
								$result->append($method);
		}
		
	}
		 
	



		//== Âûâîä ðåçóëüòàòîâ ============================================
		$st = '';
		if ( $r['qty_company']==0 ) {   
			switch($r['stat']) {
				// êîäû îøèáîê èç ãëàâíîãî çàïðîñà íà ñåðâåð edost
				case 0:	$st = ''; break;
				case 1:	$st = ''; break; //ÓÑÏÅÕ ðàñ÷åòà, íî ïîäõîäÿùèõ òàðèôîâ íå íàøëîñü
				case 2:	$st = Mage::helper('rus')->__("edost_err2"); break; //"Äîñòóï ê ðàñ÷åòó çàáëîêèðîâàí"
				case 3:	$st = Mage::helper('rus')->__("edost_err3"); break; //"Íå âåðíûå äàííûå ìàãàçèíà (ïàðîëü èëè èäåíòèôèêàòîð)"
				case 4:	$st = Mage::helper('rus')->__("edost_err4"); break; //"Íå âåðíûå âõîäíûå ïàðàìåòðû"
				case 5:	$st = Mage::helper('rus')->__("edost_err5"); break; //"Íå âåðíûé ãîðîä èëè ñòðàíà"
				case 6:	$st = Mage::helper('rus')->__("edost_err6"); break; //"Âíóòðåííÿÿ îøèáêà ñåðâåðà ðàñ÷åòîâ"
				case 7:	$st = Mage::helper('rus')->__("edost_err7"); break; //"Íå çàäàíû êîìïàíèè äîñòàâêè â íàñòðîéêàõ ìàãàçèíà"
				case 8:	$st = Mage::helper('rus')->__("edost_err8"); break; //"Ñåðâåð ðàñ÷åòà íå îòâå÷àåò"
				case 9:	$st = Mage::helper('rus')->__("edost_err9"); break; //"Ïðåâûøåí ëèìèò ðàñ÷åòîâ çà äåíü"
				case 10:$st = Mage::helper('rus')->__("edost_err10"); break; //"Íå âåðíûé ôîðìàò XML"
				case 11:$st = Mage::helper('rus')->__("edost_err11"); break; //"Íå óêàçàí âåñ"
				case 12:$st = Mage::helper('rus')->__("edost_err12"); break; //"Íå çàäàíû äàííûå ìàãàçèíà (ïàðîëü èëè èäåíòèôèêàòîð)"
				case 14:$st = Mage::helper('rus')->__("edost_err14"); break; //"Íàñòðîéêè ñåðâåðà íå ïîçâîëÿþò îòïðàâèòü çàïðîñ íà ðàñ÷åò"
				default:$st = Mage::helper('rus')->__("edost_err0"); //"Îøèáêà ðàñ÷åòà."
			};
if($city_code!=0) {$st = 15;}
			//if ( ($r['stat']>=2) and ($r['stat']<=12) ) {
			if ($this->getConfigData('hide_err')) $st = '';
			//}

			if (($st <> '')&&($st!='15')) {
				//Âûâîä îøèáêè - òàê ïðàâèëüíî ðàáîòàåò!!!
				$result = Mage::getModel('shipping/rate_result_error');
				$result->setCarrier('edost');
				$result->setCarrierTitle($this->getConfigData('title'));
				$result->setErrorMessage($st);
				return $result;
			}

			//<br>Â äàííûé ãîðîä àâòîìàòè÷åñêèé ðàñ÷åò äîñòàâêè íå îñóùåñòâëÿåòñÿ. Î âîçìîæíîñòè è ñòîèìîñòè äîñòàâêè, ïîæàëóéñòà, óòî÷íèòå ó ìåíåäæåðà.
			if ($this->getConfigData('show_msg')) {
//				$st = '';











                 //курьер
				$mba = 'blankshippingmodule';
				if(Mage::getStoreConfig('carriers/'.$mba.'/active')) {	
					$config =  Mage::getStoreConfig('carriers/'.$mba);
					$session = Mage::getSingleton('checkout/session');
					$sum = 0;
					foreach ($products_in_session as $item) {
						if(isset($_SESSION['productid'])) {
							$productId = $_SESSION['productid'];
							$item = Mage::getModel('catalog/product')->load($productId); 
							$price_final = $item->getFinalPrice();
							$qty = '1';
						} else {
							$productId = $item->getProductId();
							$price_final = $item->getBaseCalculationPrice();
							$qty = $item->getQty();
						}						
						$sum += $qty*$item->getWeight();
					}					
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
										$cost = $config['mkad_shipdo5']+$config['piter_ship'];
									} else {
										if(($km>=5)&&($km<10)) {
											$cost = $config['mkad_shipdo10']+$config['piter_ship'];
										} else {
											$cost = $ship_data['dscr']*$config['mkad_shipb10']+$config['piter_ship'];
										}
									}
								}
							}            
							$result = Mage::getModel('shipping/rate_result');
							$method = Mage::getModel('shipping/rate_result_method');
                            //Стоимость курьера
							$method->setCarrier('edost');
							$method->setCarrierTitle('Курьерская доставка');
							$method->setMethod('pickup1');
							$method->setMethodTitle('');
							$method->setCost($cost);
							$method->setPrice($cost);    	
							$result->append($method);
//Стоимость 
							if($sum<25) {
								$method = Mage::getModel('shipping/rate_result_method');
								$method->setCarrier('freeshipping');
								$method->setCarrierTitle('Самовывоз в пункте Курьер.ру г.Москва');
								$method->setMethod('pickup2');
								$method->setMethodTitle('Самовывоз в пункте Курьер.ру г.Москва');
								$method->setCost($config['ship_sam']);
								$method->setPrice($config['ship_sam']);    	
								$result->append($method);
							}
						}
					}					
				}

				$cour = 'morphescourier';
				if(Mage::getStoreConfig('carriers/'.$cour.'/active')) {
					$session = Mage::getSingleton('checkout/session');
					$sum = 0;
					foreach ($session->getQuote()->getAllVisibleItems() as $item) {
						if(isset($_SESSION['productid'])) {
							$productId = $_SESSION['productid'];
							$item = Mage::getModel('catalog/product')->load($productId); 
							$price_final = $item->getFinalPrice();
							$qty = '1';
						} else {
							$productId = $item->getProductId();
							$price_final = $item->getBaseCalculationPrice();
							$qty = $item->getQty();
						}						
						$sum += $qty*$item->getWeight();
					}
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
                	  if($ship_data['dscr']=='0') $cost_dop_km = 500;
                	  /*$result = Mage::getModel('shipping/rate_result');
                	  $method = Mage::getModel('shipping/rate_result_method');
                	  $method->setCarrier('edost');
                	  $method->setCarrierTitle('Доставка курьером');
                	  $method->setMethod('pickup0');
                	  $method->setMethodTitle('Доставка курьером');
                	  $method->setCost($cost_cour);
                	  $method->setPrice($cost_cour+$cost_dop_km);    	
                	  $result->append($method);*/
                	  $addresses = explode(';', $ship_data['addressess']);
                	  $count = 1;
                	  if($sum<25) {

                	  	foreach($addresses as $address) {
                	  		$method = Mage::getModel('shipping/rate_result_method');
                	  		$method->setCarrier('freeshipping');
                	  		$method->setCarrierTitle('Im-Logistics Пункт Самовывоза');
                	  		$method->setMethod('pickup'.$count);
                	  		$method->setMethodTitle($address.' Logistic');
                	  		$method->setCost($cost_sam);
                	  		$method->setPrice($cost_sam);    	
                	  		$result->append($method);
                	  		$count++;
                	  	}
                	  }
                	}

                }
            }























            $rate = Mage::getModel('shipping/rate_result_method');
            $rate->setCarrier('edost');
            $rate->setCarrierTitle($this->getConfigData('title'));
            $rate->setMethod('error');
            $rate->setMethodTitle( $this->getConfigData('specificerrmsg') );
	            $rate->setCost(0); //
    	        $rate->setPrice(0); //öåíà âûâîäèìàÿ (cost+handling)

    	      //  $result->append($rate);

    	    } 

    	}
    	else {


			//ïåðåâîäèì èç ðóáëåé â âàëþòó ìàãàçèíà
    		$baseCurr = Mage::app()->getStore()->getBaseCurrency();
			//echo "<br><pre>",print_r($baseCurr),"</pre>";

    		$allCurr = Mage::app()->getStore()->getAvailableCurrencyCodes ();
			//echo "<br><pre>",print_r($allCurr),"</pre>";
    		if ( !in_array('RUB', $allCurr) or ($baseCurr->getRate('RUB')==0) ) {
				//Âûâîä îøèáêè - òàê ïðàâèëüíî ðàáîòàåò!!!
    			$result = Mage::getModel('shipping/rate_result_error');
    			$result->setCarrier('edost');
    			$result->setCarrierTitle($this->getConfigData('title'));
    			$result->setErrorMessage('eDost: Not Available Currency RUB!');
    			return $result;
    		}

    		if($baseCurr->getCode() != 'RUB') {
    			$rateToBase = $baseCurr->getRate('RUB');
    		}

    		for ( $i = 1; $i <= $r['qty_company']; $i++ ) {

    			if ($r['name'.$i]=='') $q = ''; else $q = $r['name'.$i];
    			if ($r['day'.$i]=='&nbsp;') $q.=''; else $q.= ($q=='')?$r['day'.$i]:(', '.$r['day'.$i]);

    			$priceCurrency = $r['price'.$i];

    			if($baseCurr->getCode() != 'RUB') {
    				$priceCurrency = round( $priceCurrency / $rateToBase, 2 );
    			}

    			$strDelivery = $r["company".$i];
    			if ($q<>'') $strDelivery .= ' ('.$q.')';






    			$session = Mage::getSingleton('core/session');
    			$session_geo_ip = $session->getGeoIp();
    			if(isset($session_geo_ip['code'])) {     				
    				if($session_geo_ip['code']=='2137') {
    					if(strpos($strDelivery, 'СДЭК')===false) {
    						$rate = Mage::getModel('shipping/rate_result_method');
    						$rate->setCarrier('edost');
    						$rate->setCarrierTitle($this->getConfigData('title'));
    						$rate->setMethod($r['id'.$i].'-'.$r['strah'.$i]);
    						$rate->setMethodTitle($strDelivery);
				            $rate->setCost($priceCurrency); //
							$rate->setPrice($priceCurrency); //öåíà âûâîäèìàÿ (cost+handling)
							$result->append($rate);            
						} 
					} else {
						$rate = Mage::getModel('shipping/rate_result_method');
						$rate->setCarrier('edost');
						$rate->setCarrierTitle($this->getConfigData('title'));
						$rate->setMethod($r['id'.$i].'-'.$r['strah'.$i]);
						$rate->setMethodTitle($strDelivery);
						$rate->setCost($priceCurrency); //
						$rate->setPrice($priceCurrency); //öåíà âûâîäèìàÿ (cost+handling)
						$result->append($rate);
					}
				}










			}



                 //курьер
			$mba = 'blankshippingmodule';
			if(Mage::getStoreConfig('carriers/'.$mba.'/active')) {	
				$config =  Mage::getStoreConfig('carriers/'.$mba);
				$session = Mage::getSingleton('checkout/session');
				$sum = 0;
				foreach ($session->getQuote()->getAllVisibleItems() as $item) {
					$sum += $item->getQty()*$item->getWeight();
				}					
				$session = Mage::getSingleton('core/session');
				$session_geo_ip = $session->getGeoIp();		

				if(isset($session_geo_ip['code'])) { 
					$ship_data = Mage::getModel('freaks_quotes/quote')->loadByName($session_geo_ip['code']);

					if(!isset($ship_data['name'])||($ship_data['dscr']=='')) {
							 //return false;
					} else { 
						if($ship_data['name']=='2137') {
							$cost = $config['moscow_ship'];
						} else {
							if($ship_data['name']=='2344') {
								$cost = $config['piter_ship'];
							} else {
								$km = $ship_data['dscr'];
								if($km<5) {
									$cost = $config['mkad_shipdo5']+$config['piter_ship'];
								} else {
									if(($km>=5)&&($km<10)) {
										$cost = $config['mkad_shipdo10']+$config['piter_ship'];
									} else {
										$cost = $ship_data['dscr']*$config['mkad_shipb10']+$config['piter_ship'];
									}
								}
							}
						}            
						//$result = Mage::getModel('shipping/rate_result');
						$method = Mage::getModel('shipping/rate_result_method');
                            //Стоимость курьера
						$method->setCarrier('edost');
						$method->setCarrierTitle('Курьерская доставка');
						$method->setMethod('pickup1');
						$method->setMethodTitle('');
						$method->setCost($cost);
						$method->setPrice($cost);    	
						$result->append($method);
//Стоимость самовывоза
						if($sum<25) {
							$method = Mage::getModel('shipping/rate_result_method');
							$method->setCarrier('freeshipping');
							$method->setCarrierTitle('Самовывоз в пункте Курьер.ру г.Москва');
							$method->setMethod('pickup2');
							$method->setMethodTitle('Самовывоз в пункте Курьер.ру г.Москва');
							$method->setCost($config['ship_sam']);
							$method->setPrice($config['ship_sam']);    	
							$result->append($method);
						}
					}
				}					
			}

			$cour = 'morphescourier';
			if(Mage::getStoreConfig('carriers/'.$cour.'/active')) {
				$session = Mage::getSingleton('checkout/session');
				$sum = 0;
				foreach ($session->getQuote()->getAllVisibleItems() as $item) {
					$sum += $item->getQty()*$item->getWeight();
				}
				$session = Mage::getSingleton('core/session');
				$session_geo_ip = $session->getGeoIp();
				if(isset($session_geo_ip['code'])) {
					$ship_data = Mage::getModel('freaks_quotes/quote')->loadByName($session_geo_ip['code']);
			if(!isset($ship_data['name'])) { //return false;
			} else {
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
                if($ship_data['dscr']=='') {
                	$cost_dop_km = 500;

                	$method = Mage::getModel('shipping/rate_result_method');
                	$method->setCarrier('edost');
                	$method->setCarrierTitle('Курьерская доставка');
                	$method->setMethod('pickup1');
                	$method->setMethodTitle('');
                	$method->setCost($cost_dop_km);
                	$method->setPrice($cost_dop_km);    	
                	$result->append($method);
                }

                $addresses = explode(';', $ship_data['addressess']);
                $count = 1;
                if($sum<25) {
                	foreach($addresses as $address) {
                		$method = Mage::getModel('shipping/rate_result_method');
                		$method->setCarrier('freeshipping');
                		$method->setCarrierTitle('Im-Logistics Пункт Самовывоза');
                		$method->setMethod('pickup'.$count);
                		$method->setMethodTitle($address.' Logistic');
                		$method->setCost($cost_sam);
                		$method->setPrice($cost_sam);    	
                		$result->append($method);
                		$count++;
                	}
                }
            
        }
    }
}
}
$_SESSION['result'] = $result;
//Mage::log($result, true, 'edost.log');
$_SESSION['shipping_inf_edost'] = $result-> getRatesByCarrier('edost');
$_SESSION['shipping_inf_free'] = $result-> getRatesByCarrier('freeshipping');

return $result;
}

/*    public function getResult()
    {
        return $this->_result;
    }
*/
/*    protected function _getQuotes()
    {
        return $this->_getXmlQuotes();
    }*/

/*    protected function _setFreeMethodRequest($freeMethod)
    {
        $r = $this->_rawRequest;

        $r->setFreeMethodRequest(true);
        $weight = $this->getTotalNumOfBoxes($r->getFreeMethodWeight());
        $freeWeight = round(max(1, $weight),0);
        $r->setWeight($freeWeight);
        $r->setService($freeMethod);
    }
*/


    /**
     * Get allowed shipping methods
     *
     * @return array
     */
    public function getAllowedMethods()
    {
    	$allowed = explode(',', $this->getConfigData('allowed_methods'));
    	$arr = array();
    	foreach ($allowed as $k) {
    		$arr[$k] = $this->getCode('service', $k);
    	}
    	return $arr;
    }

    public function isStateProvinceRequired()
    {
    	return true;
    }


    public function isZipCodeRequired($countryId = null)
    {
    	return false;
    }

    protected function _doShipmentRequest(Varien_Object $request)
    {
    	return;
    }


}


define("EDOST_PSWD", ""); define("EDOST_ID", ""); //ïàðîëü è id ìàãàçèíà

class edost_class{

var $kod = 0; //êîäèðîâêà åñëè 1, òî íàäî win_utf8 (âõîäÿùèå ïàðàìåòðû)
var $site_win = true; //êîäèðîâêà åñëè 1, òî íàäî win_utf8 (âõîäÿùèå ïàðàìåòðû)
var $index, $curElem;
var $status = 0;
var $parser;
var $rz;
var $rz_office;
var $warning;
var $edost_id = EDOST_ID;
var $edost_pswd = EDOST_PSWD;
var	$adr = EDOST_ADDR;

public static $NowErr = false;

function crc16($data)
{
	$crc = 0xFFFF;
	for ($i = 0; $i < strlen($data); $i++) {
		$x = (($crc >> 8) ^ ord($data[$i])) & 0xFF;
		$x ^= $x >> 4;
		$crc = (($crc << 8) ^ ($x << 12) ^ ($x << 5) ^ $x) & 0xFFFF;
	}
	return $crc;
}

	//== Ïåðåâîä èç êîäèðîâêè UTF8 â WIN ==============================
function utf8_win($s){
	$out = "";
	$c1 = "";
	$byte2 = false;
	for ($c = 0; $c < strlen($s); $c ++){
		$i = ord($s[$c]);
		if ($i <= 127) $out .= $s[$c];
		if ($byte2) {
			$new_c2 = ($c1 & 3) * 64 + ($i & 63);
			$new_c1 = ($c1 >> 2) & 5;
			$new_i = $new_c1 * 256 + $new_c2;
			if ($new_i == 1025){
				$out_i = 168;
			}else {
				if ($new_i == 1105)	$out_i = 184;
				else $out_i = $new_i-848;
			}
			$out .= chr($out_i);
			$byte2 = false;
		}
		if (($i >> 5) == 6) {
			$c1 = $i;
			$byte2 = true;
		}
	}
	return $out;
}

	//== Ïåðåâîä èç êîäèðîâêè WIN â UTF8 ==============================
function win_utf8($s){
	$utf = "";
	for($i = 0; $i < strlen($s); $i++){
		$donotrecode = false;
		$c = ord(substr($s, $i, 1));
		if ($c == 0xA8) $res = 0xD081;
		elseif ($c == 0xB8) $res = 0xD191;
		elseif ($c < 0xC0) $donotrecode = true;
		elseif ($c < 0xF0) $res = $c + 0xCFD0;
		else $res = $c + 0xD090;
		$c = ($donotrecode) ? chr($c) : (chr($res >> 8) . chr($res & 0xff));
		$utf .= $c;
	}
	return $utf;
}

function strtoupper_ru($content) {
	$func = function_exists('mb_strtoupper') ? 'mb_strtoupper' : 'ToUpper';
	$s = $func($content, 'utf-8');
	return $s;
}

	//== Ïåðåêîäèðîâêà â UTF äëÿ AJAX =================================
function decode($s){
	if ($this -> kod==1) $s = $this -> win_utf8($s);
	return $s;
}

public static function my_error_handler($code, $msg, $file, $line) {
		//echo "****Ïðîèçîøëà îøèáêà $msg ($code)******<br>";
	self::$NowErr = true;
	return true;
}

	//== Çíà÷èò ñàéò ïåðåäàåò â êîäèðîâêå WIN =========================
function SetSiteWin(){
	$this -> site_win = true;
}

	//== Çíà÷èò ñàéò ïåðåäàåò â êîäèðîâêå UTF8 =========================
function SetSiteUTF(){
	$this -> site_win = false;
}

	//== Ñîçäàíèå POST çàïðîñà ========================================
function ManualPOST ($url, $post) {
       // $url = 'http://edost.ru//edost_calc_kln.php';
       // $post = 'to_city=839&strah=17550&id=2387&p=vN4g7IgFKMSbpOMGSL9cW6S15YuxxnX5&weight=73.5&ln=51&wd=62&hg=116&zip=';
	$parse_url = parse_url($url);
	$path = $parse_url["path"];

	$host= $parse_url["host"];
	$rez = "";

	self::$NowErr = false;
	set_error_handler(array('edost_class','my_error_handler'));

		$fp = fsockopen($host, 80, $errno, $errstr, 4); //4 - ìàêñ. âðåìÿ çàïðîñà

		restore_error_handler();

		if ($errno == 13) return('Err14'); //Îøèáêà: Íàñòðîéêè ñåðâåðà íå ïîçâîëÿþò îòïðàâèòü çàïðîñ íà ðàñ÷åò
		if (self::$NowErr) return(''); //Îøèáêà
		//Mage::log($post, null, 'edostlog.log');
		if ($fp){
			$out =	"POST ".$path." HTTP/1.0\n".
			"Host: ".$host."\n".
			"Referer: ".$url."\n".
			"Content-Type: application/x-www-form-urlencoded\n".
			"Content-Length: ".strlen($post)."\n\n".
			$post."\n\n";

			fputs($fp, $out);

			$q = 0;
			while($gets=fgets($fp,512)) {
				$rez.= $gets;
				$q++;
			}

			fclose($fp);

			$rez=stristr($rez, "<?xml version="); //îòðåçàåì çàãîëîâîê

		}
		Mage::log($rez, true, 'edostlog.log');
		return($rez);
	}


	//== Ôóíêöèè ðàçáîðà XML ==========================================
	function start_tag($parser,$name,$attrs) {
		//echo "<b>Element: $name</b><br>";	// èìÿ ýëåìåíòà
		switch($name) {
			case 'tarif':	$this->curElem = array(); break;
			case 'office':	$this->curElem = array(); break;
			default:		$this->index = $name; break;
		};
	}

	function end_tag($parser,$name) {
		//echo "<b>element:: $name</b><br>"; // èìÿ ýëåìåíòà
		if ((is_array($this->curElem)) && ($name=='tarif')) {
			$this->rz[] = $this ->curElem;
			$this->curElem = null;
		};
		if ((is_array($this->curElem)) && ($name=='office')) {
			$this->rz_office[] = $this->curElem; //$this->curElem['to_tarif'];
			$this->curElem = null;
		};
		$this ->index = null;
	}

	function char_data($parser,$data){
		if ((is_array($this->curElem)) && ($this->index)) {
			if (array_key_exists($this->index,$this->curElem)) $this->curElem[$this->index]=$this->curElem[$this->index].$data; else $this->curElem[$this->index] = $data;
		}
		if ( (strlen(trim($data)) > 0) and ($this->index=='stat') ) $this->status = $data;	//ñòàòóñ ðåçóëüòàòà
		if ( (strlen(trim($data)) > 0) and ($this->index=='warning') ) $this->warning[] = $data;	//ïðåäóïðåæäåíèå
		//if (strlen(trim($data)) > 0) { echo 'String: '.$data.' - '.$this ->index.'<br>'; }  // âûâîäèì ñòðîêó
	}


	//== Ðàñ÷åò ñòîèìîñòè äîñòàâêè ====================================
	function edost_calc($to_city, $weight, $strah, $length, $width, $height, $zip='') {

		if ( !($weight>0) ) return( array('qty_company'=>0,'stat'=>11) );
		if ($to_city == '') return( array('qty_company'=>0,'stat'=>0) );

		$this->status = 0;
		$this->index = null;
		$this->rz = array();
		$this->rz_office = array();
		$this->curElem = null;
		$this->warning = array();

		$to_city_id = trim(preg_replace("/[^0-9]/i","", substr($to_city,0,50)));
		if ( ($to_city==$to_city_id) and ($to_city<>'') ) {
			//Ïðîâåðÿåì, ìîæåò ïåðåäàëè êîä ãîðîäà
			if ( ($to_city_id<1) and ($to_city_id>2279) )
				return(array('qty_company'=>0,'stat'=>0)); //Íå ïîêàçûâàåì îøèáêó, íî íå ñ÷èòàåì
		}
		else {
			return(array('qty_company'=>0,'stat'=>0 )); //Íå ïîêàçûâàåì îøèáêó, íî íå ñ÷èòàåì
		}

		$to_city = str_replace(' ', '%20', $to_city);

		$xml = $this -> ManualPOST ($this->adr, "to_city=$to_city&strah=$strah&id={$this->edost_id}&p={$this->edost_pswd}&weight=$weight&ln=$length&wd=$width&hg=$height&zip=$zip");


		//echo '<br>=======<br>('.$xml.')';

		if ($xml=='Err14') return(array('qty_company'=>0,'stat'=>14));
		if ($xml=='') return(array('qty_company'=>0,'stat'=>8));

		$code = "UTF-8"; //êîäèðîâêà xml
		$this->parser = xml_parser_create($code);
		xml_set_object($this->parser,$this);
		xml_set_element_handler($this->parser,'start_tag','end_tag');
		xml_set_character_data_handler($this->parser,'char_data');
		xml_parser_set_option($this->parser,XML_OPTION_CASE_FOLDING,false); //åñëè folding âêëþ÷åí, òî âñå èìåíà òåãîâ áóäóò ïåðåâåäåíû â âåðõíèé ðåãèñòð

		if (!xml_parse($this->parser,$xml,true)) {
			//die(sprintf('Îøèáêà XML: %s â ñòðîêå %d', xml_error_string(xml_get_error_code($this->parser)),xml_get_current_line_number($this->parser)));
			return(array('qty_company'=>0,'stat'=>10));
		}
		xml_parser_free($this->parser);

		//echo '--('.$this -> status.')';
		if ($this -> status <>1) return( array('qty_company'=>0,'stat'=>$this -> status) );


		$rez = array();
		$i=0;
		$rez['qty_company']=count($this->rz);
		$rez['stat']=$this -> status;

		//ïðåäóïðåæäåíèÿ
		$rez['warning'] = $this -> warning;
		//echo "<br><pre>".print_r($this -> warning, true)."</pre>";
		//echo "<br>------<pre>".print_r($this->rz, true)."</pre>------";

		foreach($this->rz as $n){
			$i++;
			$rez['price'.$i] = preg_replace("/[^0-9.]/i","", substr(trim($n['price']),0,11) );	//öåíà äîñòàâêè

			if (!isset($n['day'])) $n['day'] = '&nbsp;';
			$rez['day'.$i] = substr(trim($n['day']),0,60);		//ñðîê äîñòàâêè
			$rez['company'.$i] = substr($n['company'],0,80);	//íàçâàíèå êîìïàíèè (â 2 ðàçà áîëüøå èç-çà UTF)
			if (!isset($n['name'])) $n['name'] = '';
			$rez['name'.$i] = substr(trim($n['name']),0,80);	//íàçâàíèå òàðèôà (â 2 ðàçà áîëüøå èç-çà UTF)
			$rez['strah'.$i] = substr(trim($n['strah']),0,1);	//1 - ñî ñòðàõîâêîé, 0 - áåç
			$rez['id'.$i] = substr(trim($n['id']),0,5);			//id

			if (!isset($n['pricecash'])) $n['pricecash'] = -1;
			$rez['pricecash'.$i] = preg_replace("/[^0-9.-]/i","", substr(trim($n['pricecash']),0,9) ); //ñóììà äîñòàâêè ïðè íàëîæåííîì ïëàòåæå

			if (!isset($n['transfer'])) $n['transfer'] = 0;
			$rez['transfer'.$i] = preg_replace("/[^0-9.]/i","", substr(trim($n['transfer']),0,9) ); //ñóììà äîïëàòû çà íàëîæêó ïðè ïîëó÷åíèè

			if ($this->site_win) {
				$rez['day'.($i)]=$this->utf8_win($rez['day'.($i)]);
				$rez['company'.($i)]=$this->utf8_win($rez['company'.($i)]);
				$rez['name'.($i)]=$this->utf8_win($rez['name'.($i)]);
			}
			//echo '<br>price='.$n['price'].' , id='.$n['id'].' , strah='.$n['strah'].' , day='.$n['day'].' , company='.$n['company'];

		};

		return($rez);
	}


	//Ñóììèðóåì ðàçìåðû ãðóçà äëÿ âû÷èñëåíèÿ îáúåìíîãî âåñà
	function SumSize($a) {

		$n = count($a); // êîëëè÷åñòâî òîâàðîâ ñ ãàáàðòàìè (åñëè îäíîãî òîâàðà 3 øò. òîãäà êîëëè÷åñòâî áóäåò íà 2 áîëüøå!!!)

		for ($i3=1; $i3<$n; $i3++) {
			// îòñîðòèðîâàòü ðàçìåðû ïî óáûâàíèþ
			for ($i2=$i3-1; $i2<$n; $i2++) {
				for ($i=0; $i<=1; $i++) {
					if ($a[$i2]['X'] < $a[$i2]['Y']) {
						$a1 = $a[$i2]['X'];
						$a[$i2]['X'] = $a[$i2]['Y'];
						$a[$i2]['Y'] = $a1;
					};
					if ( ($i==0) and ($a[$i2]['Y'] < $a[$i2]['Z']) ) {
						$a1 = $a[$i2]['Y'];
						$a[$i2]['Y'] = $a[$i2]['Z'];
						$a[$i2]['Z'] = $a1;
					}
				}
				$a[$i2]['Sum'] = $a[$i2]['X'] + $a[$i2]['Y'] + $a[$i2]['Z']; // ñóììà ñòîðîí
			}

			// îòñîðòèðîâàòü ãðóçû ïî âîçðàñòàíèþ
			for ($i2=$i3; $i2<$n; $i2++)
				for ($i=$i3; $i<$n; $i++)
					if ($a[$i-1]['Sum'] > $a[$i]['Sum']) {
						$a2 = $a[$i];
						$a[$i] = $a[$i-1];
						$a[$i-1] = $a2;
					}

			// ðàñ÷èòàòü ñóììó ãàáàðèòîâ äâóõ ñàìûõ ìàëåíüêèõ ãðóçîâ
					if ($a[$i3-1]['X'] > $a[$i3]['X']) $a[$i3]['X'] = $a[$i3-1]['X'];
					if ($a[$i3-1]['Y'] > $a[$i3]['Y']) $a[$i3]['Y'] = $a[$i3-1]['Y'];
					$a[$i3]['Z'] = $a[$i3]['Z'] + $a[$i3-1]['Z'];
			$a[$i3]['Sum'] = $a[$i3]['X'] + $a[$i3]['Y'] + $a[$i3]['Z']; // ñóììà ñòîðîí
		}

		//Ðåçóëüòàò
		//echo '<br>( '.Round($a[$n-1]['X'],2).'  '.Round($a[$n-1]['Y'],2).'  '.Round($a[$n-1]['Z'],2).' )';

		return( array(
			'length'=>Round($a[$n-1]['X'],2),
			'width'=>Round($a[$n-1]['Y'],2),
			'height'=>Round($a[$n-1]['Z'],2)) );
	}


	//Ñóììèðóåì ðàçìåðû ãðóçà äëÿ âû÷èñëåíèÿ îáúåìíîãî âåñà ó îäèíàêîâîãî òîâàðà
	//Âõîä: Äëèíà, Øèðèíà, Âûñîòà , Êîë-âî
	function SumSizeOneGoods($xi, $yi, $zi, $qty) {
		// îòñîðòèðîâàòü ãðóçû ïî âîçðàñòàíèþ
		$ar = array($xi, $yi, $zi);
		sort( $ar );
		//print_r($ar);

		if ($qty<=1) return ( array('X'=>$ar[0], 'Y'=>$ar[1], 'Z'=>$ar[2]) );

		$x1 = 0;
		$y1 = 0;
		$z1 = 0;
		$l = 0;

		$max1 = floor(Sqrt($qty));

		for ( $y=1; $y <= $max1; $y++ ) {
			$i = ceil($qty/$y);
			$max2 = floor(Sqrt($i));

			for ( $z=1; $z <= $max2; $z++ ) {
				$x = ceil($i/$z);

				$l2 = $x*$ar[0] + $y*$ar[1] + $z*$ar[2];
				if ( ($l == 0) or ($l2 < $l) ) {
					$l = $l2;
					$x1 = $x;
					$y1 = $y;
					$z1 = $z;
				}
			}
		}

		// êîëè÷åñòâî òîâàðîâ ïî ñòîðîíàì
		//echo '<br>êîëè÷åñòâî òîâàðîâ ïî ñòîðîíàì: x1=', $x1,' , y1=', $y1,' , z1=', $z1;

		// èòîãîâûå ãàáàðèòû òîâàðà
		//echo '<br>èòîãîâûå ãàáàðèòû òîâàðà: x1=', $x1*$ar[0],' , y1=', $y1*$ar[1],' , z1=', $z1*$ar[2];

		return (array ('X'=>$x1*$ar[0], 'Y'=>$y1*$ar[1], 'Z'=>$z1*$ar[2]) );
	}

	function calculateMag($to_city, $weight, $length, $width, $height) {		 
	    $calc = new CalculatePriceDeliveryCdek();    
	    $calc->setAuth('741a658ed104752ff5a4ba27950ba72b', 'e95869780addf13d9753221b36b7bd00');
	    $calc->setSenderCityId('44');    
	    $calc->setReceiverCityId('137');    
	    $tarifList = array(
	        '0' => '5',
	        '1' => '62'
	    );    
	    $calc->setTariffId('62');        
	     $calc->addTariffPriority($tarifList);    
	    $calc->addGoodsItemBySize('70', '115', '40', '61');   
	    if ($calc->calculate() === true) {
	        $res = $calc->getResult();
	        return $res;
	    } else {
	        $err = $calc->getError();
	        return $err;
	    }
	}

	function calculateEkonom($to_city, $weight, $length, $width, $height) {		 
	    $calc = new CalculatePriceDeliveryCdek();    
	    $calc->setAuth('741a658ed104752ff5a4ba27950ba72b', 'e95869780addf13d9753221b36b7bd00');
	    $calc->setSenderCityId('44');    
	    $calc->setReceiverCityId('137');    
	    $tarifList = array(
	        '0' => '5',
	        '1' => '62'
	    );    
	    $calc->setTariffId('5');        
	     $calc->addTariffPriority($tarifList);    
	    $calc->addGoodsItemBySize('70', '115', '40', '61');   
	    if ($calc->calculate() === true) {
	        $res = $calc->getResult();
	        return $res;
	    } else {
	        $err = $calc->getError();
	        return $err;
	    }
	}

}
