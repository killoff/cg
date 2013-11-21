<?php
class Cg_Product_Block_List_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('productGrid');
        $this->setDefaultSort('title');
        $this->setDefaultDir('desc');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('cg_product/product')->getCollection();
        /* @var $collection Mage_Cms_Model_Mysql4_Page_Collection */
//        $collection->setFirstStoreFlag(true);
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('title', array(
            'header'    => Mage::helper('cg_product')->__('Title'),
            'index'     => 'title',
        ));

        $this->addColumn('price', array(
            'header'    => Mage::helper('cg_product')->__('Price'),
            'index'     => 'price',
            'type'     => 'price',
        ));

        $this->addColumn('duration', array(
            'header'    => Mage::helper('cg_product')->__('Duration'),
            'index'     => 'duration'
        ));

        return parent::_prepareColumns();
    }

    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
//        parent::_afterLoadCollection();
    }

    /**
     * Row click url
     *
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}
