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
class MorphesPro_FilterAdmin_Block_Card_Container extends Morphes_Admin_Block_Crud_Card_Container {
    public function __construct() {
        parent::__construct();
        $model = Mage::registry('m_crud_model');
        $this->_headerText = $this->__('%s - Layered Navigation Filter', $model->getName());
    }
	protected function _prepareLayout() {
		$this->_addCloseButton()->_addApplyButton()->_addSaveButton();
	}
}