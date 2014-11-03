<?php
/**
 * @category    Morphes
 * @package     MorphesPro_FilterAdmin
 * @copyright   Copyright (c) http://www.morphes.ru
 * @license     http://www.morphes.ru/license  Proprietary License
 */

/**
 * Enter description here ...
 * @author Morphes Team
 *
 */
class MorphesPro_FilterAdmin_Block_List_Container extends Morphes_Admin_Block_Crud_List_Container {
    public function __construct() {
        parent::__construct();
        $this->_headerText = $this->__('Layered Navigation Filters');
    }
}