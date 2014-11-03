<?php

class Mconnect_Productribbon_Block_Adminhtml_Productribbon_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('productribbon_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('productribbon')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('productribbon')->__('Item Information'),
          'title'     => Mage::helper('productribbon')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('productribbon/adminhtml_productribbon_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}