<?php
class Cg_Register_Model_Resource_Register extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Initialize resource model
     *
     */
    protected function _construct()
    {
        $this->_init('cg_register/register', 'register_id');
    }

}
