<?php
/*
$installer = $this;
$installer->startSetup();
$installer->run("
CREATE TABLE `{$installer->getTable('cg_forms/visit')}` (
  `visit_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_id` INT(10) UNSIGNED NOT NULL,
  `admin_id` INT(10) UNSIGNED NOT NULL,
  `conclusion` TEXT NOT NULL,
  `receipt` TEXT NOT NULL,
  `row_data` TEXT NOT NULL,
  `created` DATETIME NOT NULL,
  `user_date` DATETIME NOT NULL,
  PRIMARY KEY (`visit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Patients visits';
");

$installer->endSetup();
*/
