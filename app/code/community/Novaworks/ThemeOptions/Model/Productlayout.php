<?php class Novaworks_ThemeOptions_Model_Productlayout
{
    public function toOptionArray()
    {
        return array(
        	array('value'=>0, 'label'=>Mage::helper('themeoptions')->__('Default')),
            array('value'=>1, 'label'=>Mage::helper('themeoptions')->__('Layout 1')),     
        );
    }

}?>