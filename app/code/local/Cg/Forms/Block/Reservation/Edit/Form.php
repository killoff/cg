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
 * Adminhtml poll edit form block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */

class Cg_Forms_Block_Reservation_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(
            array('id' => 'edit_form', 'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))), 'method' => 'post', 'use_container' => true)
        );
        $fieldset = $form->addFieldset('reservation_form',
            array(
                 'legend'=>Mage::helper('cg_forms')->__('Reservation information'),
                 'class' => 'fieldset-wide',
            )
        );
        $fieldset->addField('employee', 'select',
            array(
                'label'     => Mage::helper('cg_forms')->__('Employee'),
                'class'     => 'required-entry',
                'required'  => true,
                'name'      => 'data[employee]',
                'values'    => $this->getEmployeesArray()
            ));
        $fieldset->addField('status', 'select',
            array(
                'label'     => Mage::helper('cg_forms')->__('Status'),
                'class'     => 'required-entry',
                'required'  => true,
                'name'      => 'data[status]',
                'values'    => $this->getStatusesArray()
            ));
        $fieldset->addField('period', 'select',
            array(
                'label'     => Mage::helper('cg_forms')->__('When'),
                'class'     => 'required-entry',
                'required'  => true,
                'name'      => 'data[period]',
                'values'    => $this->getMonthsArray()
            ));
        $fieldset->addField('fio', 'text',
            array(
                 'label'     => Mage::helper('cg_forms')->__('Name'),
                 'class'     => 'required-entry',
                 'required'  => true,
                 'name'      => 'data[fio]',
            ));

        $fieldset->addField('phone', 'text',
            array(
                 'label'     => Mage::helper('cg_forms')->__('Phone'),
                 'class'     => 'required-entry',
                 'required'  => true,
                 'name'      => 'data[phone]',
            ));

        $fieldset->addField('address', 'text',
            array(
                 'label'     => Mage::helper('cg_forms')->__('City'),
                 'class'     => 'required-entry',
                 'required'  => true,
                 'name'      => 'data[address]',
            ));

        $fieldset->addField('comment', 'textarea',
            array(
                 'label'     => Mage::helper('cg_forms')->__('Comment'),
                 'name'      => 'data[comment]',
            ));
        $form->setValues(Mage::registry('current_reservation')->getData());

        $this->setForm($form);
        return parent::_prepareForm();
    }
    public function getEmployeesArray()
    {
        return array(
            array('value' => '', 'label' => $this->__('-- Please select --')),
            array('value'     => 'Карпович Лариса Георгиевна', 'label'     => 'Карпович Лариса Георгиевна'),
            array('value'     => 'Ковганич Татьяна Александровн', 'label'     => 'Ковганич Татьяна Александровн'),
            array('value'     => 'Лапшина Галина Николаевна', 'label'     => 'Лапшина Галина Николаевна'),
            array('value'     => 'Матиящук Ирина Георгиевна', 'label'     => 'Матиящук Ирина Георгиевна'),
            array('value'     => 'Палиенко Наталья Валерьевна', 'label'     => 'Палиенко Наталья Валерьевна'),
            array('value'     => 'Прокопович Егор Владимирович', 'label'     => 'Прокопович Егор Владимирович'),
            array('value'     => 'Солнцева Тамила Михайловна', 'label'     => 'Солнцева Тамила Михайловна'),
            array('value'     => 'Тер-Вартаньян Семен  Христофорович', 'label'     => 'Тер-Вартаньян Семен  Христофорович'),
            array('value'     => 'Шебеко Наталья Викторовна', 'label'     => 'Шебеко Наталья Викторовна'),
        );
    }

    public function getStatusesArray()
    {
        return array(
            array('value' => '', 'label' => $this->__('-- Please select --')),
            array('label' => 'СОС','value' => 'СОС'),
            array('label' => 'Х','value' => 'Х'),
            array('label' => 'ПОВТ','value' => 'ПОВТ'),
            array('label' => 'ПЕРВ','value' => 'ПЕРВ'),
        );
    }
    public function getMonthsArray()
    {
        $old = setlocale(LC_TIME, "0");
        setlocale(LC_TIME, Mage::app()->getLocale()->getLocaleCode().'.UTF-8');
        $result = array(
            array('value' => '', 'label' => $this->__('-- Please select --')),
            array('value' => 'не важно', 'label' => 'не важно'),
        );
        $now = new DateTime('@'.time());
        for($i = 0; $i < 12; $i++) {
            $month = strftime('%B \'%y', $now->getTimestamp());
            $result[] = array('label' => $month,'value' => $month);
            $now->add(new DateInterval('P1M'));
        }
        setlocale(LC_TIME, $old);
        return $result;
    }
}
