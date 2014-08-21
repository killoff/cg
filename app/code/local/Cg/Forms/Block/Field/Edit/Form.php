<?php
class Cg_Forms_Block_Field_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
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
        $fieldset = $form->addFieldset('field_form',
            array(
                 'legend'=>Mage::helper('cg_forms')->__('Form Field'),
                 'class' => 'fieldset-wide',
            )
        );
        $fieldset->addField('title', 'text',
            array(
                'label'     => Mage::helper('cg_forms')->__('Name'),
                'class'     => 'required-entry',
                'required'  => true,
                'name'      => 'data[name]',
            ));
        $fieldset->addField('code', 'text',
            array(
                 'label'     => Mage::helper('cg_forms')->__('Code'),
                 'class'     => 'required-entry',
                 'required'  => true,
                 'name'      => 'data[code]',
            ));
        $types = array(
            array('value' => 'text', 'label' => Mage::helper('cg_forms')->__('Text field')),
            array('value' => 'wysiwyg', 'label' => Mage::helper('cg_forms')->__('Editor')),
            array('value' => 'protocol', 'label' => Mage::helper('cg_forms')->__('Protocol')),
        );
        $fieldset->addField('type', 'select',
            array(
                 'label'     => Mage::helper('cg_forms')->__('Type'),
                 'class'     => 'required-entry',
                 'required'  => true,
                 'name'      => 'data[code]',
                 'values'    => $types
            ));

        $form->setValues(Mage::registry('current_field')->getData());

        $this->setForm($form);
        return parent::_prepareForm();
    }
}
