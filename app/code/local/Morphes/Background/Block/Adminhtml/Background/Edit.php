<?php
class Morphes_Background_Block_Adminhtml_Background_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
	public function __construct()
	{
		parent::__construct();
		$this->_removeButton('add');
		$this->_objectId = 'id';
		$this->_blockGroup = 'background';
		$this->_controller = 'adminhtml_background';
		$this->_removeButton('delete');
		$this->_removeButton('reset');
	}

	public function getHeaderText()
	{
		if( Mage::registry('background_data') && Mage::registry('background_data')->getId() ) {
			return Mage::helper('background')->__("Сменить фон");
		} else {
			return Mage::helper('background')->__('Добавить фон');
		}
	}
}
