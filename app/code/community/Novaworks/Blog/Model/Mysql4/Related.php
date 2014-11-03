<?php
/**
 * Novaworks Blog Extension
 * @version   1.0 12.09.2013
 * @author    Novaworks http://www.novaworks.net <info@novaworks.net>
 * @copyright Copyright (C) 2010 - 2013 Novaworks
 */

class Novaworks_Blog_Model_Mysql4_Related extends Mage_Core_Model_Mysql4_Abstract {

    public function _construct() {
        $this->_init('blog/related', 'post_id');
    }

    public function load(Mage_Core_Model_Abstract $object, $value, $field=null) {
        if (strcmp($value, (int) $value) !== 0) {
            $field = 'post_id';
        }
        return parent::load($object, $value, $field);
    }

}
