<?php
class Novaworks_WidgetProductsList_Model_Mode
{

    public function toOptionArray()
    {    
    	$attribute = Mage::getModel('eav/config')->getAttribute('catalog_product', 'featured');
    	if(null===$attribute->getId()) {
        	 return array(
            	array('value'=>'latest', 'label'=>Mage::helper('adminhtml')->__('Latest Product')),
            	array('value'=>'bestseller', 'label'=>Mage::helper('adminhtml')->__('Best Seller Product')),
            	array('value'=>'mostviewed', 'label'=>Mage::helper('adminhtml')->__('Most Viewed Product')),
            	array('value'=>'toprated', 'label'=>Mage::helper('adminhtml')->__('Toprated Product')),
            	array('value'=>'sale', 'label'=>Mage::helper('adminhtml')->__('Sale Product')),
            	array('value'=>'new', 'label'=>Mage::helper('adminhtml')->__('New Product'))
        	);
		}else{
			return array(
            	array('value'=>'latest', 'label'=>Mage::helper('adminhtml')->__('Latest Product')),
            	array('value'=>'bestseller', 'label'=>Mage::helper('adminhtml')->__('Best Seller Product')),
            	array('value'=>'mostviewed', 'label'=>Mage::helper('adminhtml')->__('Most Viewed Product')),
            	array('value'=>'toprated', 'label'=>Mage::helper('adminhtml')->__('Toprated Product')),
            	array('value'=>'sale', 'label'=>Mage::helper('adminhtml')->__('Sale Product')),
            	array('value'=>'featured', 'label'=>Mage::helper('adminhtml')->__('Featured Products')),
            	array('value'=>'new', 'label'=>Mage::helper('adminhtml')->__('New Product'))
        	);
		}
    } 
}
