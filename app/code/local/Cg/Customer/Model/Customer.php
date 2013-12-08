<?php
class Cg_Customer_Model_Customer extends Mage_Customer_Model_Customer
{
    public function getName()
    {
        return $this->getLastname().' '.$this->getFirstname().' '.$this->getMiddlename();
    }

    public function getAge()
    {
        if (!$this->getDob()) {
            return 0;
        }
        $dateTime = new DateTime($this->getDob());
        $today = new DateTime('@'.time());
        $interval = $dateTime->diff($today);
        return $interval->format('%y');
    }

}
