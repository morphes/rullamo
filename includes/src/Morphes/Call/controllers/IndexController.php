<?php
class Morphes_Call_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
       $to = Mage::getStoreConfig('j2tajaxcheckout/default/j2t_ajax_cart_email2', Mage::app()->getStore()->getId());      
       $mail = Mage::getModel('core/email');
       $mail->setToName('Клиент '.$_REQUEST['fio']);
       $mail->setToEmail($to);
       $mail->setBody('<h3>ФИО: </h3>'.$_REQUEST['name']."<br/><h3>Телефон/email: </h3>".$_REQUEST['phone']."<br/><h3>Текст сообщения: </h3>".$_REQUEST['text']);
       $mail->setSubject('=?utf-8?B?'.base64_encode('Заказ звонка').'?=');
       $mail->setFromEmail("admin@toys-store.ru");
       $mail->setFromName("toys-store.ru");
       $mail->setType('html');
       try {
           $mail->send();
       }
       catch (Exception $e) {
           Mage::logException($e);
           return false;
       }
       exit();
    }
}
