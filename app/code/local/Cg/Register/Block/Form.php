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
        $periods = $helper->getAvailablePeriods($this->getScheduleId(), $this->getStart());
        $result = array();
        foreach ($periods as $period) {
            $start = $period['start'];
            $end = $period['end'];
            $startString = $start->format(Varien_Date::DATETIME_PHP_FORMAT);
            $endString = $end->format(Varien_Date::DATETIME_PHP_FORMAT);
            $result[] = new Varien_Object(array(
                'start' => $startString,
                'end' => $endString,
                'start_time' => $start->format('H:i'),
                'end_time' => $end->format('H:i'),
                'value' => $startString . '.' . $endString
            ));
        }
        return $result;
    }

    public function getSaveUrl()
    {
        return $this->getUrl('*/register_ajax/save', array('schedule_id' => $this->getScheduleId(), 'customer_id' => $this->getCustomerId()));
    }
}
