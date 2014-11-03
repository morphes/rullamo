<?php
/**

 */
class Intex_Yandex_Model_Catalog extends Intex_Yandex_Model_Abstract
{
    /**
     *
     * @return bool
     */
    public function isUseGzip()
    {
        return Mage::getStoreConfig('yandex/yandex/use_gzip');
    }

    public function isActive()
    {
        return Mage::getStoreConfig('yandex/yandex/active');
    }

    public function isUseAuth()
    {
        return Mage::getStoreConfig('yandex/yandex/use_auth');
    }

    public function isDeliveryIncluded()
    {
        return Mage::getStoreConfig('yandex/yandex/is_delivery_included');
    }

    public function getAuthLogin()
    {
        return Mage::getStoreConfig('yandex/yandex/auth_login');
    }

    public function getAuthPassword()
    {
        return Mage::getStoreConfig('yandex/yandex/auth_password');
    }

    public function getYandexStoreName()
    {
        return Mage::getStoreConfig('yandex/yandex/store_name');
    }

    public function getYandexCompany()
    {
        return Mage::getStoreConfig('yandex/yandex/company_name');
    }

    public function getYandexPhone()
    {
        return Mage::getStoreConfig('yandex/yandex/phone');
    }

    public function getYandexUrl()
    {
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
    }

    /**
     * Get Yandex Market Language description
     *
     * @return string
     */
    public function getDescription()
    {
               
        $collection = Mage::getResourceModel('catalog/product_collection')->addAttributeToFilter('yml_export', "1")->addAttributeToSelect('*')->load();  

        $desc  = "<?xml version=\"1.0\" encoding=\"windows-1251\"?>\n";
        $desc .= "<!DOCTYPE yml_catalog SYSTEM \"shops.dtd\">\n";
        $desc .= "<yml_catalog date=\"".strftime('%Y-%m-%d %H:%M')."\">\n";
        $desc .= Mage::getModel('yandex/shop')->setYMLCatalog($this)->setProductsCollection($collection)->getDescription();
        $desc .= "</yml_catalog>\n";
        $desc = iconv("UTF-8", "windows-1251", $desc);
        return $desc;
    }
}
