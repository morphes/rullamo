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
class MorphesPro_FilterSuperSlider_Model_Price extends Morphes_Filters_Model_Filter_Price {
    /**
     * Prepare text of item label
     *
     * @param   int $range
     * @param   float $value
     * @return  string
     */
    protected function _renderItemLabel($range, $value) {
        $range = $this->_getResource()->getPriceRange($value, $range);
    	/* @var $helper MorphesPro_FilterSuperSlider_Helper_Data */ $helper = Mage::helper(strtolower('MorphesPro_FilterSuperSlider'));
        $fromPrice  = $helper->formatNumber($range['from'], $this->getFilterOptions());
        $toPrice    = $helper->formatNumber($range['to'], $this->getFilterOptions());
        return Mage::helper('catalog')->__('%s - %s', $fromPrice, $toPrice);
    }
    public function getExistingValues() {
        $result = array();
        foreach ($this->_getResource()->getExistingValues($this) as $value) {
            $result[] = round($value);
        }
        return array_values(array_unique($result));
    }
    public function getDecimalDigits() {
        return $this->getFilterOptions()->getSliderDecimalDigits();
    }
}