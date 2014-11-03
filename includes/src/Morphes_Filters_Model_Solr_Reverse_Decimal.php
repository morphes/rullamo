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
class Morphes_Filters_Model_Solr_Reverse_Decimal extends Morphes_Filters_Model_Solr_Decimal
{
    /**
     * @param Enterprise_Search_Model_Resource_Collection $collection
     */
    public function applyToCollection($collection)
    {
        $attributeCode     = $this->getAttributeModel()->getAttributeCode();
        $field             = 'attr_decimal_'. $attributeCode;

        $fq = array();
        foreach ($this->getMSelectedValues() as $selection) {
            if (strpos($selection, ',') !== false) {
                list($index, $range) = explode(',', $selection);
                $fq[] = array(
                    'from' => ($range * ($index - 1)),
                    'to'   => $range * $index - ($this->isUpperBoundInclusive() ? 0 : 0.001),
                );
            }
        }

        $collection->addFqFilter(array($field => array('reverse' => $fq)));
    }

}