<?php
/**
 * Class Cg_Register_Adminhtml_AjaxController
 */
class Cg_Register_Adminhtml_Register_AjaxController extends Cg_Kernel_Controller_Action
{
    public function addAction()
    {
        $this->loadLayout();


        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('cg_register/form')
                ->setUserId($this->getRequest()->getParam('user_id'))
                ->setCustomerId($this->getRequest()->getParam('customer_id'))
                ->setStart($this->getRequest()->getParam('start'))
                ->setScheduleId($this->getRequest()->getParam('schedule_id'))
                ->toHtml()
        );
    }

    public function saveAction()
    {
        $request = $this->getRequest();
        $register = Mage::getModel('cg_register/register');
        $period = explode('-', $request->getParam('period'));
        $start = $period[0];
        $end = $period[1];
        $schedule = Mage::getModel('cg_employee/schedule')->load($request->getParam('schedule_id'));
        /** @var Cg_Register_Helper_Schedule $helper */
        $helper = Mage::helper('cg_register/schedule');
        if ($helper->isPeriodConflicted($schedule, $start, $end)) {
            throw new Exception('Periods conflicted');
        }
        $register->addData(array(
            'schedule_id' => $request->getParam('schedule_id'),
            'customer_id' => $request->getParam('customer_id'),
            'product_id' => $request->getParam('product_id'),
            'start' => $start,
            'end' => $end
        ));
        $register->save();

        $dateHelper = Mage::helper('cg_kernel/date');
        $response = array(
            'start' => $dateHelper->format($start),
            'end' => $dateHelper->format($end),
            'title' =>  Mage::getModel('customer/customer')->load($request->getParam('customer_id'))->getName(),
            'allDay' => false,
            'editable' => false,
            'timestamp_start' => $start
        );

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));

    }
}
