<?php
/**
 * @category    Morphes
 * @package     Morphes_Filters
 * @copyright   Copyright (c) http://www.morphes.ru
 * @license     http://www.morphes.ru/license  Proprietary License
 */
/**
 * @author Morphes Team
 *
 */
class Morphes_Filters_Model_Operation extends Morphes_Core_Model_Source_Abstract {
    protected function _getAllOptions() {
        return array(
            array('value' => '', 'label' => Mage::helper('morphes_filters')->__('Logical OR')),
            array('value' => 'and', 'label' => Mage::helper('morphes_filters')->__('Logical AND')),
        );
    }
}