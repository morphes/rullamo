<?php
class Novaworks_FacebookConnect_Model_Mysql4_FacebookConnect extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('facebookconnect/facebookconnect', 'id');
    }
}