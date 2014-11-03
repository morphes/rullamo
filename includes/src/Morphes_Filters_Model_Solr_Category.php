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
class Morphes_Filters_Model_Solr_Category extends Morphes_Filters_Model_Filter_Category
{
    public function isCountedOnMainCollection() {
        return true;
    }

    /**
     * Get facet field name based on current website and customer group
     *
     * @return string
     */
    protected function _getFilterField() {
        $engine = Mage::getResourceSingleton('enterprise_search/engine');
        if (method_exists($engine, 'getSearchEngineFieldName')) {
            return 'category_ids';
        }
        else {
            return 'categories';
        }
    }

    public function processCounts($counts) {
        /* @var $collection Enterprise_Search_Model_Resource_Collection */
        $collection = $counts;

        $facetedData = $collection->getFacetedData($this->_getFilterField());
        foreach ($this->getCountedCategories() as $category) {
            if (isset($facetedData[$category->getId()])) {
                $category->setProductCount($facetedData[$category->getId()]);
            }
        }
        return $this->getCountedCategories();
    }

    /**
     * @param Enterprise_Search_Model_Resource_Collection $collection
     * @return Enterprise_Search_Model_Resource_Collection
     */
    public function countOnCollection($collection)
    {
        $useFlat = (bool) Mage::getStoreConfig('catalog/frontend/flat_catalog_category');
        $countedCategories = $this->getCountedCategories();
        $categories = ($countedCategories instanceof Mage_Core_Model_Resource_Db_Collection_Abstract)
            ? $countedCategories->getAllIds()
            : (($useFlat)
                ? array_keys($this->getCountedCategories())
                : array_keys($this->getCountedCategories()->toArray()));

        $collection->setFacetCondition($this->_getFilterField(), $categories);

        return $collection;
    }

    /**
     * @param Enterprise_Search_Model_Resource_Collection $collection
     */
    public function applyToCollection($collection)
    {
        $collection->addFqFilter(array($this->_getFilterField() => array('or' => $this->getMSelectedValues())));
    }

    public function isFilterAppliedWhenCounting($modelToBeApplied) {
        return true;
    }
}