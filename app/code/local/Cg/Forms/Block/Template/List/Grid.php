<?php
class Cg_Forms_Block_Template_List_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('templatesGrid');
        $this->setDefaultSort('name');
        $this->setDefaultDir('asc');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('cg_forms/template')->getCollection();
        /* @var $collection Mage_Cms_Model_Mysql4_Page_Collection */
//        $collection->setFirstStoreFlag(true);
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('name', array(
            'header'    => Mage::helper('cg_forms')->__('Template Name'),
            'index'     => 'name',
        ));


        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('customer')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('customer')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
            ));

        return parent::_prepareColumns();
    }

    /**
     * Row click url
     *
     * @param Varien_Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/forms_field/index', array('template_id' => $row->getId()));
    }
}
