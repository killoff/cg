<?php
class Cg_Forms_Block_Edit_Print extends Cg_Kernel_Block_Adminhtml_Template_Smarty
{
    protected $_template = 'form/print.tpl';

    protected function _beforeToHtml()
    {
        $form = Mage::registry('current_form');
        $this->assign('data', $form);
        $customer = Mage::getModel('customer/customer')->load($form->getCustomerId());
        $this->assign('customer', $customer->getData());
        $employee = Mage::getModel('admin/user')->load($form->getAdminId());
        $this->assign('employee', $employee->getData());
        return parent::_beforeToHtml();
    }
}
