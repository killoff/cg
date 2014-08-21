<?php
class Cg_Forms_Block_Template_List extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    /**
     * Block constructor
     */
    public function __construct()
    {
        $this->_controller = 'template_list';
        $this->_blockGroup = 'cg_forms';
        $this->_headerText = Mage::helper('cg_forms')->__('Form Templates');

        parent::__construct();


        if ($this->_isAllowedAction('save')) {
            $this->_updateButton('add', 'label', Mage::helper('cg_forms')->__('Add Template'));
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
        return true; //Mage::getSingleton('admin/session')->isAllowed('cg_office/product/' . $action);
    }

}
