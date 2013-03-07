<?php
class Cg_Product_Block_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'cg_product';
        $this->_mode = '';
        $this->_controller = 'edit';

        $this->_updateButton('save', 'label', Mage::helper('cg_product')->__('Save Product'));
        $this->_updateButton('delete', 'label', Mage::helper('cg_product')->__('Delete Product'));
//        $this->_removeButton('delete');
//        $this->_updateButton('print', 'label', Mage::helper('cg_product')->__('Print Product'));
        $this->_removeButton('reset');
        $this->setValidationUrl($this->getUrl('*/*/validate', array('id' => $this->getRequest()->getParam($this->_objectId))));
    }

    protected function _prepareLayout()
    {
        $this->setChild('form',
            $this->getLayout()->createBlock($this->_blockGroup . '/' . $this->_controller . '_form'));
        return parent::_prepareLayout();
    }

    public function getHeaderText()
    {
        if( Mage::registry('current_product')->getId() ) {
            return Mage::helper('cg_product')->__("Edit Form '%s'", $this->htmlEscape(Mage::registry('current_product')->getId()));
        } else {
            return Mage::helper('cg_product')->__('New Form');
        }
    }

    public function getValidationUrl()
    {
        return false;
    }
}
