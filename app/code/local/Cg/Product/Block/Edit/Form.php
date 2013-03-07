<?php

class Cg_Product_Block_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(
            array(
                'id' => 'edit_form',
                'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
                'method' => 'post',
                'use_container' => true
            )
        );
        $fieldset = $form->addFieldset('product_form',
            array(
                 'legend'=>Mage::helper('cg_product')->__('Product information'),
                 'class' => 'fieldset-wide',
            )
        );
        $fieldset->addField('Category', 'select',
            array(
                'label'     => Mage::helper('cg_product')->__('Category'),
                'class'     => 'required-entry',
                'required'  => true,
                'name'      => 'Category',
                'values'    => $this->getEmployeesArray()
            ));
        $fieldset->addField('title', 'text',
            array(
                 'label'     => Mage::helper('cg_product')->__('Title'),
                 'class'     => 'required-entry',
                 'required'  => true,
                 'name'      => 'data[title]',
            ));
        $fieldset->addField('price', 'text',
            array(
                 'label'     => Mage::helper('cg_product')->__('Price'),
                 'class'     => 'required-entry price',
                 'required'  => true,
                 'name'      => 'data[price]',
            ));

        $form->setValues(Mage::registry('current_product')->getData());

        $this->setForm($form);
        return parent::_prepareForm();
    }
    public function getEmployeesArray()
    {
        return array(
            array('value' => '', 'label' => $this->__('-- Please select --')),
            array('value'     => 'Головач Роман Эдуардович', 'label'     => 'Головач Роман Эдуардович'),
            array('value'     => 'Головешко Таисия Николаевна', 'label'     => 'Головешко Таисия Николаевна'),
            array('value'     => 'Гринчак Станислав Владимиров', 'label'     => 'Гринчак Станислав Владимиров'),
            array('value'     => 'Карпович Лариса Георгиевна', 'label'     => 'Карпович Лариса Георгиевна'),
            array('value'     => 'Ковганич Татьяна Александровн', 'label'     => 'Ковганич Татьяна Александровн'),
            array('value'     => 'Лапшина Галина Николаевна', 'label'     => 'Лапшина Галина Николаевна'),
            array('value'     => 'Мартинчук Олександр Олександро', 'label'     => 'Мартинчук Олександр Олександро'),
            array('value'     => 'Марченкова Анна Петровна', 'label'     => 'Марченкова Анна Петровна'),
            array('value'     => 'Матиящук Ирина Георгиевна', 'label'     => 'Матиящук Ирина Георгиевна'),
            array('value'     => 'Палиенко Наталья Валерьевна', 'label'     => 'Палиенко Наталья Валерьевна'),
            array('value'     => 'Парсенюк Лилия Дмитриевна', 'label'     => 'Парсенюк Лилия Дмитриевна'),
            array('value'     => 'Пилипенко Кирилл Владимирович', 'label'     => 'Пилипенко Кирилл Владимирович'),
            array('value'     => 'Прокопович Егор Владимирович', 'label'     => 'Прокопович Егор Владимирович'),
            array('value'     => 'Солнцева Тамила Михайловна', 'label'     => 'Солнцева Тамила Михайловна'),
            array('value'     => 'Стременюк Оксана Тарасовна', 'label'     => 'Стременюк Оксана Тарасовна'),
            array('value'     => 'Тер-Вартаньян Семен  Христофорович', 'label'     => 'Тер-Вартаньян Семен  Христофорович'),
            array('value'     => 'Шебеко Наталья Викторовна', 'label'     => 'Шебеко Наталья Викторовна'),
            array('value'     => 'Шоптенко Татьяна Васильевна', 'label'     => 'Шоптенко Татьяна Васильевна'),
        );
    }
}
