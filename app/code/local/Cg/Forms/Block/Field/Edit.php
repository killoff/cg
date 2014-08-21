<?php
class Cg_Forms_Block_Field_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'cg_forms';
        $this->_controller = 'field';

//        $this->_updateButton('save', 'label', Mage::helper('cg_office')->__('Save'));
//        $this->_updateButton('delete', 'label', Mage::helper('cg_office')->__('Delete'));
//        $this->_removeButton('delete');
//        $this->_updateButton('print', 'label', Mage::helper('cg_office')->__('Print Product'));
        $this->_removeButton('reset');
    }

    public function getHeaderText()
    {
        if( Mage::registry('current_field')->getId() ) {
            return Mage::helper('cg_forms')->__("Edit Field '%s'", $this->htmlEscape(Mage::registry('current_field')->getName()));
        } else {
            return Mage::helper('cg_forms')->__('Add New Field');
        }
    }

    public function getValidationUrl()
    {
        return false;
    }
}
