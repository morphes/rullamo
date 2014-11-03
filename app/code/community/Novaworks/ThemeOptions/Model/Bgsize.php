<?php class Novaworks_ThemeOptions_Model_Bgsize
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'auto', 'label'=>Mage::helper('themeoptions')->__('auto')),
            array('value'=>'cover', 'label'=>Mage::helper('themeoptions')->__('cover')),
            array('value'=>'contain', 'label'=>Mage::helper('themeoptions')->__('contain'))   
        );
    }

}?>
