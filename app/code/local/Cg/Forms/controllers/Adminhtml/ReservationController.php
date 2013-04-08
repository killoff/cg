<?php
class Cg_Forms_Adminhtml_ReservationController extends Mage_Adminhtml_Controller_Action
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
            ->_setActiveMenu('customer/reservation')
//            ->_addBreadcrumb(Mage::helper('cg_forms')->__('CMS'), Mage::helper('cms')->__('CMS'))
            ->_addBreadcrumb(Mage::helper('cms')->__('Manage Customer Reservation'), Mage::helper('cms')->__('Manage Customer Reservation'))
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
            $this->getLayout()->createBlock('cg_forms/reservation_list')
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
        $this->getResponse()->setBody($this->getLayout()->createBlock('cg_forms/reservation_grid')->toHtml());
    }

    public function newAction()
    {
        $reservation = Mage::getModel('cg_forms/reservation');
        if ($this->getRequest()->getParam('id')) {
            $reservation->load($this->getRequest()->getParam('id'));
        }
        Mage::register('current_reservation', $reservation);
        $this->loadLayout();
        $this->_addContent(
            $this->getLayout()->createBlock('cg_forms/reservation_edit')
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
        /** @var $collection Cg_Forms_Model_Resource_Reservation_Collection */

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
            /** @var $reservation Cg_Forms_Model_Reservation */
            $reservation = Mage::getModel('cg_forms/reservation');
            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $reservation->load($id);
            } else {
                $date = new Zend_Date(time(), null, 'ru_RU');
                $reservation->setData('created_at', $date->toString(Varien_Date::DATETIME_INTERNAL_FORMAT));
            }
            $reservation->addData($this->getRequest()->getParam('data'));
            $reservation->setAdminId(Mage::getSingleton('admin/session')->getUser()->getId());
            $reservation->save();
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('cg_forms')->__('Reservation has been saved successfully.'));
            $this->_redirect('*/*/index');

        } catch (Exception $e) {

        }


    }
    public function deleteAction()
    {
        try {
            /** @var $reservation Cg_Forms_Model_Reservation */
            $reservation = Mage::getModel('cg_forms/reservation')->load($this->getRequest()->getParam('id'))->delete();
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('cg_forms')->__('Reservation has been deleted successfully.'));
            $this->_redirect('*/*/');
        } catch (Exception $e) {

        }
    }

    public function printAction()
    {
        $reservation = Mage::getModel('cg_forms/reservation')->load($this->getRequest()->getParam('id'));
        Mage::register('current_reservation', $reservation);
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('cg_forms/reservation_print')->toHtml()
        );
    }

}
