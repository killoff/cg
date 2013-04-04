<?php
class Cg_Forms_Block_Reservation_Print extends Cg_Kernel_Block_Adminhtml_Template_Smarty
{
    protected $_template = 'form/print.tpl';

    protected function _beforeToHtml()
    {
        $reservation = Mage::registry('current_reservation');
        $this->assign('data', $reservation);
        $customer = Mage::getModel('customer/customer')->load($reservation->getCustomerId());
        $this->assign('customer', $customer->getData());
        $employee = Mage::getModel('admin/user')->load($reservation->getAdminId());
        $this->assign('employee', $employee->getData());
        return parent::_beforeToHtml();
    }
}
