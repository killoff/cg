<?php
class Cg_Product_Adminhtml_ProductController extends Mage_Adminhtml_Controller_Action
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
            ->_setActiveMenu('product/product')
//            ->_addBreadcrumb(Mage::helper('cg_product')->__('CMS'), Mage::helper('cms')->__('CMS'))
            ->_addBreadcrumb(Mage::helper('cms')->__('Manage Product Product'), Mage::helper('cms')->__('Manage Product Product'))
        ;
        return $this;
    }

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->_title($this->__('Products'))->_title($this->__('Manage Products'));

        if ($this->getRequest()->getQuery('ajax')) {
            $this->_forward('grid');
            return;
        }
        $this->loadLayout();

        /**
         * Set active menu item
         */
        $this->_setActiveMenu('product/manage');

        /**
         * Append products block to content
         */
        $this->_addContent(
            $this->getLayout()->createBlock('cg_product/list', 'product_list')
        );

        /**
         * Add breadcrumb item
         */
//        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Products'), Mage::helper('adminhtml')->__('Products'));
//        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Manage Products'), Mage::helper('adminhtml')->__('Manage Products'));

        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->getResponse()->setBody($this->getLayout()->createBlock('cg_product/product_grid')->toHtml());
    }

    public function newAction()
    {
        $product = Mage::getModel('cg_product/product');
        if ($this->getRequest()->getParam('id')) {
            $product->load($this->getRequest()->getParam('id'));
        }
        Mage::register('current_product', $product);
        $this->loadLayout();
        $this->_addContent(
            $this->getLayout()->createBlock('cg_product/edit')
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
        /** @var $collection Cg_Product_Model_Resource_Product_Collection */

            $collection = Mage::getResourceModel('product/product_collection')
            ->addNameToSelect()
            ->addAttributeToFilter(array(
                array('attribute' => 'firstname', 'like' => $q.'%'),
                array('attribute' => 'lastname', 'like' => $q.'%')
            ))
            ->setPage(1, 10)
            ->load();

        $result = array();
        foreach ($collection->getItems() as $product) {
            $result[] = array(
                'id' => $product->getId(),
                'label' => $product->getName(),
                'value' => $product->getName()
            );
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    public function saveAction()
    {
        try {
            /** @var $product Cg_Product_Model_Product */
            $product = Mage::getModel('cg_product/product');
            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $product->load($id);
            } else {
                $date = new Zend_Date(time(), null, 'ru_RU');
                $product->setData('created_at', $date->toString(Varien_Date::DATETIME_INTERNAL_FORMAT));
            }
            $product->addData($this->getRequest()->getParam('data'));
            $product->setAdminId(Mage::getSingleton('admin/session')->getUser()->getId());
            $product->save();
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('cg_product')->__('Product has been saved successfully.'));
            $this->_redirect('*/*/index');

        } catch (Exception $e) {

        }


    }
    public function deleteAction()
    {
        try {
            /** @var $product Cg_Product_Model_Product */
            $product = Mage::getModel('cg_product/product')->load($this->getRequest()->getParam('id'))->delete();
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('cg_product')->__('Product has been deleted successfully.'));
            $this->_redirect('*/*/');
        } catch (Exception $e) {

        }
    }

    public function printAction()
    {
        $product = Mage::getModel('cg_product/product')->load($this->getRequest()->getParam('id'));
        Mage::register('current_product', $product);
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('cg_product/product_print')->toHtml()
        );
    }

}
