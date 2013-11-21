<?php
class Cg_Register_Block_Start extends Mage_Adminhtml_Block_Template
{
    protected $_template = 'cg/register/start.phtml';

    protected function _construct()
    {
        parent::_construct();

        $customerId = $this->getRequest()->get('customer_id');
        if ($customerId) {
            $customer = Mage::getModel('customer/customer')->load($customerId);
            $this->setCustomer($customer);
        }
    }

    public function getProductsTree()
    {
        $collection = Mage::getModel('cg_product/category')->getCollection();
        foreach ($collection as $category) {
            $products = $category->getProducts();
            $category->setProductsCollection($products);
        }
        return $collection;
    }

    public function getUsers()
    {
        return Mage::getModel('admin/user')->getCollection();
    }
}
