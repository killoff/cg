<?php
class Cg_Forms_Block_List_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('formGrid');
        $this->setDefaultSort('created');
        $this->setDefaultDir('desc');
        $this->setUseAjax(true);
        $this->setEmptyText(Mage::helper('cg_forms')->__('No Forms Found'));
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('cg_forms/form')->getCollection();
        if ($this->getCustomerId()) {
            $collection->addFieldToFilter('customer_id', $this->getCustomerId());
        }
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $baseUrl = $this->getUrl();

        $this->addColumn('fullname', array(
            'header'    => Mage::helper('cg_forms')->__('Patient'),
            'align'     => 'left',
            'index'     => 'fullname',
        ));

        $this->addColumn('employee', array(
            'header'    => Mage::helper('cg_forms')->__('Doctor'),
            'align'     => 'left',
            'index'     => 'admin_name',
        ));

        $this->addColumn('user_date', array(
            'header'    => Mage::helper('cg_forms')->__('Date'),
            'align'     => 'left',
            'index'     => 'user_date',
            'type'     => 'date',
        ));


//        $this->addColumn('identifier', array(
//            'header'    => Mage::helper('cms')->__('URL Key'),
//            'align'     => 'left',
//            'index'     => 'identifier'
//        ));
//
//
//
//        $this->addColumn('root_template', array(
//            'header'    => Mage::helper('cms')->__('Layout'),
//            'index'     => 'root_template',
//            'type'      => 'options',
//            'options'   => Mage::getSingleton('page/source_layout')->getOptions(),
//        ));
//
//        /**
//         * Check is single store mode
//         */
//        if (!Mage::app()->isSingleStoreMode()) {
//            $this->addColumn('store_id', array(
//                'header'        => Mage::helper('cms')->__('Store View'),
//                'index'         => 'store_id',
//                'type'          => 'store',
//                'store_all'     => true,
//                'store_view'    => true,
//                'sortable'      => false,
//                'filter_condition_callback'
//                                => array($this, '_filterStoreCondition'),
//            ));
//        }
//
//        $this->addColumn('is_active', array(
//            'header'    => Mage::helper('cms')->__('Status'),
//            'index'     => 'is_active',
//            'type'      => 'options',
//            'options'   => Mage::getSingleton('cms/page')->getAvailableStatuses()
//        ));
//
//        $this->addColumn('creation_time', array(
//            'header'    => Mage::helper('cms')->__('Date Created'),
//            'index'     => 'creation_time',
//            'type'      => 'datetime',
//        ));
//
//        $this->addColumn('update_time', array(
//            'header'    => Mage::helper('cms')->__('Last Modified'),
//            'index'     => 'update_time',
//            'type'      => 'datetime',
//        ));
//
//        $this->addColumn('page_actions', array(
//            'header'    => Mage::helper('cms')->__('Action'),
//            'width'     => 10,
//            'sortable'  => false,
//            'filter'    => false,
//            'renderer'  => 'adminhtml/cms_page_grid_renderer_action',
//        ));

        return parent::_prepareColumns();
    }

    protected function _afterLoadCollection()
    {
//        $this->getCollection()->walk('afterLoad');
//        parent::_afterLoadCollection();
    }

    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }

        $this->getCollection()->addStoreFilter($value);
    }

    /**
     * Row click url
     *
     * @param $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    /**
     * Retrieve grid url
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/forms/grid', array('_current' => true));
    }
}
