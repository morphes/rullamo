<?php
class Medma_Background_Model_Mysql4_Background extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('background/background', 'background_id');
    }
}
