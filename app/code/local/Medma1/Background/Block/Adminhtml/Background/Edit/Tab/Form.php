<?php

class Medma_Background_Block_Adminhtml_Background_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
		$form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('background_info', array('legend'=>Mage::helper('background')->__('Module information')));
      $tempArray = array(
	          'label'     => Mage::helper('background')->__('Sample Work'),
	          'name'      => 'image_value',
	          'style'     => 'display:none;',
		  );
      $fieldset->addField('image_value', 'thumbnail',$tempArray);
	}
}
?>
