<?php
class Cg_Employee_Block_User_Edit_Tab_Products extends Mage_Adminhtml_Block_Template
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    protected $_employeeProductIds = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setTemplate('cg/employee/products.phtml');
        return parent::__construct();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return $this->__('Products');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->__('Products');
    }

    /**
     * Returns status flag about this tab can be shown or not
     *
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }

    public function getCategories()
    {
        $collection = Mage::getModel('cg_product/category')->getCollection();
        foreach ($collection as $category) {
            $products = $category->getProducts();
            $category->setProductsCollection($products);
            $category->setIsSelected(true);
            foreach ($products as $product) {
                $product->setIsSelected(true);
                if (!$this->isSelected($product->getId())) {
                    $category->setIsSelected(false);
                    $product->setIsSelected(false);
                }
            }
        }
        return $collection;
    }

    public function isSelected($productId)
    {
        if ($this->_employeeProductIds === null) {
            $userId = Mage::registry('permissions_user')->getId();
            $this->_employeeProductIds = Mage::getResourceHelper('cg_employee')->getProductIds($userId);
        }
        return in_array($productId, $this->_employeeProductIds);
    }
}
