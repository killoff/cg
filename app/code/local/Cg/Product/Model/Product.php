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
    }

    protected function _afterLoad()
    {
    }
}
