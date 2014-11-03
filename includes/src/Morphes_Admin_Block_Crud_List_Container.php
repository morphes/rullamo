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
class Morphes_Admin_Block_Crud_List_Container extends Mage_Adminhtml_Block_Widget_Container {
    /**
     * Set template
     */
    public function __construct() {
        parent::__construct();
        $this->setTemplate('morphes/admin/container.phtml');
    }
}