<?php
  /**
     * Mtoo Co.
     *
     * NOTICE OF LICENSE
     *
     * This source file is subject to the EULA
     * that is bundled with this package in the file LICENSE.txt.
     * It is also available through the world-wide-web at this URL:
     * http://www.magento800.com/LICENSE.txt
     *
     * @category   Mtoo
     * @package    Mtoo_Navitation
     * @copyright  Copyright (c) 2010 Mtoo Co. (http://www.magento800.com)
     * @license    http://www.magento800.com/LICENSE.txt
     */

/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */
$installer = new Mage_Eav_Model_Entity_Setup('core_setup');
$installer->startSetup();

$entityTypeId     = $installer->getEntityTypeId('catalog_category');
$attributeSetId   = $installer->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = $installer->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);

$installer->addAttribute('catalog_category', 'category_ad',  array(
    'type'     => 'text',
    'label'    => 'Category Ad',
    'input'    => 'textarea',
    'global'   => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'required' => false,
));

$installer->addAttributeToGroup(
    $entityTypeId,
    $attributeSetId,
    $attributeGroupId,
    'category_ad',
    '11'
);
$installer->updateAttribute($entityTypeId, 'category_ad', 'is_wysiwyg_enabled', 1);
$installer->updateAttribute($entityTypeId, 'category_ad', 'is_html_allowed_on_front', 1);

$installer->endSetup();
