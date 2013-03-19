<?php
$installer = $this;
$installer->startSetup();

$installer->run(
    "CREATE TABLE `{$installer->getTable('cg_product/category')}` (
      category_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
      title VARCHAR(255) NOT NULL,
      parent_id INT(10) UNSIGNED DEFAULT NULL,
      PRIMARY KEY (category_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
);

$installer->run(
    "CREATE TABLE `{$installer->getTable('cg_product/product')}` (
      product_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
      title VARCHAR(500) NOT NULL,
      price DECIMAL(8,2) UNSIGNED NOT NULL,
      category_id INT(10) UNSIGNED NOT NULL,
      PRIMARY KEY (product_id),
      KEY FK_CATEGORY (category_id),
      CONSTRAINT CATEGORY FOREIGN KEY (category_id) REFERENCES {$this->getTable('cg_product/category')} (category_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
);

$installer->endSetup();
