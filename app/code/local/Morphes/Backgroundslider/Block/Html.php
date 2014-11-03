<?php 
class Morphes_Backgroundslider_Block_Html extends Mage_Page_Block_Html
{
    public function getSlider() {
    	return Mage::getModel('backgroundslider/backgroundslider')
    				->getCollection()
    				->addFilter('type', '0')
    				->addFilter('status', '1')
    				->setOrder('orders', 'ASC')
    				->getData();    	
    }

    public function getBanners() {
    	return Mage::getModel('backgroundslider/backgroundslider')
    				->getCollection()
    				->addFilter('type', '1')
    				->addFilter('status', '1')
    				->setOrder('orders', 'ASC')
    				->getData();    	
    }
}