<?php
class Cg_Customer_Block_Adminhtml_Edit_Accordion extends Mage_Adminhtml_Block_Customer_Edit_Tab_View_Accordion
{
    protected function _prepareLayout()
    {
        $this->setId('customerViewAccordion');
    }
}
