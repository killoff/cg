<?php
class Cg_Forms_Block_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'cg_forms';
        $this->_mode = '';
        $this->_controller = 'edit';

        $this->_headerText = Mage::helper('cms')->__('Manage Customer Visits');
        $this->_updateButton('save', 'label', Mage::helper('cg_forms')->__('Save Visit'));
        $this->_updateButton('delete', 'label', Mage::helper('cg_forms')->__('Delete Visit'));
        $this->_updateButton('print', 'label', Mage::helper('cg_forms')->__('Print Visit'));
        $this->_removeButton('reset');

        if ($this->_isAllowedAction('save')) {
            $this->_updateButton('add', 'label', Mage::helper('cg_forms')->__('Add Visit'));
        } else {
            $this->_removeButton('add');
        }
    }

    protected function _prepareLayout()
    {
        $this->setChild('form',
            $this->getLayout()->createBlock($this->_blockGroup . '/' . $this->_controller . '_form'));
        return parent::_prepareLayout();
    }

    public function getHeaderText()
    {
        if( Mage::registry('current_form')->getId() ) {
            return Mage::helper('cg_forms')->__("Edit Form '%s'", $this->htmlEscape(Mage::registry('current_form')->getId()));
        } else {
            return Mage::helper('cg_forms')->__('New Form');
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
        return Mage::getSingleton('admin/session')->isAllowed('cg_forms/' . $action);
    }

    public function getValidationUrl()
    {
        return false;
    }
}