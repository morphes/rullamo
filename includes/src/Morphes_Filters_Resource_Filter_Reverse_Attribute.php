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
 * Morphes_Filters_Model_Filter_Attribute::_getResource().
 */
class Morphes_Filters_Resource_Filter_Reverse_Attribute
    extends Morphes_Filters_Resource_Filter_Attribute
{
    /**
     * @param Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection $collection
     * @param Morphes_Filters_Resource_Filter_Attribute $model
     * @param array $value
     * @return Morphes_Filters_Resource_Filter_Attribute
     */
    public function applyToCollection($collection, $model, $value) {
        $attribute = $model->getAttributeModel();
        $connection = $this->_getReadAdapter();

        $tableAlias = $attribute->getAttributeCode() . '_idx';
        $conditions = array(
            "{$tableAlias}.entity_id = e.entity_id",
            $connection->quoteInto("{$tableAlias}.attribute_id = ?", $attribute->getAttributeId()),
            $connection->quoteInto("{$tableAlias}.store_id = ?", $collection->getStoreId()),
            "{$tableAlias}.value in (" . implode(',', array_filter($value)) . ")"
        );
        $conditions = join(' AND ', $conditions);
        $collection->getSelect()
            ->distinct()
            ->where("NOT EXISTS (SELECT * ".
                "FROM `{$this->getMainTable()}` AS `$tableAlias` ".
                "WHERE {$conditions})");

        return $this;
    }
}