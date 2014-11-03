<?php
class Morphes_Backgroundslider_Block_Adminhtml_Backgroundslider extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
	{
		parent::__construct();						
		$this->_addButtonLabel = Mage::helper('background')->__('Добавить город');		
		$this->_controller = 'adminhtml_backgroundslider';
		$this->_blockGroup = 'backgroundslider';
		$this->_headerText = Mage::helper('background')->__('Слайдер');
		// $this->_removeButton('add');
		$this->_removeButton('task');
	}
}
