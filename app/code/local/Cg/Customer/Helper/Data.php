<?php


class Cg_Customer_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_PATH_ALLOWED_CUSTOMER_TABS = 'adminhtml/allowed_tabs';

    /**
     * List of declared customer tabs
     *
     * @var array
     */
    protected $_declaredAllowedCustomerTabs;

    /**
     * Retrieve list of allowed customer tabs
     *
     * @return array
     */
    public function getAllowedCustomerTabs()
    {
        return array_keys($this->_getDeclaredAllowedTabs());
    }

    /**
     * Retrieve active tab id
     *
     * @return null|string
     */
    public function getDefaultActiveTab()
    {
        foreach ($this->_getDeclaredAllowedTabs() as $tabId => $tabNodeValue) {
            if (is_array($tabNodeValue) && isset($tabNodeValue['default'])) {
                return $tabId;
            }
        }
        return null;
    }

    /**
     * @return array
     */
    protected function _getDeclaredAllowedTabs()
    {
        if (null === $this->_declaredAllowedCustomerTabs) {
            $this->_declaredAllowedCustomerTabs = Mage::getConfig()
                ->getNode(self::XML_PATH_ALLOWED_CUSTOMER_TABS)
                ->asArray();
        }
        return $this->_declaredAllowedCustomerTabs;
    }
}
