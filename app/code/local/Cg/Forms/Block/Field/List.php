<?php
class Cg_Forms_Block_Field_List extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    /**
     * Block constructor
     */
    public function __construct()
    {
        $this->_controller = 'field_list';
        $this->_blockGroup = 'cg_forms';
        $this->_headerText = Mage::helper('cg_forms')->__('Form Fields');

        parent::__construct();

        if ($this->_isAllowedAction('save')) {
            $this->_updateButton('add', 'label', Mage::helper('cg_forms')->__('Add Field'));
        } else {
            $this->_removeButton('add');
        }

    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->getChild('grid')->setTemplateId($this->getTemplateId());
        return $this;
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
