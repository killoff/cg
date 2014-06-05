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
        $fieldset->addField('category_id', 'select',
            array(
                'label'     => Mage::helper('cg_product')->__('Category'),
                'class'     => 'required-entry',
                'required'  => true,
                'name'      => 'data[category_id]',
                'values'    => $this->getCategoryArray()
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
                 'class'     => 'required-entry validate-zero-or-greater',
                 'required'  => true,
                 'name'      => 'data[price]',
            ));
        $fieldset->addField('duration', 'text',
            array(
                 'label'     => Mage::helper('cg_product')->__('Duration'),
                 'class'     => 'required-entry validate-zero-or-greater',
                 'required'  => true,
                 'name'      => 'data[duration]',
            ));

        if (true /*Mage::getSingleton('admin/session')->isAllowed('cg_product/permissions')*/) {
            $fieldset->addField('user_roles', 'checkboxes',
                array(
                     'label'     => Mage::helper('cg_product')->__('Allowed for'),
                     'class'     => 'required-entry',
                     'required'  => true,
                     'name'      => 'user_roles[]',
                     'values'    => $this->getPermissionRolesArray()
                ));

            if ($this->_getCurrentProduct()->getId()) {
                $form->getElement('user_roles')->setChecked($this->_getCurrentProduct()->getRoleIds());
            }
        }

        $fieldset->addField('protocol', 'textarea',
            array(
                'label'     => Mage::helper('cg_product')->__('Protocol'),
                'name'      => 'data[protocol]',
            ));


        $form->setValues(Mage::registry('current_product')->getData());

        $this->setForm($form);
        return parent::_prepareForm();
    }
    // @TODO refactor add emty value option to dropdown

    public function getCategoryArray()
    {
        $collection = Mage::getResourceModel('cg_product/category_collection');
        $result = array(array('value' => '', 'label' => '...'));
        $result = array_merge($result, $collection->load()->toOptionArray());
        return $result;
    }

    public function getPermissionRolesArray()
    {
        return Mage::getModel("admin/roles")->getCollection()->toOptionArray();

    }

    protected function _getCurrentProduct()
    {
        return Mage::registry('current_product');
    }
}
