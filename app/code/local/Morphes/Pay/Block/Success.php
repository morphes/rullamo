<?php 
class Morphes_Pay_Block_Success extends Mage_Checkout_Block_Onepage_Success 
{   

    function getOrder() {
        $model = Mage::getModel('sales/order');
        $_order = $model->loadByIncrementId($this->getOrderId());
        $order = $model->load($_order->getEntityId());
        return $order;
    }

    function getPaymentInfo() {
        return $this->getOrder()->getPayment()->getMethodInstance()->getInfoInstance()->getData(); 
    }

    function getPD4() {
        $payment_info = $this->getPaymentInfo();
        if($payment_info['method']=='pay') {
            return $payment_info['link_sberbank'];
        } else 
            return '';
    }
     
}