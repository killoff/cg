<?php
class Cg_Product_Model_Resource_Helper_Mysql4 extends Mage_Core_Model_Resource_Helper_Mysql4
{
    /**
     * Return array of User Role IDs assigned for product
     *
     * @param $productId
     * @return array
     */
    public function getProductRoleIds($productId)
    {
        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
            ->from($this->_getProductRoleTable(), array('role_id'))
            ->where('product_id=?', $productId);
        return $adapter->fetchCol($select);
    }

    /**
     * Return array of Product IDs which has role ID
     *
     * @param $roleId
     * @return array
     */
    public function getRoleProductIds($roleId)
    {
        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
            ->from($this->_getProductRoleTable(), array('product_id'))
            ->where('role_id=?', $roleId);
        return $adapter->fetchCol($select);
    }

    /**
     * Save multiple role IDS for product
     *
     * @param $productId
     * @param array $roleIds
     * @return int
     */
    public function saveProductRoleIds($productId, array $roleIds)
    {
        $this->_getWriteAdapter()->delete($this->_getProductRoleTable(), array('product_id=?' => $productId));
        if (empty($roleIds)) {
            return 0;
        }
        $data = array();
        foreach ($roleIds as $roleId) {
            $data[] = array('product_id' => $productId, 'role_id' => $roleId);
        }
        return $this->_getWriteAdapter()->insertMultiple($this->_getProductRoleTable(), $data);
    }

    /**
     * Getter for product<->role table
     *
     * @return string
     */
    protected function _getProductRoleTable()
    {
        return $this->_getReadAdapter()->getTableName('cg_product_user_roles');
    }
}
