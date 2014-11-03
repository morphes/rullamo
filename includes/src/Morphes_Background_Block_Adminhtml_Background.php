<?php
class Morphes_Background_Block_Adminhtml_Background extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
	{
		parent::__construct();
		$this->_controller = 'adminhtml_background';
		$this->_blockGroup = 'background';
		$this->_headerText = Mage::helper('background')->__('Управление фоном');
		$this->_removeButton('add');
		$this->_removeButton('task');
	}
}
