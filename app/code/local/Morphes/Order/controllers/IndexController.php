<?php
class Morphes_Order_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
       $to = Mage::getStoreConfig('j2tajaxcheckout/default/j2t_ajax_cart_email2', Mage::app()->getStore()->getId());      
       $product = Mage::getModel('catalog/product')->setStoreId(Mage::app()->getStore()->getId())->load($_GET['id']);
       $name = $product->getName(); 
       $mail = Mage::getModel('core/email');
       $mail->setToName('Клиент '.$_REQUEST['fio']);
       $mail->setToEmail($to);
      // $mail->setBody('<h3>ФИО: </h3>'.$_REQUEST['fio']."<br/><h3>Телефон/email: </h3>".$_REQUEST['phone']."<br/><h3>Товар: </h3><a href='".$product->getProductUrl()."'>".$product->getName()."</a><br/><h3>Количество: </h3>".$_GET['qty']);
         $mail->setBody('<h3>ФИО: </h3>'.$_REQUEST['fio'].'<br/><h3>Телефон: </h3>'.$_REQUEST['phone']."<br/><h3>Товар: </h3><a href='".$product->getProductUrl()."'>".$product->getName()."</a><br/><h3>Количество: </h3>".$_GET['qty']);
       $mail->setSubject('=?utf-8?B?'.base64_encode('Новый заказ с формы заказа').'?=');
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

    public function shippingAction() {
      
      $sku = $_POST['sku'];      
      $_SESSION['product_for_cart'] = $_product = Mage::getModel('catalog/product')->load($sku, 'sku');
      $_SESSION['productid'] = Mage::getModel("catalog/product")->getIdBySku($sku);
      $session = Mage::getSingleton('core/session');
      $session_geo_ip = $session->getGeoIp();
      if (isset($session_geo_ip['code'])) {
            $to_city = $session_geo_ip['code'];
      } else {
            $to_city = '';
      }    
      if (isset($session_geo_ip['code'])) {


          $_SESSION['city_for_cart'] = $session_geo_ip['code'];
          $ship_data = Mage::getModel('freaks_quotes/quote')->loadByName($session_geo_ip['code']);
          $quote = Mage::getModel('sales/quote');
          $quote->getShippingAddress()->setCountryId('RU'); // Set your default shipping country here
          $_product->getStockItem()->setUseConfigManageStock(false);
          $_product->getStockItem()->setManageStock(false);
          $quote->addProduct($_product);
          $quote->getShippingAddress()->setCollectShippingRates(true);
          $op = $quote->getShippingAddress()->collectTotals();

          /* $rates = $quote->getShippingAddress()->getShippingRatesCollection();
           */
          // Find cheapest rate
          $cheapestrate = null;
          foreach ($rates as $rate) {
              if (is_null($cheapestrate) || $rate->getPrice() < $cheapestrate) {
                  $cheapestrate = $rate->getPrice();
              }
          }

          $corehelper = Mage::helper('core');

          unset($_SESSION['product_for_cart']);
          unset($_SESSION['city_for_cart']);
          unset($_SESSION['productid']);

          $custom = array('alias' => 'custom', 'title' => 'Custom tab');
          $custom1 = array('alias' => 'custom1', 'title' => 'Custom tab');
//          $tabs = $this->getTabs();
      }


                    if (isset($_SESSION['shipping_inf_edost'])) {

                        $edost = $_SESSION['shipping_inf_edost'];
                        echo "<br/><span class='shipping-in-tab'>Доставка</span>";
                        foreach ($edost as $ed) {    
                                // if($ed['carrier_title']=='Курьерская доставка') {
                                //     $title = $ed['carrier_title'];
                                // }
                                // else {
                                //     $title = '';
                                // }
                            $ed = $ed->getData();
                            if (isset($ed['method_title']) && ($ed['method_title'] != '')) {
                                $skobki = " " . $ed['method_title'] . "";
                            } else {
                                $skobki = '';
                            }
                            print_r("<br/><span class='shipping-in-tab-price'>" .$title. $skobki . " - <span class='colororange'>" . $ed['price'] . "</span> руб.</span>");
                        }
                    }

                    if($to_city=='2137') {  
                      echo "<br/><span class='samovivoz'>Самовывоз</span><br/><select class='appstockus-ship-select'><option value=''>Выберите пункт самовывоза...</option><option>Самовывоз в офисе магазина - 0 руб.</option>   ";
                      if (isset($_SESSION['shipping_inf_free'])) {
                          $free = $_SESSION['shipping_inf_free'];
                          foreach ($free as $fr) {
                              $fr = $fr->getData();
                              echo "<option>" . $fr['carrier_title'] . " (" . $fr['method_title'] . ") - " . $fr['price'] . " руб.</option>";
                          }

                          echo "</select>";
                      } else {
                          echo "</select>";                        
                      }
                    }

                    echo "<br/><br/><br/>";
                    exit();


    }

    public function shipping2Action() {      

      $sku = $_POST['sku'];      
      $_SESSION['product_for_cart'] = $_product = Mage::getModel('catalog/product')->load($sku, 'sku');
      $_SESSION['productid'] = Mage::getModel("catalog/product")->getIdBySku($sku);
      $session = Mage::getSingleton('core/session');
      $session_geo_ip = $session->getGeoIp();
      if (isset($session_geo_ip['code'])) {
            $to_city = $session_geo_ip['code'];
      } else {
            $to_city = '';
      }  
      if (isset($session_geo_ip['code'])) {

          $_SESSION['city_for_cart'] = $session_geo_ip['code'];
          $ship_data = Mage::getModel('freaks_quotes/quote')->loadByName($session_geo_ip['code']);
          $quote = Mage::getModel('sales/quote');
          $quote->getShippingAddress()->setCountryId('RU'); // Set your default shipping country here
          $_product->getStockItem()->setUseConfigManageStock(false);
          $_product->getStockItem()->setManageStock(false);
          $quote->addProduct($_product);
          $quote->getShippingAddress()->setCollectShippingRates(true);
          $op = $quote->getShippingAddress()->collectTotals();

          /* $rates = $quote->getShippingAddress()->getShippingRatesCollection();
           */
          // Find cheapest rate
          $cheapestrate = null;
          foreach ($rates as $rate) {
              if (is_null($cheapestrate) || $rate->getPrice() < $cheapestrate) {
                  $cheapestrate = $rate->getPrice();
              }
          }

          $corehelper = Mage::helper('core');

          unset($_SESSION['product_for_cart']);
          unset($_SESSION['city_for_cart']);
          unset($_SESSION['productid']);

          $custom = array('alias' => 'custom', 'title' => 'Custom tab');
          $custom1 = array('alias' => 'custom1', 'title' => 'Custom tab');
//          $tabs = $this->getTabs();
      }

      if (isset($_SESSION['shipping_inf_edost'])) {
                                        $edost = $_SESSION['shipping_inf_edost'];
                                       echo "<span class='shipping-in-tab'>Доставка</span>";
                                        foreach ($edost as $ed) {
                                            $ed = $ed->getData();
                                            if (isset($ed['method_title']) && ($ed['method_title'] != '')) {
                                                $skobki = "" . $ed['method_title'] . "";
                                            } else {
                                                $skobki = '';
                                            }
                                            print_r("<br/><span class='shipping-in-tab-price'>" . $ed['carrier_title'] . " " . $skobki . " - <span  class='colororange'>" . $ed['price'] . "</span> руб.</span>");
                                        }
                                    }
                                    if($to_city=='2137') {  
                                      echo "<br/><span class='samovivoz'>Самовывоз</span><br/><select class='appstockus-ship-select'><option value=''>Выберите пункт самовывоза...</option><option>Самовывоз в офисе магазина - 0 руб.</option>   ";
                                      if (isset($_SESSION['shipping_inf_free'])) {
                                          $free = $_SESSION['shipping_inf_free'];
                                          foreach ($free as $fr) {
                                              $fr = $fr->getData();
                                              echo "<option>" . $fr['carrier_title'] . " (" . $fr['method_title'] . ") - " . $fr['price'] . " руб.</option>";
                                          }

                                          echo "</select>";
                                      }
                                    }
      


           
                    exit();


    }
}
