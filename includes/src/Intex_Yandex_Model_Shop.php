<?php
/**

 */
class Intex_Yandex_Model_Shop extends Intex_Yandex_Model_Abstract
{
    /**

     *
     */
    private $_ymlCatalog;

    /**
     * Collection of shop's products
     *
     */
    private $_products_collection;

    private function _getCurrencies() {
        $desc  = "<currencies>\n";
        $store = Mage::app()->getStore();
        $currencies = $store->getAvailableCurrencyCodes();
        if (is_array($currencies)) {
            foreach ($currencies as $code) {
                $currency = Mage::getModel('directory/currency')->load($code);
                $cur_rate = $store->getBaseCurrency()->getRate($currency);
                $desc .= "<currency id=\"$code\" rate=\"$cur_rate\" />\n";
            }
        }
        $desc .= "</currencies>\n";
        return $desc;
    }

    private function _getCategories() {
        $desc  = "<categories>\n";
        foreach ($this->_products_collection->getItems() as $prodObj) {
            if ($prodObj->isSaleable()) {
                $catIds = $prodObj->getCategoryIds();
                if (!is_array($catIds) || empty($catIds)) {
                    continue;
                }
                $cat = Mage::getModel('catalog/category')->load($catIds[0]);
                $desc .= "<category id=\"".$catIds[0]."\"";
                $desc .= " parentId=\"".$cat->getParentId();
                $desc .= "\" > ".($this->_esc($cat->getName()))."</category>\n";
            }
        }
        $desc .= "</categories>\n";
        return $desc;
    }

    private function _getOffers() {
        $desc  = "<offers>\n";
        foreach ($this->_products_collection->getItems() as $prodObj) {
            if ($prodObj->isSaleable()) {
                $desc .= Intex_Yandex_Model_Offer::getOffer($prodObj)->getDescription();
            }
        }
        $desc .= "</offers>\n";
        return $desc;
    }

    public function setYMLCatalog($catalog) {
        $this->_ymlCatalog = $catalog;
        return $this;
    }

    public function setProductsCollection($collection) {
        $this->_products_collection = $collection;
        return $this;
    }

    /**
     * Get Yandex Market Language description for shop
     *
     * @return string
     */
    public function getDescription()
    {        
        $desc  = "<shop>\n";
        $desc .= "<name>".$this->_esc($this->_ymlCatalog->getYandexStoreName())."</name>\n";
        $desc .= "<company>".$this->_esc($this->_ymlCatalog->getYandexCompany())."</company>\n";
        $desc .= "<url>".$this->_esc($this->_ymlCatalog->getYandexUrl())."</url>\n";
        $phone = $this->_ymlCatalog->getYandexPhone();
        if ($phone) {
            $desc .= "<phone>".$this->_esc($phone)."</phone>\n";
        }
        $desc .= $this->_getCurrencies();
        $desc .= $this->_getCategories();
        $desc .= $this->_getOffers();
        $desc .= "</shop>\n";

        return $desc;
    }
}
