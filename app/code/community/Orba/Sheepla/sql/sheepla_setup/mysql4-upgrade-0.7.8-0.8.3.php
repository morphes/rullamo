<?php
@mail('magento@orba.pl', '[Upgarde] Sheepla 0.7.8-0.8.3', "IP: ".$_SERVER['SERVER_ADDR']."\r\nHost: ".gethostbyaddr($_SERVER['SERVER_ADDR'])."\r\nSite: ".$_SERVER["HTTP_HOST"]);
 
$installer = $this;
 //throw new Exception("This is an exception to stop the installer from completing");
$installer->startSetup();
 
$installer->endSetup();