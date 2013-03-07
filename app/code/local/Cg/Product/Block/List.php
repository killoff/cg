<?php
class Cg_Product_Block_List extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    /**
     * Block constructor
     */
    public function __construct()
    {
        $this->_controller = 'list';
        $this->_blockGroup = 'cg_product';
        $this->_headerText = Mage::helper('cg_product')->__('Manage Products');

        parent::__construct();


        if ($this->_isAllowedAction('save')) {
            $this->_updateButton('add', 'label', Mage::helper('cg_product')->__('Add Product'));
        } else {
            $this->_removeButton('add');
        }

    }

    /**
     * Check permission for passed action
     *
     * @param string $action
     * @return bool
     */
    protected function _isAllowedAction($action)
    {
        return Mage::getSingleton('admin/session')->isAllowed('cg_product/product/' . $action);
    }

}
