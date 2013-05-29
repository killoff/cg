<?php
$installer = $this;
$installer->startSetup();

$installer->run(
    "CREATE TABLE `{$installer->getTable('cg_employee/product')}` (
    user_id int(11) unsigned NOT NULL,
    product_id int(11) unsigned NOT NULL,
    UNIQUE KEY user_id (user_id,product_id),
    KEY product_id (product_id),
    CONSTRAINT cg_employee_product_ibfk_2 FOREIGN KEY (product_id) REFERENCES `{$installer->getTable('cg_product/product')}` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT cg_employee_product_ibfk_1 FOREIGN KEY (user_id) REFERENCES `{$installer->getTable('admin/user')}` (user_id) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8"
);

$installer->run(
    "CREATE TABLE `{$installer->getTable('cg_employee/schedule')}` (
    schedule_id int(11) NOT NULL AUTO_INCREMENT,
    user_id int(11) unsigned NOT NULL,
    time_start datetime NOT NULL,
    time_end datetime NOT NULL,
    type smallint(6) NOT NULL,
    PRIMARY KEY (schedule_id),
    KEY user_id (user_id),
    CONSTRAINT cg_employee_schedule_ibfk_1 FOREIGN KEY (user_id) REFERENCES `{$installer->getTable('admin/user')}` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8"
);

$installer->endSetup();
