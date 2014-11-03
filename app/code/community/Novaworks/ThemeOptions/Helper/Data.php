<?php

class Novaworks_ThemeOptions_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function get_menu_config($optionString)
    {
        return Mage::getStoreConfig('themeoptions_general/menu/' . $optionString);
    }
}