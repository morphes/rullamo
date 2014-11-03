<?php
/**
 *
 * @author Alin_M
 *
 */

class Alin_ProductGift_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getBlockGift($sku_gift) {
		$prod_gift = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku_gift);		
		$gift_name = $prod_gift->getName();
		$gift_image = Mage::helper('catalog/image')->init($prod_gift, 'small_image')->resize(78);
		$gift_url=Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
		$gift_url.=$prod_gift->getUrlPath();
		$gift_price=$prod_gift->getFormatedPrice();
		$url_img=Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN);
		$html.='<div style="position:absolute;margin-left: 152px;z-index: 6;margin-top: -2px;"><img src="/media/gift.png"/></div>';
		$html.='<div class="product_gift" style="float:left;clear:both;width:100%"><table border="0" cellspacing="0" cellpadding="0" style="border:1px solid rgb(242,66,173); border-radius:4px;"><tr>';
		$html.='';
		$html.='<td > <img src="'.$gift_image.'" style="width:50px"> </td>';
		$html.='<td style="width:159px"><div style="line-height:0.8; width:140px; height:47px;margin-top:-4px">';
		$html.='<div style="margin-top: 7px;overflow: hidden;margin-left: 10px;"><a style="font-size: 13px;color: #264C74;" href="'.$gift_url.'">'.$gift_name.'</a></div>';		
		$html.='</div></td></tr>';
		$html.='<tr><td colspan="2">';
		$html.='<div style="font-size: 12px;text-shadow:none;background-color: rgb(242,66,173);height:19px;color: white;clear: both;width: 100%;">&nbsp;&nbsp;+подарок на сумму: '.$gift_price.'</div>';
		$html.= '</td></tr></table></div>';
		return $html;
	}

	public function getBlockGiftCategory($sku_gift) {
		$prod_gift = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku_gift);
		$gift_name = $prod_gift->getName();
		$gift_image = Mage::helper('catalog/image')->init($prod_gift, 'small_image')->resize(78);
		$gift_url=Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
		$gift_url.=$prod_gift->getUrlPath();
		$gift_price=$prod_gift->getFormatedPrice();
		$url_img=Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN);
		
		$html='<img class="giftcustom" src="/media/plusgift.png"/>';		
		return $html;
	}
}
