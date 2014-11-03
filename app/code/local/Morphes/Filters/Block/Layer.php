<?php
/** 
 * @category    Morphes
 * @package     Morphes_Filters
 * @copyright   Copyright (c) http://www.morphes.ru
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/**
 * @author Morphes Team
 *
 */
class Morphes_Filters_Block_Layer extends Mage_Core_Block_Template {
    public function setCategoryId($id)
    {
        $category = Mage::getModel('catalog/category')->load($id);
        if ($category->getId()) {
            Mage::getSingleton('catalog/layer')->setCurrentCategory($category);
        }
    }
}