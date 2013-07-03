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
        return $this->addFieldToFilter('start', array('gteq' => $from));
    }

    /**
     * @param $to
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    public function setEndDate($to)
    {
        return $this->addFieldToFilter('start', array('lteq' => $to));
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
