<?php
class Cg_Forms_Block_Template_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
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
        $fieldset = $form->addFieldset('template_form',
            array(
                 'legend'=>Mage::helper('cg_forms')->__('Form Template'),
                 'class' => 'fieldset-wide',
            )
        );
        $fieldset->addField('name', 'text',
            array(
                 'label'     => Mage::helper('cg_office')->__('Name'),
                 'class'     => 'required-entry',
                 'required'  => true,
                 'name'      => 'data[name]',
            ));

        $form->setValues(Mage::registry('current_template')->getData());

        $this->setForm($form);
        return parent::_prepareForm();
    }
}
