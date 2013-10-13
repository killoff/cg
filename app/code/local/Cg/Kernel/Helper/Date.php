<?php
class Cg_Kernel_Helper_Date
{
    public function arePeriodsIntersecting($start1, $end1, $start2, $end2)
    {
        if (!is_object($start1)) {
            $start1 = new DateTime($start1);
        }
        if (!is_object($end1)) {
            $end1 = new DateTime($end1);
        }
        if (!is_object($start2)) {
            $start2 = new DateTime($start2);
        }
        if (!is_object($end2)) {
            $end2 = new DateTime($end2);
        }

        return $start1->getTimestamp() < $end2->getTimestamp()
            && $end1->getTimestamp() > $start2->getTimestamp();
    }
}
