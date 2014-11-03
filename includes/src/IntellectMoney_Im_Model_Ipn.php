<?php
/**
 * @category    Community
 * @package     Intellectmoney
 */

/**
 * Instant Payment Notification processor model
 */
class IntellectMoney_Im_Model_Ipn
{
    /*
     * @param Mage_Sales_Model_Order
     */
    protected $_order = null;

    /**
     * IPN request data
     * @var array
     */
    protected $_request = array();

    /**
     * IPN request data getter
     *
     * @param string $key
     * @return array|string
     */
    public function getRequestData($key = null)
    {
        if (null === $key) {
            return $this->_request;
        }
        return isset($this->_request[$key]) ? $this->_request[$key] : null;
    }

    /**
     * Get ipn data, run corresponding handler
     *
     * @param array $request
     * @param Zend_Http_Client_Adapter_Interface $httpAdapter
     * @throws Exception
     */
    public function processIpnRequest(array $request, Zend_Http_Client_Adapter_Interface $httpAdapter = null)
    {
        $this->_request = $request;

        try {
			if($request["LMI_PREREQUEST"] == '1')
			{
				$order = $this->_getOrder();
				$purse = Mage::getStoreConfig('payment/im/im_wallet');
				$amount = trim(round($order->getGrandTotal(), 2));
				
				if($amount == $request["LMI_PAYMENT_AMOUNT"] && $purse == $request["LMI_PAYEE_PURSE"]) {
					echo 'YES';
					exit;
				}
			}
			else {
				$secret_key = Mage::getStoreConfig('payment/im/secret_key');
				$hash_check = md5($request["LMI_PAYEE_PURSE"].$request["LMI_PAYMENT_AMOUNT"].$request["LMI_PAYMENT_NO"].$request["LMI_MODE"].$request["LMI_SYS_INVS_NO"].$request["LMI_SYS_TRANS_NO"].$request["LMI_SYS_TRANS_DATE"].$secret_key.$request["LMI_PAYER_PURSE"].$request["LMI_PAYER_WM"]);
				if(strtolower($hash_check) != strtolower($request['LMI_HASH']))
					exit;
				$this->_processOrder();
			}
        }
        catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Load and validate order, instantiate proper configuration
     *
     *
     * @return Mage_Sales_Model_Order
     * @throws Exception
     */
    protected function _getOrder()
    {
        if (empty($this->_order)) {
            // get proper order
            $id = $this->_request['LMI_PAYMENT_NO'];
            $this->_order = Mage::getModel('sales/order')->loadByIncrementId($id);
            if (!$this->_order->getId()) {
                throw new Exception(sprintf('Wrong order ID: "%s".', $id));
            }
        }
        return $this->_order;
    }

    /**
     * IPN workflow implementation
     * Everything should be added to order comments. In positive processing cases customer will get email notifications.
     * Admin will be notified on errors.
     */
    protected function _processOrder()
    {
        $this->_order = null;
        $this->_getOrder();

        try {
            $this->_registerPaymentCapture();
        }
        catch (Mage_Core_Exception $e) {
            $comment = $this->_createIpnComment(Mage::helper('im')->__('Note: %s', $e->getMessage()), true);
            $comment->save();
            throw $e;
        }
    }

    /**
     * Process completed payment (either full or partial)
     */
    protected function _registerPaymentCapture()
    {
        $payment = $this->_order->getPayment();
        $payment
			//->setTransactionId($this->getRequestData('txn_id'))
            //->setPreparedMessage($this->_createIpnComment(''))
            //->setParentTransactionId($this->getRequestData('parent_txn_id'))
            //->setShouldCloseParentTransaction('Completed' === $this->getRequestData('auth_status'))
            //->setIsTransactionClosed(0)
            ->registerCaptureNotification($this->getRequestData('LMI_PAYMENT_AMOUNT'));
        $this->_order->save();

        // notify customer
        if ($invoice = $payment->getCreatedInvoice()) {
            $comment = $this->_order->sendNewOrderEmail()->addStatusHistoryComment(
                    Mage::helper('im')->__('Notified customer about invoice #%s.', $invoice->getIncrementId())
                )
                ->setIsCustomerNotified(true)
                ->save();
        }
    }

    /**
     * Generate an "IPN" comment with additional explanation.
     * Returns the generated comment or order status history object
     *
     * @param string $comment
     * @param bool $addToHistory
     * @return string|Mage_Sales_Model_Order_Status_History
     */
    protected function _createIpnComment($comment = '', $addToHistory = false)
    {
        $paymentStatus = $this->getRequestData('payment_status');
        $message = Mage::helper('im')->__('IPN "%s".', $paymentStatus);
        if ($comment) {
            $message .= ' ' . $comment;
        }
        if ($addToHistory) {
            $message = $this->_order->addStatusHistoryComment($message);
            $message->setIsCustomerNotified(null);
        }
        return $message;
    }

}
