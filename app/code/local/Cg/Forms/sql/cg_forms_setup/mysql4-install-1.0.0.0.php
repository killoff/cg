<?php
$installer = $this;
$installer->startSetup();
$installer->run("
CREATE TABLE IF NOT EXISTS `{$installer->getTable('cg_forms/form')}` (
  `form_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  `admin_id` int(10) unsigned NOT NULL,
  `row_data` text NOT NULL,
  `created_at` datetime NOT NULL,
  `user_date` datetime NOT NULL,
  PRIMARY KEY (`form_id`),
  KEY `customer_id` (`customer_id`),
  KEY `product_id` (`product_id`),
  KEY `admin_id` (`admin_id`),
  KEY `user_date` (`user_date`),
  CONSTRAINT `cg_form_ibfk_3` FOREIGN KEY (`admin_id`) REFERENCES `{$this->getTable('admin/user')}` (`user_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `cg_form_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `{$this->getTable('customer/entity')}` (`entity_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `cg_form_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `{$installer->getTable('cg_product/product')}` (`product_id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->run("
CREATE TABLE `{$installer->getTable('cg_forms/reservation')}` (
  `reservation_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) unsigned NOT NULL,
  `status` VARCHAR(255) NOT NULL,
  `period` VARCHAR(255) NOT NULL,
  `fio` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(255) NOT NULL,
  `address` VARCHAR(255) NOT NULL,
  `comment` TEXT NOT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`reservation_id`),
  KEY `admin_id` (`admin_id`),
  CONSTRAINT `cg_reservation_ibfk_3` FOREIGN KEY (`admin_id`) REFERENCES `{$this->getTable('admin/user')}` (`user_id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();
