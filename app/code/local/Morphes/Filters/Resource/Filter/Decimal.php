<?php
/**
 * @category    Morphes
 * @package     Morphes_Filters
 * @copyright   Copyright (c) http://www.morphes.ru
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/**
 * Resource type which contains sql code for applying filters and related operations
 * @author Morphes Team
 * Injected instead of standard resource catalog/layer_filter_attribute in 
 * Morphes_Filters_Model_Filter_Price::_getResource().
 */
class Morphes_Filters_Resource_Filter_Decimal extends Mage_Catalog_Model_Resource_Eav_Mysql4_Layer_Filter_Decimal {
    /**
     * @param Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection $collection
     * @param Morphes_Filters_Model_Filter_Attribute $model
     * @return Morphes_Filters_Resource_Filter_Decimal
     */
    public function countOnCollection($collection, $model) {
        $select = $collection->getSelect();
        $select->reset(Zend_Db_Select::COLUMNS);
        $select->reset(Zend_Db_Select::ORDER);
        $select->reset(Zend_Db_Select::LIMIT_COUNT);
        $select->reset(Zend_Db_Select::LIMIT_OFFSET);

        $attributeId = $model->getAttributeModel()->getId();
        $storeId     = $collection->getStoreId();

        $select->join(
            array('decimal_index' => $this->getMainTable()),
            'e.entity_id = decimal_index.entity_id'.
            ' AND ' . $this->_getReadAdapter()->quoteInto('decimal_index.attribute_id = ?', $attributeId) .
            ' AND ' . $this->_getReadAdapter()->quoteInto('decimal_index.store_id = ?', $storeId),
            array()
        );

        $adapter = $this->_getReadAdapter();

        $countExpr = new Zend_Db_Expr("COUNT(*)");
        $rangeExpr = new Zend_Db_Expr("FLOOR(decimal_index.value / {$model->getRange()}) + 1");

        $select->columns(array('range' => $rangeExpr, 'count' => $countExpr));
        $select->group('range');

        return $adapter->fetchPairs($select);
    }

    /**
     * @param Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection $collection
     * @param Morphes_Filters_Model_Filter_Decimal $model
     * @param array $value
     * @return Morphes_Filters_Resource_Filter_Decimal
     */
    public function applyToCollection($collection, $model, $value) {
        $attribute  = $model->getAttributeModel();
        $connection = $this->_getReadAdapter();
        $tableAlias = $attribute->getAttributeCode() . '_idx';
        $conditions = array(
            "{$tableAlias}.entity_id = e.entity_id",
            $connection->quoteInto("{$tableAlias}.attribute_id = ?", $attribute->getAttributeId()),
            $connection->quoteInto("{$tableAlias}.store_id = ?", $collection->getStoreId())
        );

        $condition = '';
        foreach ($value as $selection) {
            if (strpos($selection, ',') !== false) {
                list($index, $range) = explode(',', $selection);
                $range = $this->getRange($index, $range);
                if ($condition != '') $condition .= ' OR ';
                $condition .= '(('."{$tableAlias}.value" . ' >= '. $range['from'].') '.
                    'AND ('."{$tableAlias}.value" . ($this->isUpperBoundInclusive() ? ' <= ' : ' < '). $range['to'].'))';
            }
        }

        if ($condition) {
            $collection->getSelect()
                ->join(
                    array($tableAlias => $this->getMainTable()),
                    join(' AND ', $conditions),
                    array()
                )
                ->distinct()
                ->where($condition);
        }

        return $this;
    }


    public function isUpperBoundInclusive() {
        return false;
    }

    protected function _getSelectForCollection($filter, $collection)
    {
        // clone select from collection with filters
        $select = clone $collection->getSelect();
        // reset columns, order and limitation conditions
        $select->reset(Zend_Db_Select::COLUMNS);
        $select->reset(Zend_Db_Select::ORDER);
        $select->reset(Zend_Db_Select::LIMIT_COUNT);
        $select->reset(Zend_Db_Select::LIMIT_OFFSET);

        $attributeId = $filter->getAttributeModel()->getId();
        $storeId     = $collection->getStoreId();

        $select->join(
            array('decimal_index' => $this->getMainTable()),
            'e.entity_id = decimal_index.entity_id'.
            ' AND ' . $this->_getReadAdapter()->quoteInto('decimal_index.attribute_id = ?', $attributeId) .
            ' AND ' . $this->_getReadAdapter()->quoteInto('decimal_index.store_id = ?', $storeId),
            array()
        );

        return $select;
    }

    /**
     * Retrieve maximal price for attribute
     *
     * @param Mage_Catalog_Model_Layer_Filter_Price $filter
     * @return float
     */
    public function getMinMaxForCollection($filter, $collection)
    {
        $select     = $this->_getSelectForCollection($filter, $collection);
        $connection = $this->_getReadAdapter();

        $table = 'decimal_index';

        $select->columns(array(
            'min_value' => new Zend_Db_Expr('MIN(decimal_index.value)'),
            'max_value' => new Zend_Db_Expr('MAX(decimal_index.value)'),
        ));
        Mage::helper('morphes_filters')->resetProductCollectionWhereClause($select);

        $result     = $connection->fetchRow($select);
        return array($result['min_value'], $result['max_value']);
    }

    public function getRange($index, $range) {
    	return array('from' => $range * ($index - 1), 'to' => $range * $index);
    }
}