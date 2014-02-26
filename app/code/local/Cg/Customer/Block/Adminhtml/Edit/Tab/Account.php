<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Customer account form block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Cg_Customer_Block_Adminhtml_Edit_Tab_Account extends Mage_Adminhtml_Block_Customer_Edit_Tab_Account
{
    /**
     * Initialize form
     *
     * @return Mage_Adminhtml_Block_Customer_Edit_Tab_Account
     */
    public function initForm()
    {
        parent::initForm();
        $form = $this->getForm();
        /** @var Varien_Data_Form_Element_Fieldset $baseFieldSet */
        $baseFieldSet = $form->getElement('base_fieldset');
        $baseFieldSet
            ->removeField('group_id')
            ->removeField('website_id')
            ->removeField('prefix')
            ->removeField('suffix')
            ->removeField('taxvat')
            ->removeField('gender')
            ->removeField('created_in');

        $customer = Mage::registry('current_customer');
        $age = $customer->getAge();
        if ($age) {
            $form->getElement('dob')->setAfterElementHtml(Mage::helper('cg_kernel')->getAgeString($age));
        }

        $gender = $baseFieldSet->addField('gender', 'select',
            array(
                 'name' => 'account[gender]',
                 'label' => Mage::helper('cg_customer')->__("Gender")
            ),
            'dob'
        );
        $gender->setValues(array(
            array('label' => Mage::helper('cg_customer')->__(""), 'value' => '0'),
            array('label' => Mage::helper('cg_customer')->__("Male"), 'value' => '1'),
            array('label' => Mage::helper('cg_customer')->__("Female"), 'value' => '2'),
        ));
        $gender->setValue($customer->getGender());

        $form->getElements()->remove('password_fieldset');
        return $this;
    }
}
