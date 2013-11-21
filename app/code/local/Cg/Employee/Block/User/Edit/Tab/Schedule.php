<?php
class Cg_Employee_Block_User_Edit_Tab_Schedule extends Mage_Adminhtml_Block_Template
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setTemplate('cg/employee/schedule.phtml');
        return parent::__construct();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return $this->__('Schedule');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->__('Schedule');
    }

    /**
     * Returns status flag about this tab can be shown or not
     *
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }

    public function getActive()
    {
        return true;
    }

    public function getScheduleJson()
    {
        $userId = Mage::registry('permissions_user')->getId();
        $schedule = Mage::getResourceHelper('cg_employee')->getSchedule($userId);
        $result = array();
        $helper = Mage::helper('cg_kernel/date');
        foreach ($schedule as $row) {
            $result[] = array(
                'title' => $row['room_name'],
                'start' => $helper->format($row['start']),
                'end' => $helper->format($row['end']),
                'allDay' => false,
                'id' => $row['schedule_id'],
            );
        }
        return Mage::helper('core')->jsonEncode($result,Zend_Json::TYPE_OBJECT);

    }


    public function getRooms()
    {
        return Mage::getModel('cg_office/room')->getCollection();
    }
}
