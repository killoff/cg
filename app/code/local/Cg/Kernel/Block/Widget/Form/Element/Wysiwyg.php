<?php
class Cg_Kernel_Block_Widget_Form_Element_Wysiwyg extends Varien_Data_Form_Element_Textarea
{

    public function getAfterElementHtml()
    {
        $config = is_array($this->getConfig()) ? json_encode($this->getConfig()) : '{}';
        return '<script type="text/javascript">'
            . 'CKEDITOR.replace("' . $this->getHtmlId() . '",'.$config.');'
            . '</script>';
    }
}
