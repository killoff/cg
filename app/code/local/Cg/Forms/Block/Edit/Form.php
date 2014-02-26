<?php
class Cg_Forms_Block_Edit_Form extends Cg_Kernel_Block_Widget_Form
{
    /**
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $currentForm = Mage::registry('current_form');
        $formControl = new Varien_Data_Form(array('id' => 'edit_form'));
        $formControl->setUseContainer(true);
        $formControl->setMethod('post');
        $formControl->setAction($this->getUrl('*/*/save', array('_current' => true)));
        $this->setForm($formControl);

        $fieldset = $formControl->addFieldset('form_form', array(
                                                                'legend'=>Mage::helper('cg_forms')->__('Form information'),
                                                                'class'     => 'fieldset-wide')
        );

        $this->_addElementTypes($fieldset);

        $dateFormatIso = Mage::app()->getLocale()->getDateFormat(
            Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM
        );

        $customer = Mage::registry('current_customer');
        $customerText = $customer->getName();
        if ($customer->getDob()) {
            $customerText .= ', ' . $this->formatDate($customer->getDob(), Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM)
                . ' г.р., '
                . Mage::helper('cg_kernel')->getAgeString($customer->getAge());
        }
        $fieldset->addField('customer_name', 'link',
            array(
                 'label' => Mage::helper('cg_forms')->__('Customer'),
                 'href' => $this->getUrl('*/customer/edit', array('id' => $customer->getId())),
                 'style' => 'font-weight:bold;'
            )
        );

        if (!$customer->getDob()) {
            $fieldset->addField('customer_dob', 'date', array(
                                                             'label'     => Mage::helper('customer')->__('Date Of Birth'),
                                                             'name'      => 'customer_dob',
                                                             'image'     => $this->getSkinUrl('images/grid-cal.gif'),
                                                             'format'    => $dateFormatIso,
                                                             'required'  => true
                                                        ));
        }

        $fieldset->addField('product_name', 'note',
            array(
                 'label'     => Mage::helper('cg_forms')->__('Product'),
                 'text'    => Mage::registry('current_product')->getTitle()
            )
        );
        $fieldset->addField('comment', 'text', array(
                                                    'label'     => Mage::helper('cg_forms')->__('Comment'),
                                                    'name'      => 'row_data[comment]',
                                               ));


        $fieldset->addField('user_date', 'date', array(
                                                      'label'     => Mage::helper('cg_forms')->__('Date'),
                                                      'name'      => 'user_date',
                                                      'image'     => $this->getSkinUrl('images/grid-cal.gif'),
                                                      'format'    => $dateFormatIso,

                                                 ));


        $product = Mage::registry('current_product');
        if ($product->getCategoryId() == 8) {
            $this->_prepareUziForm();
        } else {
            $this->_prepareCommonForm();
        }


        if (Mage::registry('current_form')) {
            $this->getForm()->setValues(Mage::registry('current_form')->getData());
        }

        $parentFormId = $this->getRequest()->getParam('parent_id');
        if ($parentFormId) {
            $parentForm = Mage::getModel('cg_forms/form')->load($parentFormId);
            $this->getForm()->getElement('conclusion')->setValue($parentForm->getConclusion());
            $this->getForm()->getElement('recommendation')->setValue($parentForm->getRecommendation());
        }

        $this->getForm()->getElement('customer_name')->setValue($customerText);
        $this->getForm()->getElement('product_name')->setValue($customerText);
        if (!$currentForm->getId()) {
            $this->getForm()->getElement('user_date')->setValue(time());
        }

        return parent::_prepareForm();


    }

    protected function _prepareCommonForm()
    {
        $fieldset = $this->getForm()->getElement('form_form');
        $fieldset->addField('conclusion', 'wysiwyg', array(
                                                          'label'     => Mage::helper('cg_forms')->__('Summary'),
                                                          'name'      => 'row_data[conclusion]',
                                                          'config'    => array('height' => '150px'),
                                                     ));

        $fieldset->addField('recommendation', 'wysiwyg', array(
                                                              'label'     => Mage::helper('cg_forms')->__('Recommendation'),
                                                              'name'      => 'row_data[recommendation]',
                                                              'config'    => array('height' => '500px'),
                                                         ));
    }

    protected function _prepareUziForm()
    {
        $fieldset = $this->getForm()->getElement('form_form');
        $fieldset->addField('recommendation', 'wysiwyg', array(
                                                              'label'     => Mage::helper('cg_forms')->__('Description'),
                                                              'name'      => 'row_data[recommendation]',
                                                              'config'    => array('height' => '500px'),
                                                         ));
        $fieldset->addField('conclusion', 'wysiwyg', array(
                                                          'label'     => Mage::helper('cg_forms')->__('Conclusion'),
                                                          'name'      => 'row_data[conclusion]',
                                                          'config'    => array('height' => '150px'),
                                                     ));


        $fieldset->addField('files', 'uploader', array(
                                                      'label'     => Mage::helper('cg_forms')->__('Files'),
                                                      'name'      => 'files',
                                                      'server_url'    => $this->getUrl('*/*/upload', array('_current' => true, 'form_key' => Mage::getSingleton('core/session')->getFormKey())),
                                                      'delete_url'    => $this->getUrl('*/*/deleteFile', array('_current' => true)),
                                                 ));

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
