<?php
$installer = $this;
$installer->startSetup();
$installer->run("
-- DROP TABLE IF EXISTS {$this->getTable('geoip')};
CREATE TABLE {$this->getTable('geoip')} (
  `geoip_id` int(11) unsigned NOT NULL auto_increment,
  `code_city` varchar(255) NOT NULL default '',
  `ip` text NOT NULL default '';
");
$installer->endSetup();
