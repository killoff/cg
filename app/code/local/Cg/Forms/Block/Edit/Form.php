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

class Cg_Forms_Block_Visit_Edit_Form extends Cg_Kernel_Block_Adminhtml_Template_Smarty
{

    protected $_template = 'form/summary.tpl';

    protected function _beforeToHtml()
    {
        $visit = Mage::registry('current_visit');
        $this->assign('customerSearchUrl', $this->getUrl('*/*/search'));
        $this->assign('formActionUrl', $this->getUrl('*/*/save'));
        $this->assign('formKey', $this->getBlockHtml('formkey'));
        $this->assign('data', $visit->getData());
        $customerId = $visit->getData('customer_id') ? $visit->getData('customer_id') : $this->getRequest()->getParam('customer_id');
        $this->assign('customer_id', $customerId);
        $customer = Mage::getModel('customer/customer')->load($customerId);
        $this->assign('customer', $customer->getData());
        $form = new Varien_Data_Form(array('id' => 'edit_form'));
        $fieldset = $form->addFieldset('base_fieldset', array());
        $field = $fieldset->addField('visit1', 'editor',
            array(
                'name'      => 'row_data[visit_1]',
                'style'     => 'height:30em',
                'required'  => true,
                'config'    => Mage::getSingleton('cms/wysiwyg_config')->getConfig(),
                'value'     => $visit->getData('visit_1')
             )
        );
        $this->assign('editor1', $field->getElementHtml());

        $field = $fieldset->addField('visit2', 'editor',
            array(
                'name'      => 'row_data[visit_2]',
                'style'     => 'height:30em',
                'required'  => true,
                'config'    => Mage::getSingleton('cms/wysiwyg_config')->getConfig(),
                'value'     => $visit->getData('visit_2')
             )
        );
        $this->assign('editor2', $field->getElementHtml());
        return parent::_beforeToHtml();
    }

    /**
     * Load Wysiwyg on demand and Prepare layout
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
    }
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();


        $fieldset = $form->addFieldset('visit_form', array('legend'=>Mage::helper('cg_forms')->__('Visit information')));
        $fieldset->addField('visit_title', 'text',
            array(
                'label'     => Mage::helper('cg_forms')->__('Patient'),
                'class'     => 'required-entry',
                'required'  => true,
                'name'      => 'poll_title',
            ));
        $fieldset->addField('visit_description', 'textarea',
            array(
                'label'     => Mage::helper('cg_forms')->__('Patient'),
                'class'     => 'required-entry',
                'required'  => true,
                'name'      => 'visit_description',
            ));

//        $fieldset->addField('closed', 'select', array(
//                                                     'label'     => Mage::helper('cg_forms')->__('Status'),
//                                                     'name'      => 'closed',
//                                                     'values'    => array(
//                                                         array(
//                                                             'value'     => 1,
//                                                             'label'     => Mage::helper('cg_forms')->__('Closed'),
//                                                         ),
//
//                                                         array(
//                                                             'value'     => 0,
//                                                             'label'     => Mage::helper('cg_forms')->__('Open'),
//                                                         ),
//                                                     ),
//                                                ));
//
//        if (!Mage::app()->isSingleStoreMode()) {
//            $fieldset->addField('store_ids', 'multiselect', array(
//                                                                 'label'     => Mage::helper('cg_forms')->__('Visible In'),
//                                                                 'required'  => true,
//                                                                 'name'      => 'store_ids[]',
//                                                                 'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(),
//                                                                 'value'     => Mage::registry('poll_data')->getStoreIds(),
//                                                                 'after_element_html' => Mage::getBlockSingleton('adminhtml/store_switcher')->getHintHtml()
//                                                            ));
//        }
//        else {
//            $fieldset->addField('store_ids', 'hidden', array(
//                                                            'name'      => 'store_ids[]',
//                                                            'value'     => Mage::app()->getStore(true)->getId()
//                                                       ));
//            Mage::registry('poll_data')->setStoreIds(Mage::app()->getStore(true)->getId());
//        }


//        if( Mage::getSingleton('adminhtml/session')->getPollData() ) {
//            $form->setValues(Mage::getSingleton('adminhtml/session')->getPollData());
//            Mage::getSingleton('adminhtml/session')->setPollData(null);
//        } elseif( Mage::registry('poll_data') ) {
//            $form->setValues(Mage::registry('poll_data')->getData());
//
//            $fieldset->addField('was_closed', 'hidden', array(
//                                                             'name'      => 'was_closed',
//                                                             'no_span'   => true,
//                                                             'value'     => Mage::registry('poll_data')->getClosed()
//                                                        ));
//        }

        $this->setForm($form);
        return parent::_prepareForm();
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
