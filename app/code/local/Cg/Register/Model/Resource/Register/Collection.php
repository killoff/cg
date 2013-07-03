<?php
class Cg_Register_Model_Resource_Register_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Define resource model
     *
     */
    protected function _construct()
    {
        $this->_init('cg_register/register');
    }

    public function joinInformation()
    {
        $this->getSelect()->join(
            array('cname' => 'customer_entity_varchar'),
            'cname.entity_id=main_table.customer_id AND cname.attribute_id=5',
            array('customer_name' => new Zend_Db_Expr("CONCAT_WS(' ',cname.value,clname.value)")))
            ->join(
                array('clname' => 'customer_entity_varchar'),
                'clname.entity_id=main_table.customer_id AND clname.attribute_id=7',
                array())
            ->join(
                array('s' => 'cg_employee_schedule'),
                's.schedule_id=main_table.schedule_id',
                array('user_id', 'room_id', 'schedule_start' => 's.start', 'schedule_end' => 's.end'))
            ->join(
                array('u' => 'admin_user'),
                'u.user_id=s.user_id',
                array('admin_name' => new Zend_Db_Expr("CONCAT_WS(' ',u.firstname,u.lastname)")))
            ->join(
                array('r' => 'cg_office_room'),
                'r.room_id=s.room_id',
                array('room_name' => 'r.name'))
            ->join(
                array('p' => 'cg_product'),
                'p.product_id=main_table.product_id',
                array('product_name' => 'p.title'))
        ;
        return $this;
    }
}
