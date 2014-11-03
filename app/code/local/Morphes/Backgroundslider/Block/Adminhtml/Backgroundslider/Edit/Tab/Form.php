<?php

class Morphes_Backgroundslider_Block_Adminhtml_Backgroundslider_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
		$form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('background_info_slider', array('legend'=>Mage::helper('background')->__('Информация')));
      $tempArray = array(
	          'label'     => Mage::helper('background')->__('Пример работы'),
	          'name'      => 'image_value',
	          'style'     => 'display:none;',
		  );
      $fieldset->addField('image_value', 'thumbnail',$tempArray);
	}
}
?>
