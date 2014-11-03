<?php
/**
 * @category    Morphes
 * @package     Morphes_Admin
 * @copyright   Copyright (c) http://www.morphes.ru
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/**
 * @author Morphes Team
 *
 */
class Morphes_Admin_ChooserController extends Mage_Adminhtml_Controller_Action {
    public function productAction() {
        $this->getResponse()->setBody(Mage::helper('morphes_admin')->getProductChooserHtml());
    }
    public function cmsBlockAction() {
        $this->getResponse()->setBody(Mage::helper('morphes_admin')->getCmsBlockChooserHtml());
    }
}