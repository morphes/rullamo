<?php
/**
 * Novaworks Blog Extension
 * @version   1.0 12.09.2013
 * @author    Novaworks http://www.novaworks.net <info@novaworks.net>
 * @copyright Copyright (C) 2010 - 2013 Novaworks
 */

class Novaworks_Blog_IndexController extends Mage_Core_Controller_Front_Action
{

    public function preDispatch()
    {
      
        parent::preDispatch();
        if (!Mage::helper('blog')->getEnabled()) {
            $this->_redirectUrl(Mage::helper('core/url')->getHomeUrl());
        }

        Mage::helper('blog')->ifStoreChangedRedirect();
    }

    public function indexAction()
    {
        $this->_forward('list');
    }

    public function listAction()
    {        
        $this->loadLayout();

        $this->getLayout()->getBlock('root')->setTemplate(Mage::helper('blog')->getLayout());
 
        $this->renderLayout();
    }

}
