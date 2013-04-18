<?php
class Cg_Kernel_Block_Widget_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareLayout()
    {
        $this->getLayout()->getBlock('head')->addJs('ckeditor/ckeditor.js');
        return parent::_prepareLayout();
    }

    /**
     * Additional field types for Admin forms
     *
     * @return array
     */
    protected function _getAdditionalElementTypes()
    {
        return array(
            'wysiwyg'         => Mage::getConfig()->getBlockClassName('cg_kernel/widget_form_element_wysiwyg'),
            'uploader'         => Mage::getConfig()->getBlockClassName('cg_kernel/widget_form_element_uploader')
        );
    }
}
