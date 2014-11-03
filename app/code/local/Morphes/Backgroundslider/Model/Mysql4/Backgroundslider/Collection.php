<?php
class Morphes_Backgroundslider_Model_Mysql4_Backgroundslider_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('backgroundslider/backgroundslider');
    }

}
