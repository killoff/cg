<?php
class Cg_Employee_Model_Employee
{
    /**
     * Return current logged in admin user
     *
     * @return Mage_Admin_Model_User
     */
    public function getUser()
    {
        return Mage::getSingleton('admin/session')->getUser();
    }
}
