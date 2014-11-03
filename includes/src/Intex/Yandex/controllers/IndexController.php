<?php
/**

 */

class Intex_Yandex_IndexController extends Mage_Core_Controller_Front_Action
{
    /**
     * Index action
     */
    public function indexAction()
    {

        $catalog = Mage::getModel('yandex/catalog');
        if (!$catalog->isActive()) { 
            exit();
        }

        if ($catalog->isUseAuth()) {
            if (!$catalog->getAuthLogin() || !$catalog->getAuthPassword()) {
                exit();
            } else {
                if (!isset($_SERVER['PHP_AUTH_USER'])) {
                    $this->getResponse()->setHeader('WWW-Authenticate', 'Basic realm="'.$this->__('YML description').'"');
                    $this->getResponse()->setHeader('HTTP/1.0', '401 Unauthorized');
                    $this->getResponse()->appendBody(Mage::helper('adminhtml')->__('You should be authorize in order to download YML description'));
                    $this->getResponse()->sendResponse();
                    exit;
                } else {
                    if ($_SERVER['PHP_AUTH_USER'] != $catalog->getAuthLogin() || $_SERVER['PHP_AUTH_PW'] != $catalog->getAuthPassword()) {
                        $this->getResponse()->setHeader('HTTP/1.1', '403 Forbidden');
                        $this->getResponse()->sendResponse();
                        exit;
                    }
                }
            }
        }
                
        if ($catalog->isUseGzip()) {
            $this->getResponse()->setHeader('Content-Type', 'application/x-gzip');
            $this->getResponse()->setHeader('Content-Disposition', 'attachment; filename="intex.yandex.xml.gz"');
            $this->getResponse()->appendBody(gzencode($catalog->getDescription()));
        } else {
            $this->getResponse()->setHeader('Content-Type', 'application/xml');
            $this->getResponse()->setHeader('Content-Disposition', 'attachment; filename="intex_yandex.xml"');
            $this->getResponse()->appendBody($catalog->getDescription());
        }
        $this->getResponse()->sendResponse();

        exit();
    }
}
