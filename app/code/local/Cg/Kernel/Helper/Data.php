<?php
class Cg_Kernel_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function todayStart()
    {
        return Mage::app()->getLocale()->date()->setTime('00:00:00');
    }

    public function todayEnd()
    {
        return Mage::app()->getLocale()->date()->setTime('23:59:59');
    }
}
