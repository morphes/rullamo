<?php

/**
 * J2T-DESIGN.
 *
 * @category   J2t
 * @package    J2t_Ajaxcheckout
 * @copyright  Copyright (c) 2003-2009 J2T DESIGN. (http://www.j2t-design.com)
 * @license    GPL
 */


require_once 'Mage/Checkout/controllers/CartController.php';

class J2t_Ajaxcheckout_CartController extends Mage_Checkout_CartController
{  
    private $request; 

    public function indexAction() {             
        $this->request = $this->getRequest();
        $qty = $_POST['qty'];        
        $id_product = $this->request->getParam('id');    
        $cartHelper = Mage::helper('checkout/cart');
        $items = $cartHelper->getCart()->getItems();
        foreach ($items as $item) {
           if ($item->getProduct()->getId() == $id_product) { 
               $qty = $item->getQty() + $qty - 1;                
               $item->setQty($qty);
               $cartHelper->getCart()->save();
               break;               
           }
        }        
        parent::indexAction();
    }
}
