<?php
class Cg_Forms_Adminhtml_VisitController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Init actions
     *
     * @return Mage_Adminhtml_Cms_PageController
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        $this //->loadLayout()
            ->_setActiveMenu('customer/visit')
//            ->_addBreadcrumb(Mage::helper('cg_forms')->__('CMS'), Mage::helper('cms')->__('CMS'))
            ->_addBreadcrumb(Mage::helper('cms')->__('Manage Customer Visits'), Mage::helper('cms')->__('Manage Customer Visits'))
        ;
        return $this;
    }

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->_title($this->__('Customers'))->_title($this->__('Manage Customers'));

        if ($this->getRequest()->getQuery('ajax')) {
            $this->_forward('grid');
            return;
        }
        $this->loadLayout();

        /**
         * Set active menu item
         */
        $this->_setActiveMenu('customer/manage');

        /**
         * Append customers block to content
         */
        $this->_addContent(
            $this->getLayout()->createBlock('cg_forms/visit', 'visit_list')
        );

        /**
         * Add breadcrumb item
         */
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Customers'), Mage::helper('adminhtml')->__('Customers'));
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Manage Customers'), Mage::helper('adminhtml')->__('Manage Customers'));

        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->getResponse()->setBody($this->getLayout()->createBlock('cg_forms/visit_grid')->toHtml());
    }

    public function newAction()
    {
        $visit = Mage::getModel('cg_forms/visit');
        if ($this->getRequest()->getParam('id')) {
            $visit->load($this->getRequest()->getParam('id'));
        } elseif (!$this->getRequest()->getParam('customer_id')) {
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('cg_forms')->__('Для добавления осмотра выберите пациента.'));
            $this->_redirect('*/customer');
            return;
        }
        Mage::register('current_visit', $visit);
        $this->loadLayout();
        $this->_addContent(
            $this->getLayout()->createBlock('cg_forms/visit_edit')
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
            $visit = Mage::getModel('cg_forms/visit');
            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $visit->load($id);
            }
            $visit->addData($this->getRequest()->getParam('data'));
            $visit->setRowData($this->getRequest()->getParam('row_data'));
            $visit->setAdminId(Mage::getSingleton('admin/session')->getUser()->getId());
            $date = new Zend_Date($this->getRequest()->getParam('user_date'), null, 'ru_RU');
            $visit->setData('user_date', $date->toString(Varien_Date::DATETIME_INTERNAL_FORMAT));
            $visit->save();
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('cg_forms')->__('Visit has been saved successfully.'));
            $this->_redirect('*/*/edit', array('id' => $visit->getId()));

        } catch (Exception $e) {

        }


    }
    public function deleteAction()
    {
        try {
            /** @var $visit Cg_Forms_Model_Visit */
            $visit = Mage::getModel('cg_forms/visit')->load($this->getRequest()->getParam('id'))->delete();
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('cg_forms')->__('Visit has been deleted successfully.'));
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

}
