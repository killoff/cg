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

    public function getTimeIntervals()
    {
        $result = array();
        $start = new DateTime($this->getStartDate());
        $end = new DateTime($this->getEndDate());

//        $intervalEnd =
        while($start < $end) {
            $start =
            $result[] = array('start' => $start, 'end' => $start->add(new DateInterval('P1H')));

        }


    }
}
