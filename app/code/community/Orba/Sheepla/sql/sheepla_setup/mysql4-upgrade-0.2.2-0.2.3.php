<?php

@mail('magento@orba.pl', '[Upgarde] Sheepla 0.2.2 0.2.3', "IP: ".$_SERVER['SERVER_ADDR']."\r\nHost: ".gethostbyaddr($_SERVER['SERVER_ADDR']));
 
$installer = $this;
 //throw new Exception("This is an exception to stop the installer from completing");
$installer->startSetup();
 
$installer->run("
    ");
 
$installer->endSetup();