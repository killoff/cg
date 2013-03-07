<?php
class Cg_Product_Model_Resource_Product extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Initialize resource model
     *
     */
    protected function _construct()
    {
        $this->_init('cg_product/product', 'product_id');
    }
}
