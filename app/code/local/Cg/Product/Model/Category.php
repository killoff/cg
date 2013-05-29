<?php
class Cg_Product_Model_Category extends Mage_Core_Model_Abstract
{
    /**
     * Initialize resource model
     *
     */
    protected function _construct()
    {
        $this->_init('cg_product/category');
    }

    /**
     * Return products collection for category
     *
     * @return Cg_Product_Model_Resource_Product_Collection
     */
    public function getProducts()
    {
        if (!$this->getId()) {
            Mage::throwException('Category not loaded to retrieve products collection.');
        }
        $collection = Mage::getModel('cg_product/product')->getCollection();
        $collection->addFieldToFilter("category_id", $this->getId());
        return $collection;
    }
}
