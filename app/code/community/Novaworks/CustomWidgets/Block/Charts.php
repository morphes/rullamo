<?php
class Novaworks_CustomWidgets_Block_Charts extends Mage_Core_Block_Template
{
    /**
     * Set default template.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->setData('template', 'novaworks/custom_widgets/charts.phtml');
        parent::_construct();
    }

    /**
     * Return absolute url to the image file.
     *
     * @return string
     */
    public function getPinUrl()
    {
        return Mage::getBaseUrl('media') . $this->getCustomMapPin();
    }
}