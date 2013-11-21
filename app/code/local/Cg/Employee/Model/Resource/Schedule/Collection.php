<?php
class Cg_Employee_Model_Resource_Schedule_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Define resource model
     *
     */
    protected function _construct()
    {
        $this->_init('cg_employee/schedule');
    }

    /**
     * Init select
     *
     * @return Cg_Employee_Model_Resource_Schedule_Collection
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        $this->getSelect()->order('main_table.start ASC');
        return $this;
    }

    /**
     * @param $from
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    public function setStartDate($from)
    {
        return $this->addFieldToFilter('main_table.start', array('gteq' => $from));
    }

    /**
     * @param $to
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    public function setEndDate($to)
    {
        return $this->addFieldToFilter('main_table.start', array('lteq' => $to));
    }

    public function addEmployeeFilter($userIds)
    {
        return $this->addFieldToFilter('main_table.user_id', array('in' => $userIds));
    }

    public function setProductIds($productIds)
    {
        $this->getSelect()->join(
            array('ep' => 'cg_employee_product'),
            'ep.user_id=main_table.user_id AND ep.product_id IN(' . implode(',', $productIds) . ')',
            array('product_id' => new Zend_Db_Expr("ep.product_id")))
            ->join(
                array('p' => 'cg_product'),
                'ep.product_id=p.product_id',
                array('duration' => new Zend_Db_Expr("p.duration"), 'product_name' => new Zend_Db_Expr("p.title")))
        ;
        return $this;
    }

    /**
     * @return $this
     */
    public function joinInformation()
    {
        $this->getSelect()->join(
                array('u' => 'admin_user'),
                'u.user_id=main_table.user_id',
                array('admin_name' => new Zend_Db_Expr("CONCAT_WS(' ',u.firstname,u.lastname)")))
        ;
        return $this;
    }
}
