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
class Morphes_Admin_Block_Crud_List_Grid extends Morphes_Admin_Block_Crud_Grid {
    public function __construct() {
        parent::__construct();
        $this->setSaveParametersInSession(true);
    }
    public function getGridUrl() {
        return Mage::helper('morphes_admin')->getStoreUrl('*/*/grid');
    }

    public function getRowUrl($row) {
	    return Mage::helper('morphes_admin')->getStoreUrl('*/*/edit', array(
	    	'id' => Mage::helper('morphes_admin')->isGlobal() ? $row->getId() : $row->getGlobalId(),
	    ));
    }
}