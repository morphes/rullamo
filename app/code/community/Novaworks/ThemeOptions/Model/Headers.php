<?php class Novaworks_ThemeOptions_Model_Headers
{
    public function toOptionArray()
    {
        return array(
        	array('value'=>'', 'label'=>Mage::helper('themeoptions')->__('Default Header')),
            array('value'=>'custom-header-1', 'label'=>Mage::helper('themeoptions')->__('Mega Style')),
            array('value'=>'custom-header-2', 'label'=>Mage::helper('themeoptions')->__('Vertical Menu'))  
        );
    }

}?>