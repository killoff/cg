<?php
/**
 * Rewrite for Customer admin grid
 *
 * @category   Cg
 * @package    Cg_Customer
 */
class Cg_Customer_Block_Adminhtml_Grid extends Mage_Adminhtml_Block_Customer_Grid
{
    protected function _prepareColumns()
    {
        parent::_prepareColumns();

        $this->removeColumn('email')
            ->removeColumn('group')
            ->removeColumn('billing_postcode')
            ->removeColumn('billing_country_id')
            ->removeColumn('billing_region')
            ->removeColumn('customer_since')
            ->removeColumn('website_id');

//        $this->getColumn('customer_since')->setWidth('100');
        $this->_exportTypes = array();
        return $this;
    }

    protected function _prepareMassaction()
    {
        return $this;
    }
}
