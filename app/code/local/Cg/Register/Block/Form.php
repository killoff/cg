<?php
class Cg_Register_Block_Form extends Mage_Adminhtml_Block_Template
{
    protected $_template = 'cg/register/register-form.phtml';

    public function getProducts()
    {
        $userId = $this->getUserId();
        $productIds = Mage::getResourceHelper('cg_employee')->getProductIds($userId);
        $collection = Mage::getResourceModel('cg_product/product_collection')
            ->addFieldToFilter('product_id', array('in' => $productIds));
        return $collection;
    }

    public function getCustomerName()
    {
        return Mage::getModel('customer/customer')->load($this->getCustomerId())->getName();
    }

    public function getEmployeeName()
    {
        return Mage::getModel('admin/user')->load($this->getUserId())->getName();
    }

    public function getDayDate()
    {
        return Varien_Date::formatDate($this->getStart(), false);
    }

    public function getTimePeriods()
    {
        /** @var Cg_Register_Helper_Schedule $helper */
        $helper = Mage::helper('cg_register/schedule');
        $dateHelper = Mage::helper('cg_kernel/date');
        $periods = $helper->getAvailableIntervals($this->getScheduleId(), $this->getStart());
        $result = array();
        foreach ($periods as $period) {
            $start = $period['start'];
            $end = $period['end'];
            $result[] = new Varien_Object(array(
                'start' => $start,
                'end' => $end,
                'start_time' => $dateHelper->format($start, 'H:i'),
                'end_time' => $dateHelper->format($end, 'H:i'),
                'duration' => ($end - $start) / 60,
                'value' => $start . '-' . $end
            ));
        }
        return $result;
    }

    public function getSaveUrl()
    {
        return $this->getUrl('*/register_ajax/save', array('schedule_id' => $this->getScheduleId(), 'customer_id' => $this->getCustomerId()));
    }
}
