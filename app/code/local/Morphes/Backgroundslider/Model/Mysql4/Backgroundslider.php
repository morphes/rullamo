<?php
class Morphes_Backgroundslider_Model_Mysql4_Backgroundslider extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('backgroundslider/backgroundslider', 'background_id');
    }
}
