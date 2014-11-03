<?php
 
class Openstream_GeoIP_Model_Mysql4_Geoip_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        //parent::__construct();
        $this->_init('geoip/geoip');
    }

    public function getConfigCities() {
      
    }
}
