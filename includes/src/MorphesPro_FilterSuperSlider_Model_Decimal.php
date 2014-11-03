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
class MorphesPro_FilterSuperSlider_Model_Decimal extends Morphes_Filters_Model_Filter_Decimal {
    protected function _renderItemLabel($range, $value) {
        $range = $this->_getResource()->getRange($value, $range);
        /* @var $helper MorphesPro_FilterSuperSlider_Helper_Data */ $helper = Mage::helper(strtolower('MorphesPro_FilterSuperSlider'));
        $fromPrice  = $helper->formatNumber($range['from'], $this->getFilterOptions());
        $toPrice    = $helper->formatNumber($range['to'], $this->getFilterOptions());
        return Mage::helper('catalog')->__('%s - %s', $fromPrice, $toPrice);
    }
    public function getExistingValues() {
        $result = array();
        foreach ($this->_getResource()->getExistingValues($this) as $value) {
            $result[] = $value;
        }
        return array_values(array_unique($result));
    }

    public function isFilterAppliedWhenCounting($modelToBeApplied) {
        if ($this->_getIsFilterable() != 2) {
            return $modelToBeApplied != $this && $modelToBeApplied->getFilterOptions()->getDisplay() != 'slider';
        }
        else {
            return false;
        }
    }
}