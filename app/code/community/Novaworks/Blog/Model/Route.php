<?php
/**
 * Novaworks Blog Extension
 * @version   1.0 12.09.2013
 * @author    Novaworks http://www.novaworks.net <info@novaworks.net>
 * @copyright Copyright (C) 2010 - 2013 Novaworks
 */

class Novaworks_Blog_Model_Route extends Mage_Core_Model_Config_Data {

    public function toOptionArray() {
        $options = array();
        return $options;
    }

    protected function _beforeSave() {
        $value = $this->getValue();
        if (trim($value) == "") {
            $value = "blog";
        }
        $this->setValue($value);
        return $this;
    }

}
