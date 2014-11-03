<?php
class Novaworks_CustomWidgets_Block_Latest extends Mage_Core_Block_Template
{
    /**
     * Set default template.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->setData('template', 'novaworks/custom_widgets/latest_tweets.phtml');
        parent::_construct();
    }
}