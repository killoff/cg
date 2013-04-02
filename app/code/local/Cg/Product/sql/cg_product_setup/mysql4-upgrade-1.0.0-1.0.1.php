<?php
$installer = $this;
$installer->startSetup();

$installer->run(
    "CREATE TABLE `{$installer->getTable('cg_product/user_roles')}` (
      product_id INT(10) UNSIGNED NOT NULL,
      role_id INT(10) UNSIGNED NOT NULL,
      UNIQUE KEY `product_role` (`product_id`,`role_id`),
      KEY `fk_role` (role_id),
      CONSTRAINT `c_role` FOREIGN KEY (`role_id`) REFERENCES {$this->getTable('admin/role')} (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE,
      CONSTRAINT `c_product` FOREIGN KEY (`product_id`) REFERENCES {$this->getTable('cg_product/product')} (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
);

$installer->endSetup();
