<?php

class Morphes_Pay_Block_Info_Pay extends Mage_Payment_Block_Info {

    protected function _construct() { 
        parent::_construct();
        $this->setTemplate('pay/info/info.phtml');
    }

    protected function _prepareSpecificInformation($transport = null) {
        if (null !== $this->_paymentSpecificInformation) {
            return $this->_paymentSpecificInformation;
        }
        $info = $this->getInfo();
        $transport = new Varien_Object();
        $transport = parent::_prepareSpecificInformation($transport);
        $transport->addData(array(
            Mage::helper('payment')->__('Sberbank') => '<b>' . $info->getLinkSberbank() . '</b>'
        ));
        return $transport;
    }

}