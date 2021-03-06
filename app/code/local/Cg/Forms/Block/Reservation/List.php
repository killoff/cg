<?php
class Cg_Forms_Block_Reservation_List extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    /**
     * Block constructor
     */
    public function __construct()
    {
        $this->_controller = 'reservation_list';
        $this->_blockGroup = 'cg_forms';
        $this->_headerText = Mage::helper('cms')->__('Manage Reservation');

        parent::__construct();

        if ($this->_isAllowedAction('save')) {
            $this->_updateButton('add', 'label', Mage::helper('cg_forms')->__('Add Reservation'));
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
        return true; //Mage::getSingleton('admin/session')->isAllowed('cg_forms/form/' . $action);
    }

}
