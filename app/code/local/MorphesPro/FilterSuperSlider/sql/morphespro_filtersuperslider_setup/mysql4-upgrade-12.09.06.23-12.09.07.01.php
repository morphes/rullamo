<?php
/** 
 * @category    Morphes
 * @package     MorphesPro_FilterSuperSlider
 * @copyright   Copyright (c) http://www.morphes.ru
 * @license     http://www.morphes.ru/license  Proprietary License
 */
if (defined('COMPILER_INCLUDE_PATH')) {
	throw new Exception(Mage::helper('morphes_core')->__('This Magento installation contains pending database installation/upgrade scripts. Please turn off Magento compilation feature while installing/upgrading new modules in Admin Panel menu System->Tools->Compilation.'));
}
/* @var $installer Mage_Core_Model_Resource_Setup */$installer = $this;
$installer->startSetup();

$table = 'morphes_filters/filter2';
$installer->run("
    ALTER TABLE `{$this->getTable($table)}` ADD COLUMN (
        `thousand_separator` tinyint NOT NULL default '0'
    );
");

$table = 'morphes_filters/filter2_store';
$installer->run("
    ALTER TABLE `{$this->getTable($table)}` ADD COLUMN (
        `thousand_separator` tinyint NOT NULL default '0'
    );
");

$installer->endSetup();

if (!Mage::registry('m_run_db_replication')) {
    Mage::register('m_run_db_replication', true);
}
