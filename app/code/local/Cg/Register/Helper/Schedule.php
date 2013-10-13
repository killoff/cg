<?php
class Cg_Register_Helper_Schedule
{
    public function getAvailablePeriods($scheduleId, $start)
    {
        /** @var Cg_Employee_Model_Schedule $schedule */
        $schedule = Mage::getModel('cg_employee/schedule')->load($scheduleId);
        if (!$schedule->getId()) {
            Mage::throwException('Failed to load schedule to get its available intervals.');
        }
        $scheduleStart = new DateTime($schedule->getStart());
        $scheduleEnd = new DateTime($schedule->getEnd());
        if (!is_object($start)) {
            $start = new DateTime($start);
        }

        // start is out of schedule
        if ($start->getTimestamp() < $scheduleStart->getTimestamp()
            || $start->getTimestamp() >= $scheduleEnd->getTimestamp()
        ) {
            return array();
        }

        $maxDuration = min(90, ($scheduleEnd->getTimestamp() - $start->getTimestamp()) / 60);
        $periods = $this->generatePeriods($start, 15, $maxDuration);

        $result = array();
        foreach ($periods as $period) {
            if (!$this->isPeriodConflicted($schedule, $period['start'], $period['end'])) {
                $result[] = $period;
            }
        }
        return $result;
    }

    /**
     * @param Cg_Employee_Model_Schedule $schedule
     * @param DateTime $start
     * @param DateTime $end
     * @return bool
     */
    public function isPeriodConflicted($schedule, $start, $end)
    {
        $registryKey = 'schedule_collection' . $schedule->getId();
        $collection = Mage::registry($registryKey);
        if ($collection === null) {
            $collection = Mage::getResourceSingleton('cg_register/register_collection');
            $collection->addFieldToFilter('schedule_id', $schedule->getId());
            $collection->addFieldToFilter('end', array('gt' => $start->format(Varien_Date::DATETIME_INTERNAL_FORMAT)));
            $collection->load();
            Mage::register($registryKey, $collection);
        }
        /** @var Cg_Kernel_Helper_Date $dateHelper */
        $dateHelper = Mage::helper('cg_kernel/date');
        foreach ($collection as $register) {
            if ($dateHelper->arePeriodsIntersecting($start, $end, $register->getStart(), $register->getEnd())) {
                return true;
            }
        }
        return false;
    }


    /**
     * Generates array of periods. Example: start: 2013-09-09 12:00:00, step = 30 minutes, max duration = 60 minutes
     * Result:
     *      array ('start' => '2013-09-09 12:00:00', 'end' => '2013-09-09 12:30:00'),
     *      array ('start' => '2013-09-09 12:00:00', 'end' => '2013-09-09 13:00:00')
     *
     *
     * @param DateTime|string $start Date and time for starting point
     * @param int $step Step increment | minutes
     * @param int $maxDuration Max duration of period | minutes
     * @return array
     */
    public function generatePeriods($start, $step, $maxDuration)
    {
        $result = array();
        if ($maxDuration < $step) {
            return $result;
        }
        if (!is_object($start)) {
            $start = new DateTime($start);
        }
        $limit = $step;
        while($limit <= $maxDuration) {
            $end = clone $start;
            $result[] = array(
                'start' => $start,
                'end'   => $end->add(new DateInterval('PT' . $limit . 'M'))
            );
            $limit += $step;
        }
        return $result;
    }

    public function getRegistrations()
    {

    }

}
