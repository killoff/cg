<?php
class Cg_Register_Block_Register extends Mage_Adminhtml_Block_Template
{
    protected $_template = 'cg/register/register.phtml';

    protected $_options = array();

    protected $_isScheduleGrouped = false;
    protected $_groupedSchedule = array();


    public function __construct()
    {
        parent::__construct();
        $this->_options = array(
            'period_start' => '2013-07-02 00:00:00',
            'period_end' => '2013-07-30 23:59:59',
            'day_start' => '09:00:00',
            'day_end' => '19:00:00',
        );
    }


    public function getAll()
    {

        $result = array();
        foreach ($this->getDays() as $day) {
            $dayDate = $day->format('Y-m-d');
            $result[$dayDate] = array(
                'date' => $dayDate,
                'rooms' => $this->getRoomsWithSchedule($day)
            );
        }
        return $result;
    }

    public function getRoomsWithSchedule(DateTime $date)
    {
        $result = array();
        foreach ($this->getRooms() as $room) {
            $room['schedule'] = $this->getRoomSchedule($room['room_id'], $date);
            $result[] = $room;
        }
        return $result;
    }

    /**
     * @return DatePeriod
     */
    public function getDays()
    {
        $start = new DateTime($this->getOption('period_start'));
        $end = new DateTime($this->getOption('period_end'));
        $interval = new DateInterval('P1D'); // 1 Day
        $period = new DatePeriod($start, $interval, $end);
        return $period;
    }

    protected $_rooms = null;
    public function getRooms()
    {
        if ($this->_rooms === null) {
            $this->_rooms = $this->getRoomCollection()->getData();
        }
        return $this->_rooms;
    }

    public function getRoomSchedule($roomId, DateTime $date)
    {
        $day = $date->format('Y-m-d');
        $this->_groupSchedule();
        $schedule = isset($this->_groupedSchedule[$day][$roomId]) ? $this->_groupedSchedule[$day][$roomId] : array();

        $start = $day.' '.$this->_options['day_start'];
        $end = $day.' '.$this->_options['day_end'];
        $schedule = $this->addMissingIntervals($start, $end, $schedule);
        foreach ($schedule as &$item) {
            if (!$item['generated']) {
                $item['register'] = $this->getRegister($item);
            }
        }
        return $schedule;
    }

    protected $_register = null;
    public function getRegister($schedule)
    {
        if ($this->_register === null) {
            $allRegister = Mage::getModel('cg_register/register')->getCollection()->joinInformation()->getData();
            $result = array();
            foreach ($allRegister as $item) {
                $result[$item['schedule_id']][] = $item;
            }
            $this->_register = $result;
        }
        $scheduleId = $schedule['schedule_id'];
        return isset($this->_register[$scheduleId]) ? $this->addMissingIntervals($schedule['start'], $schedule['end'], $this->_register[$scheduleId]) : array();
    }

    protected function _groupSchedule()
    {
        if (!$this->_isScheduleGrouped) {
            $allSchedule = $this->getSchedulesCollection()->getData();
            foreach ($allSchedule as $schedule) {
                $schedule['register'] = array();
                $schedule['generated'] = true;
                $roomId = $schedule['room_id'];
                $date = new DateTime($schedule['start']);
                $day = $date->format('Y-m-d');
                $this->_groupedSchedule[$day][$roomId][] = $schedule;
            }
            $this->_isScheduleGrouped = true;
        }
        return $this;
    }

    public function getSchedulesCollection()
    {
        /** @var Cg_Employee_Model_Resource_Schedule_Collection $collection  */
        $collection = Mage::getModel('cg_employee/schedule')->getCollection();
        $collection->setStartDate($this->getOption('period_start'))
            ->setEndDate($this->getOption('period_end'))
            ->setOrder('start', 'ASC')
            ->joinInformation();

        return $collection;
    }

    public function getRoomCollection()
    {
        $collection = Mage::getModel('cg_office/room')->getCollection();
        return $collection;
    }

    public function addMissingIntervals($start, $end, array $intervals = array())
    {
        if (empty($intervals)) {
            return array($this->_getIntervalInfo($start, $end, array('generated' => 1)));
        }

        $result = array();

        if ($start != $intervals[0]['start']) {
            $result[] = $this->_getIntervalInfo($start, $intervals[0]['start'], array('generated' => 1));
        }

        $c = count($intervals);
        for($i = 0; $i < $c; $i++) {
            $current = $intervals[$i];
            $current['generated'] = 0;

            $prevIndex = $i - 1;
            if ($i > 0) {
                $prev = $intervals[$prevIndex];
                if ($prev['end'] != $current['start']) {
                    $result[] = $this->_getIntervalInfo($prev['end'], $current['start'], array('generated' => 1));
                }
            }
            $result[] = $this->_getIntervalInfo($current['start'], $current['end'], $current);
        }

        if ($end != $intervals[$c-1]['end']) {
            $result[] = $this->_getIntervalInfo($intervals[$c-1]['end'], $end, array('generated' => 1));
        }
        return $result;
    }

    protected function _getIntervalInfo($start, $end, $additional = array())
    {
        $startDate = new DateTime($start);
        $endDate = new DateTime($end);
        $info = array(
            'start' => $start,
            'end' => $end,
            'start_time' => $startDate->format('H:i'),
            'end_time' => $endDate->format('H:i'),
            'duration' => $this->getIntervalMinutesDuration($start, $end)
        );
        $info['height'] = $this->getIntervalHeight($info['duration']);
        if (!empty($additional)) {
            foreach ($additional as $k => $v) {
                $info[$k] = $v;
            }
        }
        return $info;
    }

    public function getIntervalMinutesDuration($start, $end)
    {
        $start = new DateTime($start);
        $end = new DateTime($end);
        $interval = $start->diff($end, true);
        return $interval->h * 60 + $interval->i;
    }

    public function getIntervalHeight($duration)
    {
        return $duration;
    }

    public function setOption($key, $value)
    {
        $this->_options[$key] = $value;
        return $this;
    }

    public function getOption($key, $value = null)
    {
        return isset($this->_options[$key]) ? $this->_options[$key] : null;
    }

    public function getCategories()
    {
        $collection = Mage::getModel('cg_product/category')->getCollection();
        foreach ($collection as $category) {
            $products = $category->getProducts();
            $category->setProductsCollection($products);
        }
        return $collection;
    }

    public function getUsers()
    {
        return Mage::getModel('admin/user')->getCollection();
    }
}
