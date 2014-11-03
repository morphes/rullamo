<?php
 
class Openstream_GeoIP_Model_GeoIP extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('geoip/geoip');
    }
}
