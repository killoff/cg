<?php
class Cg_Employee_Model_Resource_Helper_Mysql4 extends Mage_Core_Model_Resource_Helper_Mysql4
{
    /**
     * @param $userId
     * @param array $productIds
     * @return Number of saved product IDs
     */
    public function saveProductIds($userId, array $productIds = array())
    {
        $where = array('user_id=?' => $userId);
        $this->_getWriteAdapter()->delete($this->_getEmployeeProductTable(), $where);

        if (!empty($productIds)) {
            $rows = array();
            foreach ($productIds as $id) {
                $rows[] = array('user_id' => $userId, 'product_id' => $id);
            }
            return $this->_getWriteAdapter()->insertMultiple($this->_getEmployeeProductTable(), $rows);
        }
        return 0;
    }

    /**
     * @param $userId
     * @return array
     */
    public function getProductIds($userId)
    {
        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
            ->from($this->_getEmployeeProductTable(), array('product_id'))
            ->where('user_id=?', (int)$userId);

        return $adapter->fetchCol($select);
    }
    /**
     * @param $userId
     * @return array
     */
    public function getSchedule($userId)
    {
        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
            ->from($this->_getEmployeeScheduleTable())
            ->where('user_id=?', (int)$userId);

        return $adapter->fetchAll($select);
    }

    public function saveSchedule($userId, array $schedule)
    {
        $rows = array();
        foreach ($schedule as $period) {
            $rows[] = array('user_id' => $userId, 'time_start' => $period['start'], 'time_end' => $period['end'], 'type' => 1);
        }
        return $this->_getWriteAdapter()->insertMultiple($this->_getEmployeeScheduleTable(), $rows);

    }

    /**
     * Getter for product<->role table
     *
     * @return string
     */
    protected function _getEmployeeProductTable()
    {
        return $this->_getReadAdapter()->getTableName('cg_employee_product');
    }

    /**
     * Getter for product<->role table
     *
     * @return string
     */
    protected function _getEmployeeScheduleTable()
    {
        return $this->_getReadAdapter()->getTableName('cg_employee_schedule');
    }
}
