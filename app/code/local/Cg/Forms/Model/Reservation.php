<?php
class Cg_Forms_Model_Reservation extends Mage_Core_Model_Abstract
{
    /**
     * Initialize resource model
     *
     */
    protected function _construct()
    {
        $this->_init('cg_forms/reservation');
    }

    protected function _beforeSave()
    {
    }

    protected function _afterLoad()
    {
    }
}
