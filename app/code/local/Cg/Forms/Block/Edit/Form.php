<?php
class Cg_Forms_Block_Edit_Form extends Cg_Kernel_Block_Widget_Form
{
    /**
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $formControl = new Varien_Data_Form(array('id' => 'edit_form'));
        $formControl->setUseContainer(true);
        $formControl->setMethod('post');
        $formControl->setAction($this->getUrl('*/*/save', array('_current' => true)));


        $fieldset = $formControl->addFieldset('form_form', array('legend'=>Mage::helper('cg_forms')->__('Form information'), 'class'     => 'fieldset-wide'));

        $this->_addElementTypes($fieldset);
        $fieldset->addField('customer_id', 'note',
            array(
                'label'     => Mage::helper('cg_forms')->__('Customer'),
                'text'    => Mage::registry('current_customer')->getName()
             )
        );
        $fieldset->addField('product_id', 'note',
            array(
                'label'     => Mage::helper('cg_forms')->__('Product'),
                'text'    => Mage::registry('current_product')->getTitle()
             )
        );
/*
        $fieldset->addField('customer_id', 'select', array(
                'label'     => Mage::helper('cg_forms')->__('Customer'),
                'class'     => 'required-entry',
                'required'  => true,
                'name'      => 'customer_id',
                'values'    => $this->_getCustomerValues()
        ));

        $fieldset->addField('product_id', 'select', array(
                'label'     => Mage::helper('cg_forms')->__('Product'),
                'class'     => 'required-entry',
                'required'  => true,
                'name'      => 'product_id',
                'values'    => $this->_getProductValues()
        ));
*/

        $fieldset->addField('description1', 'wysiwyg', array(
                'label'     => Mage::helper('cg_forms')->__('Conclusion'),
                'name'      => 'row_data[description1]',
        ));

//        $fieldset->addField('files', 'uploader', array(
//                'label'     => Mage::helper('cg_forms')->__('Files'),
//                'name'      => 'files',
//                'server_url'    => $this->getUrl('*/*/upload', array('_current' => true, 'form_key' => Mage::getSingleton('core/session')->getFormKey()))
//        ));

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
