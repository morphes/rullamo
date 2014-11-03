<?php
/**
 * @category    Morphes
 * @package     Morphes_Filters
 * @copyright   Copyright (c) http://www.morphes.ru
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/* BASED ON SNIPPET: Resources/Install/upgrade script */
/* @var $installer Morphes_Filters_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->installEntities();
$installer->updateDefaultMaskFields(Morphes_Filters_Model_Filter::ENTITY);

$installer->endSetup();