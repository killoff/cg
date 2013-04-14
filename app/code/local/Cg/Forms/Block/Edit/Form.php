<?php
class Cg_Forms_Block_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $formControl = new Varien_Data_Form(array('id' => 'edit_form'));
        $formControl->setUseContainer(true);
        $formControl->setMethod('post');
        $formControl->setAction($this->getUrl('*/*/save'));


        $fieldset = $formControl->addFieldset('form_form', array('legend'=>Mage::helper('cg_forms')->__('Form information')));

        $fieldset->addField('customer', 'select', array(
                'label'     => Mage::helper('cg_forms')->__('Customer'),
                'class'     => 'required-entry',
                'required'  => true,
                'name'      => 'customer_id',
                'values'    => $this->_getCustomerValues()
        ));

        $fieldset->addField('product', 'select', array(
                'label'     => Mage::helper('cg_forms')->__('Product'),
                'class'     => 'required-entry',
                'required'  => true,
                'name'      => 'product_id',
                'values'    => $this->_getProductValues()
        ));

        $fieldset->addField('description1', 'textarea', array(
                'label'     => Mage::helper('cg_forms')->__('Conclusion'),
                'class'     => 'required-entry',
                'required'  => true,
                'name'      => 'row_data[description1]',
        ));

        $fieldset->addField('description2', 'textarea', array(
                'label'     => Mage::helper('cg_forms')->__('Recommendation'),
                'class'     => 'required-entry',
                'required'  => true,
                'name'      => 'row_data[description2]',
        ));

        if (Mage::registry('current_form')) {
            $formControl->setValues(Mage::registry('current_form')->getData());
        }

        $this->setForm($formControl);
        return parent::_prepareForm();
    }

    protected function _getCustomerValues()
    {
        /** @var $customers Mage_Customer_Model_Resource_Customer_Collection */
        $customers = Mage::getModel('customer/customer')->getCollection()->addNameToSelect();
        $result = array();
        foreach ($customers as $person) {
            $result[] = array('value' => $person->getId(), 'label' => $person->getName());
        }
        return $result;
    }

    protected function _getProductValues()
    {
        /** @var $customers Mage_Customer_Model_Resource_Customer_Collection */
        $products = Mage::getModel('cg_product/product')->getCollection();
        $result = array();
        foreach ($products as $product) {
            $result[] = array('value' => $product->getId(), 'label' => $product->getTitle());
        }
        return $result;
    }

//    protected function _prepareForm()
//    {
//        $form = new Varien_Data_Form(array(
//                                        'id' => 'edit_form',
//                                        'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
//                                        'method' => 'post',
//                                     )
//        );
//
//        $form->setUseContainer(true);
//        $this->setForm($form);
//        return parent::_prepareForm();
//    }
}
