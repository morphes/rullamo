<?php
class Freaks_Quotes_Adminhtml_QuotesController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->_title($this->__('Города'));

        $this->loadLayout();
        $this->_setActiveMenu('freaks_quotes');
        $this->_addBreadcrumb(Mage::helper('freaks_quotes')->__('Quotes'), Mage::helper('freaks_quotes')->__('Города'));
        $this->renderLayout();
    }
    
    public function newAction()
    {
        $this->_title($this->__('Добавить город'));
        $this->loadLayout();
        $this->_setActiveMenu('freaks_quotes');
        $this->_addBreadcrumb(Mage::helper('freaks_quotes')->__('Add new quote'), Mage::helper('freaks_quotes')->__('Добавить город'));
        $this->renderLayout();
    }
    
    public function editAction()
    {
        $this->_title($this->__('Редактировать город'));

        $this->loadLayout();
        $this->_setActiveMenu('freaks_quotes');
        $this->_addBreadcrumb(Mage::helper('freaks_quotes')->__('Edit quote'), Mage::helper('freaks_quotes')->__('Редактировать город'));
        $this->renderLayout();
    }
    
    public function deleteAction()
    {
        $tipId = $this->getRequest()->getParam('id', false);
 
        try {
            Mage::getModel('freaks_quotes/quote')->setId($tipId)->delete();
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('freaks_quotes')->__('Город удален'));
            
            return $this->_redirect('*/*/');
        } catch (Mage_Core_Exception $e){
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        } catch (Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('adminhtml/session')->addError($this->__('Somethings went wrong'));
        }
 
        $this->_redirectReferer();
    }

    public function saveAction()
    {
        $data = $this->getRequest()->getPost();
        if (!empty($data)) {
            try {
                Mage::getModel('freaks_quotes/quote')->setData($data)
                    ->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('freaks_quotes')->__('Город успешно сохранен'));
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError($this->__('Произошла ошибка.'));
            }
        }
        return $this->_redirect('*/*/');
    }
    
    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('freaks_quotes/adminhtml_quotes_grid')->toHtml()
        );
    }
}
