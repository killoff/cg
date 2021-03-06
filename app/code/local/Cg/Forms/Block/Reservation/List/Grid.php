<?php
class Cg_Forms_Block_Reservation_List_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('reservationGrid');
        $this->setDefaultSort('created');
        $this->setDefaultDir('desc');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('cg_forms/reservation')->getCollection();
        /* @var $collection Mage_Cms_Model_Mysql4_Page_Collection */
//        $collection->setFirstStoreFlag(true);
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('created_at',
            array(
                'header' => Mage::helper('cg_forms')->__('Added'),
                'index'  => 'created_at',
                'type'   => 'datetime'
            )
        );

        $this->addColumn('employee', array(
            'header'    => Mage::helper('cg_forms')->__('Employee'),
            'index'     => 'employee',
        ));

        $this->addColumn('status', array(
            'header'    => Mage::helper('cg_forms')->__('Status'),
            'index'     => 'status',
        ));
        $this->addColumn('period', array(
            'header'    => Mage::helper('cg_forms')->__('When'),
            'index'     => 'period',
        ));
        $this->addColumn('fio', array(
            'header'    => Mage::helper('cg_forms')->__('Name'),
            'index'     => 'fio',
        ));
        $this->addColumn('phone', array(
            'header'    => Mage::helper('cg_forms')->__('Phone'),
            'index'     => 'phone',
        ));
        $this->addColumn('address', array(
            'header'    => Mage::helper('cg_forms')->__('City'),
            'index'     => 'address',
        ));
        $this->addColumn('comment', array(
            'header'    => Mage::helper('cg_forms')->__('Comment'),
            'index'     => 'comment',
        ));

//        $this->addColumn('employee', array(
//            'header'    => Mage::helper('cg_forms')->__('Врач'),
//            'align'     => 'left',
//            'index'     => 'admin_name',
//        ));



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
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}
