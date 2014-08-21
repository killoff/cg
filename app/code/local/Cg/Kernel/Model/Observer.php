<?php
class Cg_Kernel_Model_Observer
{
    public function setWidgetContainerValidationUrl(Varien_Event_Observer $observer)
    {
        return;

        /** @var Mage_Adminhtml_Block_Widget_Container $block */
        $block = $observer->getBlock();

        if (!$block instanceof Mage_Adminhtml_Block_Widget_Form_Container) {
            return;
        }
        $block->setValidationUrl(
            $block->getUrl('http://asdkjhfnesifdneifgef.com')
        );
        $class = new ReflectionClass("Mage_Adminhtml_Block_Widget_Form_Container");
        $property = $class->getProperty("_formScripts");
        $property->setAccessible(true);
        $property->setValue($block, array("

        Object.extend(varienForm, {
            _submit : function() {
            alert('setWidgetContainerValidationUrl 111');
            }
            });
//            Event.observe('edit_form','submit',function(el) {
//                alert('setWidgetContainerValidationUrl');
//            });
//$('edit_form').observe('submit', function(event) {
//  alert('setWidgetContainerValidationUrl');
//});

//            editForm.validator.options.onFormValidate = function(result, form) {
//                console.log(result);
//                console.log(form);
//            Event.observe('edit_form','submit',function(el) {
//                alert('setWidgetContainerValidationUrl');
//            });
//                form.onsubmit = false;
//                result = false;
//            }

        "));


    }
}
