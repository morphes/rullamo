<?php
class Morphes_Backgroundslider_Block_Adminhtml_Backgroundslider_Renderer_Productimage extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {
	public function render(Varien_Object $row) {
		return ($this->_getImage($row));
	}
	protected function _getImage(Varien_Object $row) {
		$img = $row->getImageValue() != '' ? '<img width="131" height="36" src="'.$row->getImageValue().'" alt="" />' : '';
		return $img;
	}
}
