<?php
class Novaworks_CustomWidgets_Block_Template extends Mage_Core_Block_Template
{
    /**
     * Set default template.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->setData('template', 'novaworks/custom_widgets/template.phtml');
        parent::_construct();
    }

}