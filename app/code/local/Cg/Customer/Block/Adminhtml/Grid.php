<?php
/**
 * Rewrite for Customer admin grid
 *
 * @category   Cg
 * @package    Cg_Customer
 */
class Cg_Customer_Block_Adminhtml_Grid extends Mage_Adminhtml_Block_Customer_Grid
{
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('customer/customer_collection')
            ->addNameToSelect()
            ->addAttributeToSelect('created_at')
            ->addAttributeToSelect('uniqid')
            ->joinAttribute('billing_telephone', 'customer_address/telephone', 'default_billing', null, 'left');

        $this->setCollection($collection);
        return Mage_Adminhtml_Block_Widget_Grid::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        parent::_prepareColumns();

        $this->addColumnAfter('uniqid', array(
            'header'    => Mage::helper('customer')->__('ID'),
            'width'     => '50px',
            'index'     => 'uniqid',
            'type'  => 'number',
        ), 'entity_id');

        $this->sortColumnsByOrder();

        $this->removeColumn('entity_id')
            ->removeColumn('email')
            ->removeColumn('group')
            ->removeColumn('billing_postcode')
            ->removeColumn('billing_country_id')
            ->removeColumn('billing_region')
            ->removeColumn('customer_since')
            ->removeColumn('website_id');

        $this->_exportTypes = array();
        return $this;
    }

    protected function _prepareMassaction()
    {
        return $this;
    }
}
