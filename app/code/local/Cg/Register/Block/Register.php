<?php
class Cg_Register_Block_Register extends Mage_Adminhtml_Block_Template
{
    protected $_template = 'cg/register/register.phtml';

    protected $_options = array();

    protected $_isScheduleGrouped = false;
    protected $_groupedSchedule = array();

    protected $_employeeIdsFilter = array();
    protected $_productIdsFilter = array();

    protected function _construct()
    {
        parent::_construct();

        /** @var Cg_Kernel_Helper_Data $helper */
        $helper = Mage::helper('cg_kernel/date');
        $this->_options = array(
            'period_start' => $helper->todayStart(),
            'period_end' => $helper->todayEnd() + 14*24*60*60,
            'day_start' => '09:00:00',
            'day_end' => '19:00:00',
        );
        $customerId = $this->getRequest()->get('customer_id');
        if ($customerId) {
            $customer = Mage::getModel('customer/customer')->load($customerId);
            $this->setCustomer($customer);
        }

        $products = $this->getRequest()->get('products');
        if ($products) {
            $products = preg_split('/,/', $products, 0, PREG_SPLIT_NO_EMPTY);
            $this->setProductIdsFilter($products);
        }
        $users = $this->getRequest()->get('users');
        if ($users) {
            $users = preg_split('/,/', $users, 0, PREG_SPLIT_NO_EMPTY);
            $this->setEmployeeIdsFilter($users);
        }
    }

    public function setEmployeeIdsFilter(array $ids)
    {
        $this->_employeeIdsFilter = $ids;
    }

    public function setProductIdsFilter(array $ids)
    {
        $this->_productIdsFilter = $ids;
    }

    public function getTodayTimestamp()
    {
        return Mage::helper('cg_kernel/date')->todayStart();
    }

    protected $_rooms = null;
    public function getRooms()
    {
        if ($this->_rooms === null) {
            $this->_rooms = $this->getRoomCollection()->getData();
        }
        return $this->_rooms;
    }

    public function getSchedulesCollection()
    {
        /** @var Cg_Employee_Model_Resource_Schedule_Collection $collection  */
        $collection = Mage::getModel('cg_employee/schedule')->getCollection();
        $collection->setStartDate($this->getOption('period_start'))
            ->setEndDate($this->getOption('period_end'))
            ->setOrder('start', 'ASC')
            ->setProductIds($this->_productIdsFilter)
            ->joinInformation();

        return $collection;


        $employeeFilter = $this->_employeeIdsFilter;
        if ($this->_productIdsFilter) {
            $employeeFilter = array_merge(
                $employeeFilter,
                Mage::getResourceHelper('cg_employee')->getUserIdsByProductIds($this->_productIdsFilter)
            );
        }
        $collection->addEmployeeFilter($employeeFilter);
//        echo $collection->getSelect()->__toString();
    }


    public function getRoomCollection()
    {
        $roomIds = array();
        foreach ($this->getSchedulesCollection() as $schedule) {
            $roomId = $schedule->getRoomId();
            $roomIds[$roomId] = $roomId;
        }
        $collection = Mage::getModel('cg_office/room')->getCollection()
            ->addFieldToFilter('main_table.room_id', array('in' => $roomIds));
        return $collection;
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


    protected $_roomEvents = null;
    protected $_perfectDays = array();
    protected $_periods = array();
    protected $_scheduleWithRegisters = array();
    protected $_productTitles = array();
    protected $_scheduleRooms = array();
    protected function _generateRoomEvents()
    {
        if ($this->_roomEvents === null) {
            $helper = Mage::helper('cg_kernel/date');
            $this->_roomEvents = array();
            $schedule = $this->getSchedulesCollection()->getData();
            foreach ($schedule as $row) {
                $roomId = $row['room_id'];
                $roomEvent = array(
                    'start' => $helper->format($row['start']),
                    'end' => $helper->format($row['end']),
                    'title' => $row['admin_name'],
                    'allDay' => false,
                    'editable' => false,
                    'user_id' => $row['user_id'],
                    'schedule_id' => $row['schedule_id'],
                    'product_id' => $row['product_id'],
                    'duration' => $row['duration'],
                    'timestamp_start' => $row['start'],
                    'timestamp_end' => $row['end'],
                    'backgroundColor' => $this->getBackground($row['user_id'])
                );
                $this->_roomEvents[$roomId][] = $roomEvent;
                $dayKey = date('Y-m-d', $row['start']);
                $this->_perfectDays[$dayKey] = 1;
                $this->_productTitles[$row['schedule_id']] = $row['product_name'];
                $this->_scheduleWithRegisters[$dayKey][$row['schedule_id']] = $roomEvent;
                $this->_scheduleRooms[$row['schedule_id']] = $roomId;
            }

            $register = Mage::getModel('cg_register/register')->getCollection()
                ->setStartDate($this->getOption('period_start'))
                ->setEndDate($this->getOption('period_end'))
                ->addScheduleIdsFilter($this->getSchedulesCollection()->getAllIds())
                ->joinInformation()
                ->getData();
            foreach ($register as $row) {
                $roomId = $row['room_id'];
                $roomEvent = array(
                    'start' => $helper->format($row['start']),
                    'end' => $helper->format($row['end']),
                    'timestamp_start' => $row['start'],
                    'timestamp_end' => $row['end'],
                    'title' => $row['customer_name'],
                    'allDay' => false,
                    'editable' => true
                );
                $this->_roomEvents[$roomId][] = $roomEvent;

                $dayKey = date('Y-m-d', $row['start']);
                $this->_scheduleWithRegisters[$dayKey][$row['schedule_id']]['register'][] = $roomEvent;
            }
        }

        return $this->_roomEvents;
    }

    public function getAvailableDaysJson()
    {
        return json_encode($this->_perfectDays);
    }

    public function getBackground($userId)
    {
        $colors = array(
            '888' => 'rgb(160, 160, 160)',
            '16' => 'rgb(130, 175, 111)',
            '9' => 'rgb(209, 91, 71)',
            '444' => 'rgb(149, 133, 191)',
            '455' => 'rgb(254, 225, 136)',
            '1' => 'rgb(214, 72, 126)',
            '21' => 'rgb(58, 135, 173)'
        );
        return isset($colors[$userId]) ? $colors[$userId] : '#5bc0de';
    }

    public function getRoomEvents($roomId)
    {
        $this->_generateRoomEvents();
        return isset($this->_roomEvents[$roomId]) ? $this->_roomEvents[$roomId] : array();
    }

    public function getAvailableRegistrations()
    {
        $this->_generateRoomEvents();
        $all = $this->_scheduleWithRegisters;

        $result = array();

        foreach ($all as $dayKey => $schedules) {
            $set = array();
            $products = array();

            $startDay = new DateTime($dayKey.' 09:00:00');
            $endDay = new DateTime($dayKey.' 18:00:00');

            foreach ($schedules as $scheduleId => $schedule) {
                $set[$scheduleId] = $this->getScheduleSet($dayKey, $scheduleId);
                $products[$scheduleId] = $schedule['duration'] * 60;
            }
//            print_r($set);
            $step = 900;
            for($i = $startDay->getTimestamp(); $i < $endDay->getTimestamp(); $i += $step) {
                $combination = $this->generateProductsSequence($i, $products);
//                print_r($combination);
                if ($this->isSuitableCombination($combination, $set)) {
                    $result[$dayKey][] = $this->prepareCombination($combination);
                }
            }
            break;
        }
        return $result[date('Y-m-d')];
    }

    public function prepareCombination($combinations)
    {
        $minStart = null;
        $maxEnd = 0;
        foreach ($combinations as $scheduleId => $interval) {
            if ($minStart === null) {
                $minStart = $interval['start'];
            }
            $minStart = min($interval['start'], $minStart);
            $maxEnd = max($interval['end'], $maxEnd);
        }

        $result = array('id' => md5($minStart.'-'.$maxEnd), 'start' => $minStart, 'end' => $maxEnd, 'title' => date('H:i', $minStart) . ' &ndash; '.date('H:i', $maxEnd));

        $helper = Mage::helper('cg_kernel/date');
        foreach ($combinations as $scheduleId => $interval) {
            $result['events'][$this->_scheduleRooms[$scheduleId]] = array(
                'start' => $helper->format($interval['start']),
                'end' => $helper->format($interval['end']),
                'title' =>  $this->_productTitles[$scheduleId],
                'allDay' => false,
                'editable' => false,
                'id' => 'temp'
            );
        }
        return $result;
    }

    public function getScheduleSet($dayKey, $scheduleId)
    {
        $schedule = $this->_scheduleWithRegisters[$dayKey][$scheduleId];
        return array(
            array($schedule['timestamp_start'], $schedule['timestamp_end'])
        );
    }

    public function isSuitableCombination($combo, $set)
    {
        foreach ($combo as $productId => $interval) {
            if (!$this->isSuitableOverlap($interval['start'],$interval['end'], $set[$productId])) {
                return false;
            }
        }
        return true;
    }

    public function isSuitableOverlap($start, $end, $set)
    {
        foreach ($set as $interval) {
            $overlap = min($end, $interval[1]) - max($start, $interval[0]);
            $minSuitable = (int)(0.8 * ($end - $start));
            if ($overlap > 0 && $overlap > $minSuitable) {
                return $overlap;
            }
        }
        return false;
    }

    public function generateProductsSequence($time, $products)
    {
        $result = array();
        foreach ($products as $productId => $duration) {
            $end =  $time + $duration;
            $result[$productId] = array('start' => $time, 'end' => $end);
            $time = $end;
        }
        return $result;
    }

    public function getProductScheduleMapping()
    {

    }

    public function getRoomJsonOptions($room)
    {
        $options = array(
            'height' => 1000,
            'minTime' => 9,
            'maxTime' => 19,
            'slotMinutes' => 15,
            'defaultView' => 'agendaDay',
            'allDaySlot' => false,

            'axisFormat' => 'H:mm',
            'header' => array('left' => '', 'right' => '', 'center' => ''),
            'columnFormat' => array('day' => $room['name']),
            'selectable' => true,
            'selectHelper' => true
        );
        $options['events'] = $this->getRoomEvents($room['room_id']);
        return json_encode($options);
    }
}


class Register_Generation
{


}
