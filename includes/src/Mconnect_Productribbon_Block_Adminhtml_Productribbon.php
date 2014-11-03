<?php
class Mconnect_Productribbon_Block_Adminhtml_Productribbon extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_productribbon';
    $this->_blockGroup = 'productribbon';
    $this->_headerText = Mage::helper('productribbon')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('productribbon')->__('Add Item');
    parent::__construct();
  }
}