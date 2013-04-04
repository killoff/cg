<?php
class Cg_Forms_Block_Visit extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    /**
     * Block constructor
     */
    public function __construct()
    {
        $this->_controller = 'visit';
        $this->_blockGroup = 'cg_forms';
//        $this->_addButtonLabel = Mage::helper('enterprise_banner')->__('Add Banner');


        $this->_headerText = Mage::helper('cms')->__('Manage Customer Visits');

        parent::__construct();

        if ($this->_isAllowedAction('save')) {
            $this->_updateButton('add', 'label', Mage::helper('cg_forms')->__('Add Visit'));
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
        return Mage::getSingleton('admin/session')->isAllowed('cg_forms/visit/' . $action);
    }

}
