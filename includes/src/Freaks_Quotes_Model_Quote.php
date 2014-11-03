<?php
class Freaks_Quotes_Model_Quote extends Mage_Core_Model_Abstract
{
 
    protected function _construct()
    {
        $this->_init('freaks_quotes/quote');
    }

    public function loadByName($name) {
        $read = Mage::getSingleton('core/resource')->getConnection('core_read');
        $result = $read->fetchAll("select * from freaks_quotes where name = ".$name);
        if(isset($result['0']))
           return $result['0'];
        else
           return false;
    }
}
