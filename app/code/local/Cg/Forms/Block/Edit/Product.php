<?php
class Cg_Forms_Block_Edit_Product extends Mage_Adminhtml_Block_Template
{
    protected $_template = 'cg/forms/edit/product.phtml';

    public function getEmployeeProducts()
    {
        $userId =  Mage::getSingleton('admin/session')->getUser()->getId();
        $productIds = Mage::getResourceHelper('cg_employee')->getProductIds($userId);
        $collection = Mage::getResourceModel('cg_product/product_collection')
            ->addFieldToFilter('product_id', array('in' => $productIds));
        return $collection;
    }

    public function getCustomer()
    {
        return Mage::getModel('customer/customer')->load($this->getRequest()->getParam('customer_id'));
    }

    public function getBackUrl()
    {
        return $this->getUrl('*/customer/edit', array('id' => $this->getRequest()->getParam('customer_id')));
    }

    public function getNewFormUrl($product)
    {
        return $this->getUrl('*/*/*', array('product_id' => $product->getId(), '_current' => true));
    }
}

