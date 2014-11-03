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
class Morphes_Filters_Resource_Solr_And_Attribute extends Morphes_Filters_Resource_Solr_Attribute
{
    /**
     * @param Enterprise_Search_Model_Resource_Collection $collection
     * @param Morphes_Filters_Model_Filter_Attribute $model
     * @param array $value
     * @return Morphes_Filters_Resource_Solr_Attribute
     */
    public function applyToCollection($collection, $model, $value)
    {
        $collection->addFqFilter(array($model->getFilterField() => array('and' => $value)));
    }
}