<?php 
class Morphes_Backgroundslider_Block_Adminhtml_Backgroundslider_Renderer_Link extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {    	        
        $out = "<a href='/". $row->getColorValue() ."'>".$row->getColorValue()."</a>";
        return $out;
    }
}