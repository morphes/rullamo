<?php
 
class Openstream_GeoIP_Model_Mysql4_Geoip extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {   

        $this->_init('geoip/geoip', 'geoip_id');
    }
}
