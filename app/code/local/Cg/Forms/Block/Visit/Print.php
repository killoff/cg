<?php
class Cg_Forms_Block_Visit_Print extends Cg_Kernel_Block_Adminhtml_Template_Smarty
{
    protected $_template = 'form/print.tpl';

    protected function _beforeToHtml()
    {
        $visit = Mage::registry('current_visit');
        $this->assign('data', $visit);
        $customer = Mage::getModel('customer/customer')->load($visit->getCustomerId());
        $this->assign('customer', $customer->getData());
        $employee = Mage::getModel('admin/user')->load($visit->getAdminId());
        $this->assign('employee', $employee->getData());
        return parent::_beforeToHtml();
    }
}
