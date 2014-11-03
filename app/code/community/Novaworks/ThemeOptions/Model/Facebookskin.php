<?php class Novaworks_ThemeOptions_Model_Facebookskin
{
    public function toOptionArray()
    {
        return array(
        	array('value'=>'light', 'label'=>Mage::helper('themeoptions')->__('Light Skin')),
            array('value'=>'dark', 'label'=>Mage::helper('themeoptions')->__('Dark Skin')),
 
        );
    }

}?>