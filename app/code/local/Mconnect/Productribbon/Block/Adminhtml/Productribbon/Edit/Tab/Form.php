<?php

class Mconnect_Productribbon_Block_Adminhtml_Productribbon_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('productribbon_form', array('legend'=>Mage::helper('productribbon')->__('Item information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('productribbon')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));

      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('productribbon')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('productribbon')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('productribbon')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('productribbon')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('productribbon')->__('Content'),
          'title'     => Mage::helper('productribbon')->__('Content'),
          'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getProductribbonData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getProductribbonData());
          Mage::getSingleton('adminhtml/session')->setProductribbonData(null);
      } elseif ( Mage::registry('productribbon_data') ) {
          $form->setValues(Mage::registry('productribbon_data')->getData());
      }
      return parent::_prepareForm();
  }
}