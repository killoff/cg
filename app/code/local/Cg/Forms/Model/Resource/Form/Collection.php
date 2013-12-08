<?php
class Cg_Forms_Model_Resource_Form_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Define resource model
     *
     */
    protected function _construct()
    {
        $this->_init('cg_forms/form');
        $this->setOrder('user_date');
//        $this->_map['fields']['page_id'] = 'main_table.page_id';
//        $this->_map['fields']['store']   = 'store_table.store_id';
    }

    protected function _initSelect()
    {
        parent::_initSelect();
        $this->getSelect()
            ->join(
                array('cname' => 'customer_entity_varchar'),
                'cname.entity_id=main_table.customer_id AND cname.attribute_id=5',
                array('firstname' => 'value')
            )
            ->join(
                array('cfname' => 'customer_entity_varchar'),
                'cfname.entity_id=main_table.customer_id AND cfname.attribute_id=6',
                array('middlename' => 'value')
            )
            ->join(
                array('clname' => 'customer_entity_varchar'),
                'clname.entity_id=main_table.customer_id AND clname.attribute_id=7',
                array('lastname' => 'value')
            )
            ->join(
                array('p' => 'cg_product'),
                'p.product_id=main_table.product_id',
                array('product' => 'title')
            )
            ->join(
                array('user' => 'admin_user'),
                'user.user_id=main_table.admin_id',
                array('admin_name' => "CONCAT(user.firstname,' ',user.lastname)")
            );
        $this->getSelect()->columns(array('fullname' => "CONCAT_WS(' ',cname.value,cfname.value,clname.value)"));
        return $this;
    }

    public function addFieldToFilter($field, $condition = null)
    {
        if ($field == 'fullname') {
            $conditionSql = $this->_getConditionSql('cname.value', $condition)
                .' OR '.$this->_getConditionSql('cfname.value', $condition)
                .' OR '.$this->_getConditionSql('clname.value', $condition);
            $this->getSelect()->where($conditionSql);
        } elseif ($field == 'admin_name') {
            $conditionSql = $this->_getConditionSql('user.firstname', $condition)
                .' OR '.$this->_getConditionSql('user.lastname', $condition);
            $this->getSelect()->where($conditionSql);
        } else {
            parent::addFieldToFilter($field, $condition);
        }
        return $this;
    }

    protected function _afterLoad()
    {
        foreach ($this->getItems() as $item) {
            $rowData = @unserialize($item->getRowData());
            if (is_array($rowData)) {
                foreach ($rowData as $k => $v) {
                    $item->setData($k, $v);
                }
            }
        }
        return parent::_afterLoad();
    }
}
