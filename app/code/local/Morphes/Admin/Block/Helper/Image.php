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
class Morphes_Admin_Block_Helper_Image extends Mage_Adminhtml_Block_Template {
    protected function _prepareLayout() {
        /* @var $js Morphes_Core_Helper_Js */ $js = Mage::helper(strtolower('Morphes_Core/Js'));
        /* @var $files Morphes_Core_Helper_Files */ $files = Mage::helper(strtolower('Morphes_Core/Files'));
        $js->options('#m-image-helper', array(
            'baseUrl' => $files->getBaseUrl('image'),
            'uploadUrl' => $this->getUrl('*/upload/start')
        ));
        return parent::_prepareLayout();
    }


}