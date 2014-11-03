<?php
/**
 * @category    Morphes
 * @package     MorphesPro_FilterSlider
 * @copyright   Copyright (c) http://www.morphes.ru
 * @license     http://www.morphes.ru/license  Proprietary License
 */

/**
 * Changes interpretation of applied price filter value
 * @author Morphes Team
 *
 */
class MorphesPro_FilterSlider_Resource_Decimal extends Morphes_Filters_Resource_Filter_Decimal {
    public function getRange($index, $range) {
    	return array('from' => $index, 'to' => $range);
    }
    protected function _isUpperBoundInclusive() {
        return true;
    }
}