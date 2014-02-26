<?php
$installer = $this;
$installer->startSetup();
$setup = $installer->getConnection();
$installer->addAttribute('customer', 'uniqid', array(
                                                     'label'        => 'Орхид ID',
                                                     'visible'      => 1,
                                                     'required'     => 0,
                                                     'position'     => 90,
                                                     'type'      => 'varchar',
                                                     'input'    => 'text'
                                                ));

$installer->addAttribute('customer', 'profession', array(
                                                        'label'        => 'Профессия',
                                                        'visible'      => 1,
                                                        'required'     => 0,
                                                        'position'     => 95,
                                                        'type'         => 'varchar',
                                                        'input'    => 'text'
                                                   ));

$installer->addAttribute('customer', 'comment', array(
                                                     'label'        => 'Комментарий',
                                                     'visible'      => 1,
                                                     'required'     => 0,
                                                     'position'     => 100,
                                                     'type'      => 'varchar',
                                                     'input'    => 'textarea'
                                                ));


$installer->endSetup();
