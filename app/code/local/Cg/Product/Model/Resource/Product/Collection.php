<?php
class Cg_Product_Model_Resource_Product_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Define resource model
     *
     */
    protected function _construct()
    {
        $this->_init('cg_product/product');
    }

}
