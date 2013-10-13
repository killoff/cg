<?php
class Cg_Kernel_Controller_Action extends Mage_Adminhtml_Controller_Action
{
    protected function _success($message)
    {
        Mage::getSingleton('adminhtml/session')->addSuccess($message);
    }

    protected function _notice($message)
    {
        Mage::getSingleton('adminhtml/session')->addNotice($message);
    }

    protected function _warning($message)
    {
        Mage::getSingleton('adminhtml/session')->addWarning($message);
    }

    protected function _error($message)
    {
        Mage::getSingleton('adminhtml/session')->addError($message);
    }

    protected function _breadcrumb($label, $url = '')
    {
        return $this->_addBreadcrumb($label, $label, $url);
    }


    protected function _validateFormKey()
    {
        return true;
    }

    protected function _validateSecretKey()
    {
        return true;
    }


}
