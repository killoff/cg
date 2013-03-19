<?php
class Cg_Product_Model_Resource_Category extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Initialize resource model
     *
     */
    protected function _construct()
    {
        $this->_init('cg_product/category', 'category_id');
    }
}
