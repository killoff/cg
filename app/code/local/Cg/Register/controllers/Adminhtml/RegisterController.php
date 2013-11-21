<?php
/**
 * @TODO Add ACL checks for Cg_Register
 * Class Cg_Forms_Adminhtml_FormsController
 */
class Cg_Register_Adminhtml_RegisterController extends Cg_Kernel_Controller_Action
{
    /**
     * Init actions
     *
     * @return Cg_Forms_Adminhtml_FormsController
     */
    protected function _initAction()
    {
        $this->_setActiveMenu('register');
        $this->_title($this->__('Register'));
        return $this;
    }

    public function indexAction()
    {
        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('cg_register/register'));
        $this->renderLayout();
    }

    public function createAction()
    {
        $this->_forward('index');
    }


    public function startAction()
    {
        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('cg_register/start'));
        $this->renderLayout();
    }

    public function proceedAction()
    {
        $products = $this->getRequest()->getParam('product', array());
        $users = $this->getRequest()->getParam('user', array());
        $url = $this->getUrl('*/*/create', array('_current' => true, 'products' => implode(',', $products), 'users' => implode(',', $users)));
        $this->getResponse()->setRedirect($url);
    }

}
