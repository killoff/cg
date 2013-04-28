<?php

class Cg_Customer_Block_Adminhtml_Edit_Tabs extends Mage_Adminhtml_Block_Customer_Edit_Tabs
{
    /**
     * Add only tabs that declared in config
     *
     * @param string $tabId
     * @param array|Varien_Object $tab
     * @return $this|Mage_Adminhtml_Block_Widget_Tabs
     */
    public function addTab($tabId, $tab)
    {
        if (false === array_search($tabId, Mage::helper('cg_customer')->getAllowedCustomerTabs())) {
            return $this;
        }
        return parent::addTab($tabId, $tab);
    }

    /**
     * Set default active tab
     */
    protected function _updateActiveTab()
    {
        $activeTab = Mage::helper('cg_customer')->getDefaultActiveTab();
        if (null !== $activeTab) {
            $this->setActiveTab($activeTab);
        }
        parent::_updateActiveTab();
    }
}
