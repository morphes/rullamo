<?php class Novaworks_ThemeOptions_Model_Footers
{
    public function toOptionArray()
    {
        return array(
        	array('value'=>'', 'label'=>Mage::helper('themeoptions')->__('Default Footer')),
            array('value'=>'custom-footer-1', 'label'=>Mage::helper('themeoptions')->__('Footer One')),
            array('value'=>'custom-footer-2', 'label'=>Mage::helper('themeoptions')->__('Footer Two'))  
        );
    }

}
?>