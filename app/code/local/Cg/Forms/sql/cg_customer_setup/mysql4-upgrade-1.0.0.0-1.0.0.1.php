<?php
/*
$installer = $this;
$installer->startSetup();
$installer->run("
CREATE TABLE `{$installer->getTable('cg_forms/reservation')}` (
  `reservation_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee` VARCHAR(255) NOT NULL,
  `status` VARCHAR(255) NOT NULL,
  `period` VARCHAR(255) NOT NULL,
  `fio` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(255) NOT NULL,
  `address` VARCHAR(255) NOT NULL,
  `comment` TEXT NOT NULL,
  `admin_id` INT(10) UNSIGNED NOT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`reservation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Patients reservations';
");

$installer->endSetup();
*/
