<?php

class Cg_Customer_Block_Adminhtml_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('customer_info_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('customer')->__('Customer Information'));
    }

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
        $tabId = $this->getRequest()->getParam('tab');
        if( $tabId ) {
            $tabId = preg_replace("#{$this->getId()}_#", '', $tabId);
            if($tabId) {
                $this->setActiveTab($tabId);
            }
        }
    }

    protected function _beforeToHtml()
    {
        $this->addTab('account', array(
                                      'label'     => Mage::helper('cg_customer')->__('Account Information'),
                                      'content'   => $this->getLayout()->createBlock('adminhtml/customer_edit_tab_account')->initForm()->toHtml(),
                                      'active'    => Mage::registry('current_customer')->getId() ? false : true
                                 ));

        $this->addTab('addresses', array(
                                        'label'     => Mage::helper('cg_customer')->__('Addresses'),
                                        'content'   => $this->getLayout()->createBlock('adminhtml/customer_edit_tab_addresses')->initForm()->toHtml(),
                                   ));


        $this->_updateActiveTab();
        return parent::_beforeToHtml();
    }

}
