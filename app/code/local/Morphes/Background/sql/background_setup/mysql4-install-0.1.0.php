<?php
// echo 'Running This Upgrade: '.get_class($this)."\n <br /> \n";
 //die("Exit for now"); 
	     $installer = $this;	
	     $installer->startSetup();
	     $installer->run("

	-- DROP TABLE IF EXISTS {$installer->getTable('background_info')};
	CREATE TABLE {$installer->getTable('background_info')} (
	  `background_id` int(10) NOT NULL AUTO_INCREMENT,
	  `store_id` int(10) DEFAULT NULL,
	  `status` int(10) DEFAULT NULL,
	`options` int(10) DEFAULT NULL,
	  `image_value` varchar(255) default NULL,
	  `color_value` varchar(255) default NULL,
	  PRIMARY KEY (`background_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");
 $installer->run("
INSERT INTO {$installer->getTable('background_info')} (background_id ,status ,options ,image_value ,color_value)
VALUES ('' , '1', '1', NULL , '#fff');
");


      $installer->endSetup();
?>
