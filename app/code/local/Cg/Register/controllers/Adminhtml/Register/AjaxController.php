<?php
/**
 * Class Cg_Register_Adminhtml_AjaxController
 */
class Cg_Register_Adminhtml_Register_AjaxController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        echo '1231';
    }
    public function addRegisterAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('cg_register/form')->setUserId($this->getRequest()->getParam('user_id'))->toHtml()
        );
    }
}
