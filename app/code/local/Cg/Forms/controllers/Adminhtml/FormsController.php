<?php
class Cg_Forms_Adminhtml_FormsController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Init actions
     *
     * @return Cg_Forms_Adminhtml_FormsController
     */
    protected function _initAction()
    {
        $this->_setActiveMenu('customer/form');
        $this->_title($this->__('Customer Forms'));

        return $this;
    }

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_initAction();

        if ($this->getRequest()->getQuery('ajax')) {
            $this->_forward('grid');
            return;
        }

        $this->_addContent($this->getLayout()->createBlock('cg_forms/list'));
        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->loadLayout()->renderLayout();
        return $this;
        $this->getLayout()->getUpdate()
            ->addHandle(strtolower($this->getFullActionName()));
        $this->loadLayoutUpdates()->generateLayoutXml()->generateLayoutBlocks();
        $this->renderLayout();
    }

    public function newAction()
    {
        $form = Mage::getModel('cg_forms/form');
        if ($this->getRequest()->getParam('id')) {
            $form->load($this->getRequest()->getParam('id'));
        }
        Mage::register('current_form', $form);

        $productId = $this->getRequest()->getParam('product_id', $form->getProductId());
        if ($productId) {
            $product = Mage::getModel('cg_product/product')->load($productId);
            Mage::register('current_product', $product);
        } else {
            $this->loadLayout();
            $this->_addContent($this->getLayout()->createBlock('cg_forms/edit_product'));
            $this->renderLayout();
            return;
        }

        $customerId = $this->getRequest()->getParam('customer_id', $form->getCustomerId());
        if ($customerId) {
            $customer = Mage::getModel('customer/customer')->load($customerId);
            Mage::register('current_customer', $customer);
        } else {
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('cg_forms')->__('Select patient.'));
            $this->_redirect('*/customer');
            return;
        }

        $this->loadLayout();
        $this->_initAction();
        $this->_title($this->__('New Form'));

        $this->_addContent(
            $this->getLayout()->createBlock('cg_forms/edit')
        );
        $this->renderLayout();
    }

    public function editAction()
    {
        $this->_forward('new');
    }

    public function searchAction()
    {
        $q = $this->getRequest()->getParam('term');
        /** @var $collection Cg_Forms_Model_Resource_Visit_Collection */

        $collection = Mage::getResourceModel('customer/customer_collection')
            ->addNameToSelect()
            ->addAttributeToFilter(array(
                array('attribute' => 'firstname', 'like' => $q.'%'),
                array('attribute' => 'lastname', 'like' => $q.'%')
            ))
            ->setPage(1, 10)
            ->load();

        $result = array();
        foreach ($collection->getItems() as $customer) {
            $result[] = array(
                'id' => $customer->getId(),
                'label' => $customer->getName(),
                'value' => $customer->getName()
            );
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    public function saveAction()
    {
        try {
            /** @var $visit Cg_Forms_Model_Visit */
            $form = Mage::getModel('cg_forms/form');
            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $form->load($id);
            } else {
                $form->setCustomerId($this->getRequest()->getParam('customer_id'));
                $form->setProductId($this->getRequest()->getParam('product_id'));
                $form->setAdminId(Mage::getSingleton('admin/session')->getUser()->getId());
            }
            $form->setRowData($this->getRequest()->getParam('row_data'));
//            $date = new Zend_Date($this->getRequest()->getParam('user_date'), null, 'ru_RU');
//            $visit->setData('user_date', $date->toString(Varien_Date::DATETIME_INTERNAL_FORMAT));
            $form->save();
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('cg_forms')->__('Form has been saved successfully.'));
//            $this->_redirect('*/*/index', array('id' => $visit->getId()));
            $this->_redirect('*/customer/edit', array(
                                                     'id' => $form->getCustomerId(),
                                                     'tab' => 'customer_info_tabs_customer_edit_tab_forms'
                                                ));

        } catch (Exception $e) {

        }


    }
    public function deleteAction()
    {
        try {
            /** @var $visit Cg_Forms_Model_Visit */
            $visit = Mage::getModel('cg_forms/form')->load($this->getRequest()->getParam('id'))->delete();
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('cg_forms')->__('Form has been deleted successfully.'));
            $this->_redirect('*/*/');
        } catch (Exception $e) {

        }
    }

    public function printAction()
    {
        $visit = Mage::getModel('cg_forms/visit')->load($this->getRequest()->getParam('id'));
        Mage::register('current_visit', $visit);
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('cg_forms/visit_print')->toHtml()
        );
    }

    public function uploadAction()
    {
        $this->getResponse()->setBody(json_encode(array('success' => true)));
    }
}
