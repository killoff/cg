<?php
class Cg_Forms_Model_Resource_Field_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Define resource model
     *
     */
    protected function _construct()
    {
        $this->_init('cg_forms/field');
    }
}
