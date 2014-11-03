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
class MorphesPro_FilterSuperSlider_Resource_Solr_Decimal extends Morphes_Filters_Resource_Filter_Decimal {
    public function getRange($index, $range) {
    	return array('from' => $index, 'to' => $range);
    }
    public function isUpperBoundInclusive() {
        return true;
    }
    public function getExistingValues($filter) {
        $select     = $this->_getSelect($filter);
        $adapter    = $this->_getReadAdapter();

        $rangeExpr  = new Zend_Db_Expr("decimal_index.value");
        $select->columns(array('value' => 'decimal_index.value'));
        $select->group('value');
        $select->order('value');

        // MORPHES BEGIN: make sure price filter is not applied
        $select->reset(Zend_Db_Select::WHERE);
        // MORPHES END

        return $adapter->fetchCol($select);
    }
}