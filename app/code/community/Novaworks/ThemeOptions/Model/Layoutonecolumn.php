<?php class Novaworks_ThemeOptions_Model_Layoutonecolumn
{
    public function toOptionArray()
    {
        return array(
            array('value'=>3, 'label'=>Mage::helper('themeoptions')->__('3')),
            array('value'=>4, 'label'=>Mage::helper('themeoptions')->__('4')),
            array('value'=>6, 'label'=>Mage::helper('themeoptions')->__('6'))            
        );
    }

}?>