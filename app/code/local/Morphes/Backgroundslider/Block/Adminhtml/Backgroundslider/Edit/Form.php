<?php
class Morphes_Backgroundslider_Block_Adminhtml_Backgroundslider_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
	
	  $form = new Varien_Data_Form(array(
          'id' => 'edit_form',
          'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
          'method' => 'post',
		  	 'enctype' => 'multipart/form-data'
		));

      $form->setUseContainer(true);
      $this->setForm($form);
      $hlp = Mage::helper('background');

/*******************Code to get values from table*********************/

	$get_key = Mage::getSingleton('adminhtml/url')->getSecretKey("adminhtml_background","getSellerImages");
	$home_url = Mage::helper('core/url')->getHomeUrl();

	$id = Mage::registry('background_data')->getId();
	$imgPath	=	Mage::getBaseUrl("media");
	$background_data = Mage::registry('background_data')->getData();

	$getBestsellerProduct = Mage::getModel('backgroundslider/backgroundslider')->getCollection()->addFieldToFilter('background_id',array('eq'=>$id))->getData();

/*******************Code to get values from table*********************/

    $fldInfo = $form->addFieldset('background_info_slider', array('legend'=> $hlp->__('Описание')));

	$fldInfo->addField('status', 'select', array(
			'label'        => $hlp->__('Status'),
			'required'  => true,
			'name'         => 'status',
			'values'    => array(
							array(
								'value'     => 1,
								'label'     => $hlp->__('Включено'),
							),
							array(
								'value'     => 0,
								'label'     => $hlp->__('Выключено'),
							),
						),
	));

	$fldInfo->addField('color_value', 'text', array(
			'label'        => $hlp->__('Ссылка'),
			'required'  => true,
			'name'         => 'color_value',
			
	));
	
//	$fldInfo->addField('options', 'select', array(
//			'label'        => $hlp->__('Цвет или изображение?'),
//			'required'  => true,
//			'name'         => 'options',
//			'values'    => array(
//							array(
//								'value'     => 1,
//								'label'     => $hlp->__('Цвет'),
//							),
//							array(
//								'value'     => 0,
//								'label'     => $hlp->__('Изображение'),
//							),
//						),
//	));
//----------------Code show uploaded image and specify if class is Requried or Not---------------//
	$object = Mage::getModel('backgroundslider/backgroundslider')->load($this->getRequest()->getParam('id'));
	$image = false;
    if($object->getImageValue()){
       $image = '<img width=350 src="'.$object->getImageValue().'" title="'.$object->getImageValue().'" align="center"/>';
    }

	$note = $hlp->__('');

  $fldInfo->addField('image_value', 'file', array(
		 'label'     => $hlp->__('Изображение'),
		 'note'      => $note,
		 'after_element_html' => $image,
		 'name'      => 'image_value',

      ));
    	$fldInfo->addField('orders', 'text', array(
			'label'        => $hlp->__('Сортировка'),
			'required'  => true,
			'name'         => 'orders',
			
	));
	$fldInfo->addField('type', 'select', array(
			'label'        => $hlp->__('Тип'),			
			'name'         => 'type',
			'values'    => array(
							array(
								'value'     => 1,
								'label'     => $hlp->__('Баннер'),
							),
							array(
								'value'     => 0,
								'label'     => $hlp->__('Слайдер'),
							),
						),
	));

//	$fldInfo->addField('color_value', 'text', array(
//		 'label'     => $hlp->__('Цвет'),
//		 'name'      => 'color_value',
//
//      ));
//----------------------------End of Code----------------------------//

	if ( Mage::registry('background_data') ) {
          $form->setValues(Mage::registry('background_data')->getData());
	  }

      return parent::_prepareForm();
  }
}
?>
