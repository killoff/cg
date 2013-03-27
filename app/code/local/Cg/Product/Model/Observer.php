<?php
class Cg_Product_Model_Observer
{
    /**
     * Add Tab Products to Permissions Role Form
     * @param Varien_Event_Observer $observer
     * @return Cg_Product_Model_Observer
     */
    public function addProductsToRoleForm(Varien_Event_Observer $observer)
    {
        $block = $observer->getEvent()->getBlock();
        $block->addTab('products', array(
                                    'label'     => Mage::helper('cg_product')->__('Products'),
                                    'title'     => Mage::helper('cg_product')->__('Products'),
                                    'content'   =>  ''
                               ));
        return $this;
    }


}
