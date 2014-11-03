<?php
/**
 * Novaworks Blog Extension
 * @version   1.0 12.09.2013
 * @author    Novaworks http://www.novaworks.net <info@novaworks.net>
 * @copyright Copyright (C) 2010 - 2013 Novaworks
 */

class Novaworks_Blog_Model_Sorter
{
    public function toOptionArray()
    {
        return array(
            array('value' => Varien_Data_Collection::SORT_ORDER_DESC , 'label' => Mage::helper('adminhtml')->__('Newest first')),
            array('value' => Varien_Data_Collection::SORT_ORDER_ASC, 'label' => Mage::helper('adminhtml')->__('Older first')),
        );
    }
}
