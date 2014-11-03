<?php

class Openstream_GeoIP_IndexController extends Mage_Core_Controller_Front_Action {

    public function getcitiesAction() {
	$session = Mage::getSingleton('core/session');
	$city_in_session = $session->getGeoIp(); 
        $store_config_cities = Mage::getStoreConfig('general/region');
        $city_model = Mage::getModel('directory/region');
        $cities = $store_config_cities['general_states'];
        $cities_ids = explode(",", $cities);
        echo '
<div id="selectcity">
<input type="hidden" id="city-code"/>
<h2 style="font-size: 13px;font-weight: bold;padding-left: 12px;padding-top: 4px;">Выберите Ваш город из списка или начинайте вводить его название в форму:<h2>
<div class="top-search">
    <div class="form-search" style="margin-left: 12px !important;">
        <label for="search"></label>
       <input value="'.$city_in_session['city'].'" name="one" id="one" type="text" value="" onkeydown="keydown(event.keyCode)" onkeyup="keyup(event.keyCode)" class="input-text" autocomplete="off">
		<button type="button" title="Выбрать" class="button btn-cart" onclick="getLocation()"><span><span>Выбрать</span></span></button>
		
        <div id="search_autocomplete" class="search-autocomplete" style="display: none;"></div>
    </div>

</div>
</div>
<div class="auto-suggest"></div>
<div style="clear:both"></div>
';
        foreach ($cities_ids as $city_id) {
            $city_object = $city_model->load($city_id);
            $city_name = explode(" (", $city_object->getDefaultName());
            echo "<div class='city-elem'>";
            echo "<font class='city-elem-name' onClick='changeCity(" . $city_id . ")'>" . $city_name[0] . "</font>";
            echo "</div>";
        }
        echo "
<script type='text/javascript'>
function setSel(num) {
tag = suggest.children('font.selected');
tag.removeClass('selected');
jQuery('.elem'+num).addClass('selected');
}


function changethis(city, code) {

suggest = jQuery('.auto-suggest');
tag = suggest.children('font.selected');
jQuery('#one').val(city);
jQuery('#city-code').val(code);  
setTimeout(function(){
				suggest.slideUp('fast');
			}, 100); 

}
//вниз - 40 вверх 38
function keydown(e) {

suggest = jQuery('.auto-suggest');

input = jQuery('#one');
if (e == 38 || e == 40) {
	   			var tag = suggest.children('font.selected');
	   			var new_tag = suggest.children('font:first-child'); 

	   			if (tag.length) { 


	   			   	if (e == 38) {
	   			   		if (suggest.children('font:first-child').hasClass('selected')) { 
		                	            new_tag = suggest.children('font:first-child'); 
		   				} else { 
		   				    new_tag = tag.prev('font');
		   				}
		   			} else { 
	   			   		if (suggest.children('font:last-child').hasClass('selected')) { 
		                	            new_tag = suggest.children('font:last-child'); 
		   				} else { 
		   				    new_tag = tag.next('font');
		   				}
		   			}
		   			tag.removeClass('selected'); 
		    	}
		    	new_tag.addClass('selected');  
	                input.val(new_tag.attr('rel'));
			var code = new_tag.attr('id');
                        jQuery('#city-code').val(code);    
		    	return;
			}
			if (e == 13 || e == 27) {  
	   			setTimeout(function(){
				suggest.slideUp('fast');
			}, 100); 
		    	return;
			}
}
</script>";
        exit();
    }

    public function changecityAction() {
        $city_id = $_GET['id'];
        $geo_ip = array();
        $geo_ip['code'] = $city_id;
        $geo_ip['city'] = Mage::getModel('directory/region')->load($city_id)->getName();
        $geo_ip['is_changed'] = 'yes';
        $ip = Mage::getModel('geoip/abstract')->getClientIP();
        $geoip_model = Mage::getModel('geoip/geoip')->getCollection()->addFieldToFilter('ip', $ip)->getFirstItem();
        $find_ip = $geoip_model->getData();      
        $model = Mage::getModel('geoip/geoip');
        if(!empty($find_ip)) {
            $founded_id = $find_ip['geoip_id'];
            $model->load($founded_id);
            $model->setCityCode($city_id);
            $model->save();
        } else {
            $model->setIp($ip);
            $model->setCityCode($city_id);
	    $model->save();
        } 
        $session = Mage::getSingleton('core/session');
        $session->unsGeoIp();             
        if($geo_ip['code']=='') die('no');
        $session->setGeoIp($geo_ip);        
        echo "ok";
        exit();
    }

    public function getcountriesAction() {
        $post = $_POST;
        $city_model = Mage::getModel('directory/region')->getResourceCollection()->addCountryFilter('RU')->load()->getData();        
        $cities = array();
        foreach ($city_model as $city) {
            $cities[$city['region_id']] = $city['default_name'];
        }        
        foreach ($cities as $code => $city) {
            $name = explode(' (', $city);
            if (isset($name[1])) {
                $only_cities[$code] = $name[0];
            } else {
                $stol_name = explode(" ", $city);
                if (!isset($stol_name[1]) or($name[0]=='Нижний Новгород')) {
                    $only_cities[$code] = $name[0];
                }
            }
        }
        $founded_cities = array();
        $word = $post['word'];
        $result = '';
        $count = 0;        
        foreach ($only_cities as $code => $city) {
            if (strpos(mb_convert_case(" " . $city, MB_CASE_LOWER, "UTF-8"), mb_convert_case($word, MB_CASE_LOWER, "UTF-8"))) {
                $count++;
                if ($count < 14) {
                    $result .= '<font class="city-element elem' . $count . '" rel="' . $city . '" id="' . $code . '" onmouseover="setSel(' . $count . ')" onClick=changethis("' . $city . '","' . $code . '")>' . $city . '</font>';
                }
            }
        }        
        echo $result;
        exit();
    }

}
