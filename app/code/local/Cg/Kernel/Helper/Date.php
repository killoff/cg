<?php
class Cg_Kernel_Helper_Date
{
    public function todayStart()
    {
        $time = time();
        $datetime = new DateTime('@' . $time);
        $datetime->setTime(0,0,0);
        return $datetime->getTimestamp();
    }

    public function todayEnd()
    {
        $time = time();
        $datetime = new DateTime('@' . $time);
        $datetime->setTime(23,59,59);
        return $datetime->getTimestamp();
    }

    /**
     * return UTC timestamp
     */
    public function format($timestamp, $format = Varien_Date::DATETIME_PHP_FORMAT)
    {
        $datetime = new DateTime('@' . $timestamp);
        return $datetime->format($format);
    }

    public function arePeriodsIntersecting($start1, $end1, $start2, $end2)
    {
        return $start1 < $end2 && $end1 > $start2;
    }
}
