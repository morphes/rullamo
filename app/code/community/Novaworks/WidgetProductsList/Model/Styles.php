<?php
class Novaworks_WidgetProductsList_Model_Styles
{

    public function toOptionArray()
    {    
    	$attribute = Mage::getModel('eav/config')->getAttribute('catalog_product', 'featured');
			return array(
            	array('value'=>'', 'label'=>Mage::helper('adminhtml')->__('Default')),
            	array('value'=>'mega-style', 'label'=>Mage::helper('adminhtml')->__('Mega Style')),
        	);
    } 
}
