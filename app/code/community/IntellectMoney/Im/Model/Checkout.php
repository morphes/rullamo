<?php

class IntellectMoney_Im_Model_Checkout extends Mage_Payment_Model_Method_Abstract {

    protected $_code          = 'im';
    protected $_formBlockType = 'im/form';
    protected $_infoBlockType = 'im/info';


    public function getCheckout() {
        return Mage::getSingleton('checkout/session');
    }

    public function getOrderPlaceRedirectUrl() {
        return Mage::getUrl('im/redirect', array('_secure' => true));
    }

    public function getIntellectMoneyUrl() {
        $url = 'https://merchant.intellectmoney.ru/ru/';
        return $url;
    }

    public function getQuote()
    {
        $orderIncrementId = $this->getCheckout()->getLastRealOrderId();
        $order = Mage::getModel('sales/order')->loadByIncrementId($orderIncrementId);		        
        return $order;
    }
    
    public function getIntellectMoneyCheckoutFormFields()
    {
        $order_id = $this->getCheckout()->getLastRealOrderId();
        $order    = Mage::getModel('sales/order')->loadByIncrementId($order_id);
        $amount   = trim(round($order->getGrandTotal(), 2));
        $entityId = $order->getEntityId();
        $email = $order->getCustomerEmail();

        $params = array(
            'LMI_PAYMENT_NO'            => $order_id,
            'LMI_PAYEE_PURSE'           => Mage::getStoreConfig('payment/im/im_wallet'),
            'LMI_PAYMENT_DESC'			=> Mage::helper('im')->__('Payment for order #').$order_id,
            'LMI_PAYMENT_AMOUNT'        => $amount,
            'LMI_RESULT_URL'			=> Mage::getUrl('im/ipn'),
            'LMI_FAIL_URL'				=> Mage::getUrl('sales/order/view', array('order_id' => $entityId)),
            'LMI_SUCCESS_URL'			=> Mage::getUrl('sales/order/view', array('order_id' => $entityId)),
            'EMAIL'						=> $email,
        );
        return $params;


    }
}
