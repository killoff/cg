<?php
class Cg_Kernel_Block_Widget_Form_Element_Wysiwyg extends Varien_Data_Form_Element_Textarea
{

    public function getAfterElementHtml()
    {

        return '<script type="text/javascript">'
            . 'CKEDITOR.replace("' . $this->getHtmlId() . '",{});'
            . '</script>';
    }
}
