<?php
/**

 */


$installer = $this;
/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */

$sort_order = 0;

foreach (Intex_Yandex_Model_Offers_VendorModel::getUsedAttributes() as $code => $attr) {
    $attr['sort_order'] = $sort_order++;
    $installer->addAttribute('catalog_product', $code, $attr);
} 

$installer->updateAttribute('catalog_product', 'manufacturer', 'is_required', 1);

