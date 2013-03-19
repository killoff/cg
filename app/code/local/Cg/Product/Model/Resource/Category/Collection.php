<?php
class Cg_Product_Model_Resource_Category_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Define resource model
     *
     */
    protected function _construct()
    {
        $this->_init('cg_product/category');
    }

    protected function _initSelect()
    {
        $this->getSelect()->order('title ' . Zend_Db_Select::SQL_ASC);
        return parent::_initSelect();
    }

    public function toOptionArray()
    {
        return parent::_toOptionArray('category_id', 'title');
    }
}
