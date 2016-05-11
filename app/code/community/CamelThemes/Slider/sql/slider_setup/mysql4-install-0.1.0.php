<?php

$installer = $this;
$installer->startSetup();
$installer->run("
DROP TABLE IF EXISTS `{$installer->getTable('slider/slider')}`;
CREATE TABLE `{$installer->getTable('slider/slider')}` (
`id_camelthemes_slider` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `title` VARCHAR(500) NOT NULL ,
 `description` TEXT NOT NULL ,
 `imagename` VARCHAR(500) NOT NULL ,
 `url` VARCHAR(500) NOT NULL ,
 `status` VARCHAR(10) NOT NULL
 
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");
$installer->endSetup();