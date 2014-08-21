<?php
class Cg_Forms_Block_Field_List_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('fieldsGrid');
        $this->setDefaultSort('sorting');
        $this->setDefaultDir('asc');
    }

    protected function _prepareCollection()
    {
        /** @var Cg_Forms_Model_Resource_Field_Collection $collection */
        $collection = Mage::getModel('cg_forms/field')->getCollection();
        $collection->addFieldToFilter('template_id', $this->getTemplateId());
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('name', array(
            'header'    => Mage::helper('cg_forms')->__('Field Name'),
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
        return $this->getUrl('*/*/fields', array('id' => $row->getId()));
    }
}
