<?php
class Cg_Forms_Adminhtml_Forms_TemplateController extends Cg_Kernel_Controller_Action
{
    protected function _initAction()
    {
        $this->_setActiveMenu('cg_forms');
        $this->_title($this->__('Form Templates'));
    }

    public function indexAction()
    {

        if ($this->getRequest()->getQuery('ajax')) {
            $this->_forward('grid');
            return;
        }
        $this->loadLayout();
        $this->_initAction();

        /**
         * Append products block to content
         */
        $this->_addContent(
            $this->getLayout()->createBlock('cg_forms/template_list', 'template_list')
        );
        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->getResponse()->setBody($this->getLayout()->createBlock('cg_forms/template_list_grid')->toHtml());
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $template = Mage::getModel('cg_forms/template');
        if ($this->getRequest()->getParam('id')) {
            $template->load($this->getRequest()->getParam('id'));
        }
        Mage::register('current_template', $template);
        $this->loadLayout();
        $this->_addContent(
            $this->getLayout()->createBlock('cg_forms/template_edit')
        );
        $this->renderLayout();
    }

    public function saveAction()
    {
        try {
            /** @var $template Cg_Office_Model_Product */
            $template = Mage::getModel('cg_forms/template');
            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $template->load($id);
            }
            $template->addData($this->getRequest()->getParam('data'));
            $template->save();

            Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Template was saved.'));
            $this->_redirect('*/*/index');

        } catch (Exception $e) {
            die($e->getMessage());
        }

    }

}
