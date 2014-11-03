<?php
 
class Freaks_Quotes_Block_Name extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{   
 
public function render(Varien_Object $row) {
    $regionId =  $row->getData($this->getColumn()->getIndex());
    return Mage::getModel('directory/region')->load($regionId)->getName();
}
}
