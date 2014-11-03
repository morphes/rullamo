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
class Morphes_Filters_Model_Sort extends Morphes_Core_Model_Source_Abstract {
    protected function _bySelected($a, $b) {
        if (!$a['m_selected'] && $b['m_selected']) return 1;
        if ($a['m_selected'] && !$b['m_selected']) return -1;
        return 0;
    }
    public function byPosition($a, $b) {
        if ($a['position'] < $b['position']) return -1;
        if ($a['position'] > $b['position']) return 1;
        return 0;
    }
    public function bySelected($a, $b) {
        $result = $this->_bySelected($a, $b);
        return $result != 0 ? $result : $this->byPosition($a, $b);
    }
    public function byName($a, $b) {
        if ($a['label'] < $b['label']) return -1;
        if ($a['label'] > $b['label']) return 1;
        return 0;
    }
    public function bySelectedName($a, $b) {
        $result = $this->_bySelected($a, $b);
        return $result != 0 ? $result : $this->byName($a, $b);
    }
    public function byCount($a, $b) {
        if ($a['count'] < $b['count']) return 1;
        if ($a['count'] > $b['count']) return -1;
        return 0;
    }
    public function bySelectedCount($a, $b) {
        $result = $this->_bySelected($a, $b);
        return $result != 0 ? $result : $this->byCount($a, $b);
    }
    protected function _getAllOptions() {
        return array(
            array('value' => '', 'label' => Mage::helper('morphes_filters')->__('Position')),
            array('value' => 'bySelected', 'label' => Mage::helper('morphes_filters')->__('Position (selected at the top)')),
            array('value' => 'byName', 'label' => Mage::helper('morphes_filters')->__('Name')),
            array('value' => 'bySelectedName', 'label' => Mage::helper('morphes_filters')->__('Name (selected at the top)')),
            array('value' => 'byCount', 'label' => Mage::helper('morphes_filters')->__('Count')),
            array('value' => 'bySelectedCount', 'label' => Mage::helper('morphes_filters')->__('Count (selected at the top)')),
        );
    }
}