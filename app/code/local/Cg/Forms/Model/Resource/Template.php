<?php
class Cg_Forms_Model_Resource_Template extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Initialize resource model
     *
     */
    protected function _construct()
    {
        $this->_init('cg_forms/template', 'template_id');
    }
}
