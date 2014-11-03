<?php
/**
 * @category    Morphes
 * @package     MorphesPro_FilterSlider
 * @copyright   Copyright (c) http://www.morphes.ru
 * @license     http://www.morphes.ru/license  Proprietary License
 */
/* BASED ON SNIPPET: New Module/Helper/Data.php */
/**
 * Generic helper functions for MorphesPro_FilterSlider module. This class is a must for any module even if empty.
 * @author Morphes Team
 */
class MorphesPro_FilterSlider_Helper_Data extends Mage_Core_Helper_Abstract {
	public function getUrl($name) {
		$query = array(
            $name=>'__0__,__1__',
            Mage::getBlockSingleton('page/html_pager')->getPageVarName() => null // exclude current page from urls
        );
        $params = array('_current' => true, '_m_escape' => '', '_use_rewrite' => true, '_query' => $query);
        return Mage::helper('morphes_filters')->markLayeredNavigationUrl(Mage::getUrl('*/*/*', $params), '*/*/*', $params);
	}
	public function getClearUrl($name) {
		$query = array(
            $name=>null,
            Mage::getBlockSingleton('page/html_pager')->getPageVarName() => null // exclude current page from urls
        );
        $params = array('_current' => true, '_m_escape' => '', '_use_rewrite' => true, '_query' => $query);
        return Mage::helper('morphes_filters')->markLayeredNavigationUrl(Mage::getUrl('*/*/*', $params), '*/*/*', $params);
	}
}