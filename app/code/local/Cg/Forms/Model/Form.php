<?php
class Cg_Forms_Model_Form extends Mage_Core_Model_Abstract
{
    /**
     * Initialize resource model
     *
     */
    protected function _construct()
    {
        $this->_init('cg_forms/form');
    }

    protected function _beforeSave()
    {
        if (is_array($this->_getData('row_data'))) {
            $this->setData('row_data', serialize($this->_getData('row_data')));
        }
        return parent::_beforeSave();
    }

    protected function _afterLoad()
    {
        $rowData = $this->_getData('row_data');
        if (!empty($rowData) && !is_array($rowData)) {
            foreach (unserialize($rowData) as $k => $v) {
                $this->setData($k, $v);
            }
        }
        return parent::_afterLoad();
    }
}
