<?php
/**
 * FAQ for Magento
 *
 * @category   Flagbit
 * @package    Flagbit_Faq
 * @copyright  Copyright (c) 2009 Flagbit GmbH & Co. KG <magento@flagbit.de>
 */

/**
 * FAQ for Magento
 *
 * @category   Flagbit
 * @package    Flagbit_Faq
 * @author     Flagbit GmbH & Co. KG <magento@flagbit.de>
 */
class Flagbit_Faq_Model_Faq extends Mage_Core_Model_Abstract
{
    /**
     * Constructor
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('flagbit_faq/faq');
    }

    public function sendToCustomer($recepientEmail, $recepientName) {
        $templateId = 1;
        $senderName = Mage::getStoreConfig('trans_email/ident_support/name');
        $senderEmail = Mage::getStoreConfig('trans_email/ident_support/email');    
        $sender = array('name' => $senderName,
                  'email' => $senderEmail);   
        $store = Mage::app()->getStore()->getId();
        $vars = array('customerName' => $recepientEmail,
                      'customerEmail' => $recepientName);             
        $translate  = Mage::getSingleton('core/translate');
        Mage::getModel('core/email_template')
            ->sendTransactional($templateId, $sender, $recepientEmail, $recepientName, $vars, $storeId);             
        $translate->setTranslateInline(true);   
    }

    public function sendToOwner($to, $name, $email, $city, $question) {
            $to = Mage::getStoreConfig('trans_email/ident_support/email');
            $mail = Mage::getModel('core/email');
            $mail->setToName('Клиент '.$name);
            $mail->setToEmail($to);
            $mail->setBody('<h3>Имя: </h3>'.$name."<br/><h3>Email: </h3>".$email."<br/><h3>Город: </h3>".$city."<br/><h3>Текст сообщения: </h3>".  $question);
            $mail->setSubject('=?utf-8?B?'.base64_encode('Пришел вопрос или отзыв').'?=');
            $mail->setFromEmail(Mage::getStoreConfig('trans_email/ident_support/email'));
            $mail->setFromName(Mage::getStoreConfig('trans_email/ident_support/name'));
            $mail->setType('html');
            try {
                $mail->send();
            }
            catch (Exception $e) {
                Mage::logException($e);
                return false;
            }

    }
}
