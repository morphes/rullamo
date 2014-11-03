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
class Flagbit_Faq_IndexController extends Mage_Core_Controller_Front_Action
{
	/**
	 * Displays the FAQ list.
	 */
	public function indexAction()
	{
		$this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Вопросы  и отзывы покупателей'));
        $this->renderLayout();
	}
	
	/**
	 * Displays the current FAQ's detail view
	 */
	public function showAction()
	{
		$this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Вопросы  и отзывы покупателей'));
        $this->renderLayout();
	}
       
        public function addAction() {
            $get = $_GET;
            $to = 'sazon@nxt.ru';
            $name = htmlspecialchars(addslashes($get['name']));
            $email = htmlspecialchars(addslashes($get['email']));
            $city = htmlspecialchars(addslashes($get['city']));
            $question = htmlspecialchars(addslashes($get['question']));
            $model = Mage::getModel('flagbit_faq/faq')->
                     setName($name)->
                     setEmail($email)->
                     setCity($city)->
                     setQuestion($question)->
                     setIsActive('0')->save();
            echo 'ok';
            $model = Mage::getModel('flagbit_faq/faq');
            $model->sendToCustomer($email, $name);
            $model->sendToOwner($to, $name, $email, $city, $question);
            exit();
        }




}
