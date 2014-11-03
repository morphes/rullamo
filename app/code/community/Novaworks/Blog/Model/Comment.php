<?php
/**
 * Novaworks Blog Extension
 * @version   1.0 12.09.2013
 * @author    Novaworks http://www.novaworks.net <info@novaworks.net>
 * @copyright Copyright (C) 2010 - 2013 Novaworks
 */

class Novaworks_Blog_Model_Comment extends Mage_Core_Model_Abstract {

    public function _construct() {
        $this->_init('blog/comment');
    }

    public function load($id, $field=null) {
        return parent::load($id, $field);
    }

}
