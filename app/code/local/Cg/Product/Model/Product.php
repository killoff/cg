<?php
class Cg_Product_Model_Product extends Mage_Core_Model_Abstract
{
    /**
     * Initialize resource model
     *
     */
    protected function _construct()
    {
        $this->_init('cg_product/product');
    }

    protected function _beforeSave()
    {
//        Mage::getResourceHelper()
    }

    protected function _afterLoad()
    {
    }

    public function getRoleIds()
    {
        return Mage::getResourceHelper('cg_product')->getProductRoleIds($this->getId());
    }
}
