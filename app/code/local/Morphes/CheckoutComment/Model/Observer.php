<?php

class Morphes_CheckoutComment_Model_Observer
{
    public function saveComment($observer) {
        
        $enable_comments = Mage::helper('checkoutcomment')->getConfig('enable_comment');

        if ($enable_comments)	{

            $orderComment = $observer->getEvent()->getRequest()->getPost('customer_notes');

            $orderComment = trim($orderComment);

            if ($orderComment != "") {
                // prepend a heading
                $orderComment = Mage::helper('checkoutcomment')->__("Customer Order Comment:\n").$orderComment;
                
                $observer->getEvent()->getQuote()->getShippingAddress()->setCustomerNotes($orderComment)->save();
            }
        }

    }
}