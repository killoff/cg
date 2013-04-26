<?php


class Cg_Customer_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_PATH_ALLOWED_CUSTOMER_TABS = 'adminhtml/allowed_tabs';

    /**
     * Retrieve list of allowed customer tabs
     *
     * @return array
     */
    public function getAllowedCustomerTabs()
    {
        $attributes = Mage::getConfig()
            ->getNode(self::XML_PATH_ALLOWED_CUSTOMER_TABS)
            ->asArray();
        return array_keys($attributes);
    }
}
