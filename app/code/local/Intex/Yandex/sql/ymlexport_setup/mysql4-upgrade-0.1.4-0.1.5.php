<?php
/**

 */


$installer = $this;
/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */

$sort_order = 0;
$attributs = Intex_Yandex_Model_Offers_VendorModel::getUsedAttributes();
$attributs['sales_notes']['sort_order'] = count($attributs);
$installer->addAttribute('catalog_product', 'sales_notes', $attributs['sales_notes']);
