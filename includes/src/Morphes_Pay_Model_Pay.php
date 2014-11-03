<?php
class Morphes_Pay_Model_Pay extends Mage_Payment_Model_Method_Abstract
{
    protected $_code = 'pay';
    protected $_infoBlockType = 'pay/info_pay';
    protected $_formBlockType = 'pay/form_pay';

    public function assignData($data)
    {   
        if (!($data instanceof Varien_Object)) {
            $data = new Varien_Object($data);
        }
        $info = $this->getInfoInstance();
        $sc = $this->getModuleConfigData();
        $link = 'http://quittance.ru/pd4.php?nam='.urlencode(iconv('utf-8', 'windows-1251', $sc['nam'])).'&inn='.urlencode(iconv('utf-8', 'windows-1251',$sc['inn'])).'&nam='.urlencode(iconv('utf-8', 'windows-1251',$sc['nam'])).'&kpp='.urlencode(iconv('utf-8', 'windows-1251',$sc['kpp'])).'&sch='.urlencode(iconv('utf-8', 'windows-1251',$sc['sch'])).'&bnk='.urlencode(iconv('utf-8', 'windows-1251',$sc['bnk'])).'&bik='.urlencode(iconv('utf-8', 'windows-1251',$sc['bik'])).'&ksc='.urlencode(iconv('utf-8', 'windows-1251',$sc['ksc'])).'&plt='.urlencode(iconv('utf-8', 'windows-1251',$sc['plt'])).'&fio='.urlencode(iconv('utf-8', 'windows-1251',$this->getFirstname().' '.$this->getLastname())).'&adr='.urlencode(iconv('utf-8', 'windows-1251',$this->getCity().', '.$this->getStreet())).'&day='.urlencode(iconv('utf-8', 'windows-1251',$this->getDay())).'&mnt='.urlencode(iconv('utf-8', 'windows-1251',$this->getMonth())).'&yea='.urlencode(iconv('utf-8', 'windows-1251',$this->getYear())).'&rubkop='.urlencode(iconv('utf-8', 'windows-1251',$this->getTotalSumm()));
        $link_prev = '<a href="';
        $link_next = '">'.$sc['lnk'].'</a>';   
        $link = $link_prev.iconv('windows-1251', 'utf-8', $link).$link_next;
        $data->setLinkSberbank('');
        $info->setLinkSberbank($link);     
        return $this;
    }
    
    function getQuote() {
        return Mage::getSingleton('checkout/session')->getQuote();
    }

    function getFirstname() {
        return $this->getQuote()->getShippingAddress()->getFirstname();
    }
    
    function getCity() {
        return $this->getQuote()->getShippingAddress()->getCity();
    }
    
    function getStreet() {
        $street = $this->getQuote()->getShippingAddress()->getStreet();
        return $street['0'];
    }
    
    function getTotalSumm() {
        return $this->getQuote()->getGrandTotal();
    }
    
    function getLastname() {
        return $this->getQuote()->getShippingAddress()->getLastname();
    }
    
    function getDay() {
        return date('d');
    }
    
    function getMonth() {
        return date('m');
    }
    
    function getYear() {
        return date('d');
    }
    
    function getModuleConfigData() {
        return Mage::getStoreConfig('payment/pay');
    }
    
    

}
