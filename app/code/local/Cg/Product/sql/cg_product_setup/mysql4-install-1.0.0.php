<?php
$installer = $this;
$installer->startSetup();
$installer->run("
CREATE TABLE `{$installer->getTable('cg_product/product')}` (
  `product_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(500) NOT NULL,
  `price` DECIMAL(8,2) UNSIGNED NOT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Services';
");

$installer->endSetup();
