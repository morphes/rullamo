<?php

/**
 * J2T-DESIGN.
 *
 * @category   J2t
 * @package    J2t_Ajaxcheckout
 * @copyright  Copyright (c) 2003-2009 J2T DESIGN. (http://www.j2t-design.com)
 * @license    GPL
 */

require_once 'app/Mage.php';
Mage::app("default");
class J2t_Ajaxcheckout_IndexController extends /*Mage_Checkout_CartController*/ Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function cartdeleteAction()
    {
        $id = $this->getRequest()->getParam('id');
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            try {
                Mage::getSingleton('checkout/cart')->removeItem($id)
                  ->save();
            } catch (Exception $e) {
                Mage::getSingleton('checkout/session')->addError($this->__('Cannot remove item'));
            }
        }
        $this->loadLayout();
        $this->_initLayoutMessages('checkout/session');

        $this->renderLayout();
    }

    public function cartAction()
    {
        if ($this->getRequest()->getParam('cart')){
            if ($this->getRequest()->getParam('cart') == "delete"){
                $id = $this->getRequest()->getParam('id');
                if ($id) {
                    try {
                        Mage::getSingleton('checkout/cart')->removeItem($id)
                          ->save();
                    } catch (Exception $e) {
                        Mage::getSingleton('checkout/session')->addError($this->__('Cannot remove item'));
                    }
                }
            }
        }

        if ($this->getRequest()->getParam('product')) {
            $cart   = Mage::getSingleton('checkout/cart');
            $params = $this->getRequest()->getParams();
            $related = $this->getRequest()->getParam('related_product');

            $productId = (int) $this->getRequest()->getParam('product');


            if ($productId) {
                $product = Mage::getModel('catalog/product')
                    ->setStoreId(Mage::app()->getStore()->getId())
                    ->load($productId);
$session = Mage::getSingleton('core/session');
$session_geo_ip = $session->getGeoIp();

if(isset($session_geo_ip['is_changed'])) {
    $model_city = $session->getGeoIp(); 
} else {
    $model_city = Mage::getModel('geoip/abstract')->data; 
}


		if(isset($model_city['code']) && $model_city['code']!= '2137'){

$price_product = (int)$product->getFinalPrice();
		} else {
$price_product = (int)$product->getFinalPrice();
 }
                try {

                    if (!isset($params['qty'])) {
                        $params['qty'] = 1;
                    }

                    $cart->addProduct($product, $params);
                    if (!empty($related)) {
                        $cart->addProductsByIds(explode(',', $related));
                    }
                    $cart->save();

                    Mage::getSingleton('checkout/session')->setCartWasUpdated(true);
                    Mage::getSingleton('checkout/session')->setCartInsertedItem($product->getId());

                    $img = '';
                    Mage::dispatchEvent('checkout_cart_add_product_complete', array('product'=>$product, 'request'=>$this->getRequest()));

                    $photo_arr = explode("x",Mage::getStoreConfig('j2tajaxcheckout/default/j2t_ajax_cart_image_size', Mage::app()->getStore()->getId()));

                    $img = '<img src="'.Mage::helper('catalog/image')->init($product, 'image')->keepFrame(false)->resize(170,114).'" width="170" height="114" />';
                    $message = $this->__('%s was successfully added to your shopping cart.', $product->getName());
                    Mage::getSingleton('checkout/session')->addSuccess('<div class="j2tajax-checkout-img">'.$img.'</div><div class="j2tajax-checkout-txt">'.$message.'</div>');
                }
                catch (Mage_Core_Exception $e) {
                    if (Mage::getSingleton('checkout/session')->getUseNotice(true)) {
                        Mage::getSingleton('checkout/session')->addNotice($e->getMessage());
                    } else {
                        $messages = array_unique(explode("\n", $e->getMessage()));
                        foreach ($messages as $message) {
                            Mage::getSingleton('checkout/session')->addError($message);
                        }
                    }
                }
                catch (Exception $e) {
                    Mage::getSingleton('checkout/session')->addException($e, $this->__('Can not add item to shopping cart'));
                }

            }
        }

        $this->loadLayout();

        $this->_initLayoutMessages('checkout/session');
        $block = $this->getLayout()
        ->createBlock('core/text', 'example-block')
        ->setText("

<div class='testmessage'>
<!--<a href='".$product->getProductUrl()."'>1 товар</a> в корзине на сумму ".Mage::helper('core')->currency($price_product,true,false)."<div id='tut'><a href='".$product->getProductUrl()."' >".$product->getName()."</a></div>

".$img."--><div class='close-window-cart' onclick='hideJ2tOverlay()'>&nbsp;</div>
<input type='hidden' value='".$productId."' id='productid'/>
<div id='wrapper-cart'>
    <div id='header-cart'>
        <div id='cart-icon'>&nbsp;</div>
        <div id='header-cart-message'>
            <font id='count-cart-href'>
                 <a href='".$product->getProductUrl()."' id='count-product-href'>1 товар</a>
            </font>
            <font id='on-summ-text'>на сумму </font><font id='on-summ'>".Mage::helper('core')->currency($price_product,true,false)."</font></font>
        </div>
    </div>
    <div id='body-cart'>
        <div id='cart-product-image'>
            ".$img."
            <br/><br/>
            <div class='continue-image' onclick='redirect()'>&nbsp;</div><font class='continuation' onclick='redirect()'>Продолжить покупки</font>
        </div>
        <div id='cart-product-descr'>
            <div id='product-name'>
                <a href='".$product->getProductUrl()."' >".$product->getName()."</a>
            </div>
            <div id='product-count'>
                <div id='minus' onclick='decrement(".$price_product.")'>
		     &nbsp;
                </div>
                <div id='product-count-input'>
		     <input type='text' id='count_cart' value='1' onkeydown='maskInput()' onkeyup='changeHtml(".$price_product.",".Mage::getModel('cataloginventory/stock_item')->loadByProduct($productId)->getQty().", ".$price_product.")'/>
                </div>
                <div id='plus' onclick='increment(".Mage::getModel('cataloginventory/stock_item')->loadByProduct($productId)->getQty().", ".$price_product.")'>
                     &nbsp;
                </div>
                <div id='by-price'>
                    x ".Mage::helper('core')->currency($price_product,true,false)."
                </div>
            </div>
	    <button type='button' title='Оформить заказ через корзину' class='css3buttoncart order-cart-button' style='font-weight: normal;
font-size: 14px;' onclick='changeQty(".$productId.", ".Mage::getModel('cataloginventory/stock_item')->loadByProduct($productId)->getQty().")'><span><span>Перейти в корзину и оформить заказ</span></span></button>
        </div>
    </div>
    <div id='send-a-call'>
         <div id='order-header'>Быстрый заказ:</div>
         <div id='order-text-wrapper'><font id='order-text'>
              Вы можете  оформить заказ быстрее. Укажите  только имя и телефон, нажмите кнопку ОК.
Заказ поступит к менеджеру и он в течение 30  минут свяжется с Вами.
         </font></div>
         <div id='form-order'>
              <div id='form-order-col1'>
                   <div class='anons-text' style='width:29px'>Имя</div>
                   <div style='width: 150px; float:left;'><input type='text' name='title' id='summary_field' class='input-text fio required-entry form-order-input' placeholder='' value=''>        </div>                          
              </div>
              <div id='form-order-col2'>
                   <div class='anons-text' style='width:56px'>Телефон</div> 
                   <div style='width: 150px; float:left;'><input type='text' name='nickname' id='nickname_field' class='input-text fio required-entry phone-mail form-order-input' placeholder='например +7 495 123 45 67' value=''></div>                                    
              </div>
              <div id='form-order-col3'>
                    <div class='buttons-set' style='border-top: 4px !important;padding-top: 0px !important;margin-top: 10px !important;margin-left: 8px !important;font-weight: normal;'>
                        <button title='' style='font-weight: normal !important' class='css3buttongrey green-button' onClick=sendOrder('".$product->getProductUrl()."','".Mage::getModel('cataloginventory/stock_item')->loadByProduct($productId)->getQty()."','".$productId."')><span><span style='font-weigth:normal' >ОК</span></span></button>
                    </div>
              </div>
         </div>
    </div>
</div>
<script type='text/javascript'>

</script>   




</div>");
	$this->getLayout()->getBlock('content')->append($block);
        $this->renderLayout();
    }

    public function addtocartAction()
    {
        $this->indexAction();
    }

   public function changeqtyAction() {
       $yourProId = $_GET['id']; 
       $qty=1; 
       $cartHelper = Mage::helper('checkout/cart');
       $items = $cartHelper->getCart()->getItems();
       foreach ($items as $item) {
           if ($item->getProduct()->getId() == $yourProId) {
               $qty = $item->getQty() + $_GET['qty'] - 1; // check if greater then 0 or set it to what you want
               $item->setQty($qty);
               $cartHelper->getCart()->save();
               break;
           }
       }
       echo "ok";
   }

    public function preDispatch()
    {
        parent::preDispatch();
        $action = $this->getRequest()->getActionName();
    }


}
