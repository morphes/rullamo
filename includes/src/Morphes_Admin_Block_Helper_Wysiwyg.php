<?php
/**
 * @category    Morphes
 * @package     Morphes_Admin
 * @copyright   Copyright (c) http://www.morphes.ru
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Enter description here ...
 * @author Morphes Team
 *
 */
class Morphes_Admin_Block_Helper_Wysiwyg extends Mage_Adminhtml_Block_Template {
    protected function _prepareLayout() {
        /* @var $js Morphes_Core_Helper_Js */ $js = Mage::helper(strtolower('Morphes_Core/Js'));
        /* @var $admin Morphes_Admin_Helper_Data */ $admin = Mage::helper(strtolower('Morphes_Admin'));
        $js->options('#morphes-wysiwyg-editor', array(
            'url' => $this->getUrl('*/catalog_product/wysiwyg'),
            'storeId' => $admin->getStore()->getId(),
        ));
        return parent::_prepareLayout();
    }


}