<?php
/**
 * @category    Morphes
 * @package     MorphesPro_FilterSuperSlider
 * @copyright   Copyright (c) http://www.morphes.ru
 * @license     http://www.morphes.ru/license  Proprietary License
 */
/**
 * @author Morphes Team
 *
 */
class MorphesPro_FilterSuperSlider_Model_Source_Menu extends Morphes_Core_Model_Source_Abstract {
    protected function _getAllOptions() {
        /* @var $t MorphesPro_FilterSuperSlider_Helper_Data */ $t = Mage::helper(strtolower('MorphesPro_FilterSuperSlider'));
        return array(
            array('value' => '', 'label' => $t->__('In Drop Down')),
            array('value' => 'inline', 'label' => $t->__('In Menu Line, Near Filter Name')),
        );
    }
}