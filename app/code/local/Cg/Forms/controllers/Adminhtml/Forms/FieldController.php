<?php
class Cg_Forms_Adminhtml_Forms_FieldController extends Cg_Kernel_Controller_Action
{
    protected function _initAction()
    {
        $this->_setActiveMenu('cg_forms');
        $this->_title($this->__('Form Fields'));
    }

    protected function getTemplateId()
    {
        return $this->getRequest()->getParam('template_id');
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
        $list = $this->getLayout()->createBlock('cg_forms/field_list', 'field_list');
        $list->setTemplateId($this->getTemplateId());
        $this->_addContent($list);
        $this->renderLayout();
    }

    public function gridAction()
    {
        $grid = $this->getLayout()->createBlock('cg_forms/field_list_grid');
        $grid->setTemplateId($this->getTemplateId());
        $this->getResponse()->setBody($grid->toHtml());
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $field = Mage::getModel('cg_forms/field');
        if ($this->getRequest()->getParam('id')) {
            $field->load($this->getRequest()->getParam('id'));
        }
        Mage::register('current_field', $field);
        $this->loadLayout();
        $this->_addContent(
            $this->getLayout()->createBlock('cg_forms/field_edit')
        );
        $this->renderLayout();
    }

    public function saveAction()
    {
        try {
            /** @var $field Cg_Office_Model_Product */
            $field = Mage::getModel('cg_forms/field');
            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $field->load($id);
            }
            $field->addData($this->getRequest()->getParam('data'));
            $field->save();

            Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Field was saved.'));
            $this->_redirect('*/*/index');

        } catch (Exception $e) {
            die($e->getMessage());
        }

    }
}
