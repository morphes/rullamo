<?php

class Mconnect_Productribbon_Block_Adminhtml_Productribbon_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'productribbon';
        $this->_controller = 'adminhtml_productribbon';
        
        $this->_updateButton('save', 'label', Mage::helper('productribbon')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('productribbon')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('productribbon_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'productribbon_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'productribbon_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('productribbon_data') && Mage::registry('productribbon_data')->getId() ) {
            return Mage::helper('productribbon')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('productribbon_data')->getTitle()));
        } else {
            return Mage::helper('productribbon')->__('Add Item');
        }
    }
}