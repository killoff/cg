<?php


class Cg_Forms_Block_Customer_Edit_Tab_Form extends Cg_Forms_Block_List_Grid
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('customer_edit_tab_forms');
    }

    /**
     * Defines after which tab, this tab should be rendered
     *
     * @return string
     */
    public function getAfter()
    {
        return 'newsletter';
    }

    /**
     * Can show tab in tabs
     *
     * @return boolean
     */
    public function canShowTab()
    {
        return (bool)Mage::registry('current_customer')->getId();
    }

    /**
     * Tab is hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Return Tab label
     *
     * @return string
     */
    public function getTabLabel()
    {
        return $this->__('Forms');
    }

    /**
     * Return Tab title
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    /**
     * Row click url
     *
     * @param $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/forms/edit', array('id' => $row->getId()));
    }

    public function getCustomerId()
    {
        return Mage::registry('current_customer')->getId();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('user_date', array(
                                           'header'    => Mage::helper('cg_forms')->__('Date'),
                                           'align'     => 'left',
                                           'index'     => 'user_date',
                                           'type'     => 'date',
                                           'sortable' => false
                                      ));
        $this->addColumn('product', array(
                                          'header'    => Mage::helper('cg_forms')->__('Product'),
                                          'align'     => 'left',
                                          'index'     => 'product',
                                          'sortable' => false
                                     ));

        $this->addColumn('employee', array(
                                          'header'    => Mage::helper('cg_forms')->__('Doctor'),
                                          'align'     => 'left',
                                          'index'     => 'admin_name',
                                          'sortable' => false
                                     ));
        $this->addColumn('comment', array(
                                          'header'    => Mage::helper('cg_forms')->__('Comment'),
                                          'align'     => 'left',
                                          'index'     => 'comment',
                                          'sortable' => false
                                    ));


        $this->addColumn('action_print',
            array(
                 'header'    =>  Mage::helper('customer')->__('Action'),
                 'width'     => '100',
                 'type'      => 'action',
                 'getter'    => 'getId',
                 'actions'   => array(
                     array(
                         'caption'   => Mage::helper('cg_forms')->__('Print'),
                         'url'       => array('base'=> '*/forms/print'),
                         'field'     => 'id',
                         'target' => '_blank'
                     )
                 ),
                 'filter'    => false,
                 'sortable'  => false,
                 'index'     => 'print',
                 'is_system' => true,
        ));
        $this->addColumn('action_copy',
            array(
                 'width'     => '100',
                 'type'      => 'action',
                 'getter'    => 'getId',
                 'actions'   => array(
                     array(
                         'caption'   => Mage::helper('cg_forms')->__('Copy'),
                         'url'       => array('base'=> '*/forms/new', 'params' => array('customer_id' => $this->getCustomerId())),
                         'field'     => 'parent_id',
                         'target' => '_blank'
                     )
                 ),
                 'filter'    => false,
                 'sortable'  => false,
                 'index'     => 'print',
                 'is_system' => true,
        ));
    }

}
