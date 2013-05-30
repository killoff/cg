<?php
class Cg_Forms_Block_Customer_Edit_Buttons extends Mage_Adminhtml_Block_Abstract
{
    /**
     * Add/remove buttons on Customer Edit page
     */
    public function prepareButtons()
    {
        $customer = Mage::registry('current_customer');
        if ($customer->getId()) {
            $this->getParentBlock()
                ->removeButton('order')
                ->removeButton('delete')
                ->addButton('add_form',
                    array(
                         'label' => Mage::helper('cg_forms')->__('Create Form'),
                         'onclick' => 'setLocation(\'' . $this->_getNewFormButtonUrl($customer->getId()) . '\')',
                         'class' => 'add',
                    ), 0);
        }
    }

    protected function _getNewFormButtonUrl($customerId)
    {
        return $this->getUrl('*/forms/new', array('customer_id' => $customerId));
    }
}
