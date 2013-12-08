<?php
class Cg_Employee_Model_Observer
{
    /**
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function saveEmployeeData(Varien_Event_Observer $observer)
    {
        /** @var $user Mage_Admin_Model_User Object */
        $user = $observer->getObject();

        Mage::getResourceHelper('cg_employee')
            ->saveProductIds($user->getId(), $user->getDataSetDefault('product', array()));

        if ($user->hasData('schedule')) {
            try {
//                print_R($user->getData('schedule'));exit;
                $schedule = Mage::helper('core')->jsonDecode($user->getData('schedule'));
                Mage::getResourceHelper('cg_employee')->saveSchedule($user->getId(), $schedule);

            } catch (Exception $e) {

            }
        }
    }

    public function beforeSaveEmployeeData(Varien_Event_Observer $observer)
    {
        /** @var $user Mage_Admin_Model_User Object */
        $user = $observer->getObject();
        $position = $user->getData('position');
        $extra = unserialize($user->getExtra());
        $extra['position'] = $position;
        $user->setExtra(serialize($extra));
    }


}
