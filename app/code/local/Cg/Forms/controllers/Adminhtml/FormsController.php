<?php
class Cg_Forms_Adminhtml_FormsController extends Mage_Adminhtml_Controller_Action
{
    public function pingAction()
    {
        // renew admin session
    }

    /**
     * Init actions
     *
     * @return Cg_Forms_Adminhtml_FormsController
     */
    protected function _initAction()
    {
        $this->_setActiveMenu('customer/form');
        $this->_title($this->__('Customer Forms'));

        return $this;
    }

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_initAction();

        if ($this->getRequest()->getQuery('ajax')) {
            $this->_forward('grid');
            return;
        }

        $this->_addContent($this->getLayout()->createBlock('cg_forms/list'));
        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->loadLayout()->renderLayout();
        return $this;
        $this->getLayout()->getUpdate()
            ->addHandle(strtolower($this->getFullActionName()));
        $this->loadLayoutUpdates()->generateLayoutXml()->generateLayoutBlocks();
        $this->renderLayout();
    }

    public function newAction()
    {
        $form = Mage::getModel('cg_forms/form');
        if ($this->getRequest()->getParam('id')) {
            $form->load($this->getRequest()->getParam('id'));
        }
        Mage::register('current_form', $form);

        $productId = $this->getRequest()->getParam('product_id', $form->getProductId());
        if ($productId) {
            $product = Mage::getModel('cg_product/product')->load($productId);
            Mage::register('current_product', $product);
        } else {
            $this->loadLayout();
            $this->_addContent($this->getLayout()->createBlock('cg_forms/edit_product'));
            $this->renderLayout();
            return;
        }

        $customerId = $this->getRequest()->getParam('customer_id', $form->getCustomerId());
        if ($customerId) {
            $customer = Mage::getModel('customer/customer')->load($customerId);
            Mage::register('current_customer', $customer);
        } else {
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('cg_forms')->__('Select patient.'));
            $this->_redirect('*/customer');
            return;
        }

        $this->loadLayout();
        $this->_initAction();
        $this->_title($this->__('New Form'));

        $this->_addContent(
            $this->getLayout()->createBlock('cg_forms/edit')
        );
        $this->renderLayout();
    }

    public function editAction()
    {
        $this->_forward('new');
    }

    public function searchAction()
    {
        $q = $this->getRequest()->getParam('term');
        /** @var $collection Cg_Forms_Model_Resource_Visit_Collection */

        $collection = Mage::getResourceModel('customer/customer_collection')
            ->addNameToSelect()
            ->addAttributeToFilter(array(
                array('attribute' => 'firstname', 'like' => $q.'%'),
                array('attribute' => 'lastname', 'like' => $q.'%')
            ))
            ->setPage(1, 10)
            ->load();

        $result = array();
        foreach ($collection->getItems() as $customer) {
            $result[] = array(
                'id' => $customer->getId(),
                'label' => $customer->getName(),
                'value' => $customer->getName()
            );
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    public function saveAction()
    {
        try {
            $data = $this->getRequest()->getPost();
            $data = $this->_filterDates($data, array('user_date'));

            /** @var $visit Cg_Forms_Model_Visit */
            $form = Mage::getModel('cg_forms/form');
            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $form->load($id);
            } else {
                $form->setCustomerId($this->getRequest()->getParam('customer_id'));
                $form->setProductId($this->getRequest()->getParam('product_id'));
                $form->setAdminId(Mage::getSingleton('admin/session')->getUser()->getId());
            }
            $files = is_array($form->getFiles()) ? $form->getFiles() : array();
            $newFiles = $this->getRequest()->getPost('files', array());
            foreach ($newFiles as $file) {
                $info = json_decode($file,true);
                if (is_file($info['path'])) {
                    /** @var Cg_Kernel_Helper_Thumbnail $helper */
                    $helper = Mage::helper('cg_kernel/thumbnail');
                    $helper->setSource($info['path']);
                    $helper->setDestination($this->_getImgDir());
                    $name = pathinfo($info['path'], PATHINFO_FILENAME);
                    $helper->setName($name . '.jpg');
                    $path = $helper->getPath(600, 600);
                    $files[] = array('path' => $path, 'name' => pathinfo($path, PATHINFO_BASENAME));
                }
            }
            $rowData = $this->getRequest()->getParam('row_data');
            $rowData['files'] = $files;
            $form->setUserDate($data['user_date']);
            $form->setRowData($rowData);
            $form->save();
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('cg_forms')->__('Form has been saved successfully.'));
            $this->_redirect('*/*/edit', array('id' => $form->getId()));

        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function deleteAction()
    {
        try {
            /** @var $visit Cg_Forms_Model_Visit */
            $form = Mage::getModel('cg_forms/form')->load($this->getRequest()->getParam('id'));
            if ($form->getId()) {
                $form->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('cg_forms')->__('Form has been deleted successfully.'));
                $this->_redirect('*/customer/edit', array(
                                                         'id' => $form->getCustomerId(),
                                                         'tab' => 'customer_info_tabs_customer_edit_tab_forms'
                                                    ));
            }
        } catch (Exception $e) {

        }
    }

    public function deleteFileAction()
    {
        $file = $this->getRequest()->getParam('file');
        $form = Mage::getModel('cg_forms/form')->load($this->getRequest()->getParam('id'));
        $files = $form->getFiles();
        $newFiles = array();
        if (is_array($files)) {
            foreach ($files as $item) {
                if ($item['name'] != $file) {
                    $newFiles[] = $item;
                }
            }
        }

        $rowData = @unserialize($form->getRowData());
        $rowData = is_array($rowData) ? $rowData : array();
        $rowData['files'] = $newFiles;
        $form->setRowData($rowData);
        $form->save();
        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('cg_forms')->__('You deleted an image.'));
        $this->_redirect('*/*/edit', array('id' => $form->getId()));
    }

    public function printAction()
    {
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('cg_forms/print')->toHtml()
        );
    }

    public function uploadAction()
    {
        $extension = pathinfo($_FILES['qqfile']['name'], PATHINFO_EXTENSION);
        $dest = $this->_getImgDir().'/'.uniqid().'.'.$extension;
        @copy($_FILES['qqfile']['tmp_name'], $dest);
        if (is_file($dest)) {
            $_FILES['qqfile']['path'] = $dest;
        }
        $this->getResponse()->setBody(
            json_encode(
                array(
                     'success' => true, 'file' => $_FILES['qqfile']
                )
            )
        );
    }

    protected function _getImgDir()
    {
        return Mage::app()->getConfig()->getOptions()->getMediaDir().'/forms';
    }

    public function importAction()
    {
        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('cg_forms/import'));
        $this->renderLayout();
    }

    public function postimportAction()
    {
        if (!isset($_FILES['customers']) || $_FILES['customers']['error'] != '0') {
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('cg_forms')->__('No file specified.'));
            $this->_redirect('*/*/import');
            return;
        }
        $file = Mage::getConfig()->getOptions()->getVarDir().'/'.uniqid();
        move_uploaded_file($_FILES['customers']['tmp_name'], $file);

        $f = fopen($file, 'rb');
        $k = 0;
        fgets($f);

        while($data = fgets($f))
        {
            $row = explode("\t", $data);
            if (count($row) < 10) {
                continue;
            }
            $row = prepareRow($row);
            $firstnameMiddlename = preg_split('/\s+/', $row[2], 0, PREG_SPLIT_NO_EMPTY);
            $dob = explode('.', $row[13]);
            $dob = count($dob) == 3 ? $dob : false;

            $data = array(
                'website_id' => 0,
                'group_id' => 1,
                'store_id' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'is_active' => 1,
                'gender' => $row[12] == 'F' ? '2' : '1',
                'firstname' => isset($firstnameMiddlename[0]) ? $firstnameMiddlename[0] : '',
                'middlename' => isset($firstnameMiddlename[1]) ? $firstnameMiddlename[1] : '',
                'lastname' => isset($row[3]) ? $row[3] : '',
                'suffix' => '',
                'taxvat' => '',
                'dob' => $dob ? $dob['2'].'-'.$dob[1].'-'.$dob[0].' 00:00:00' : '',
                'password_hash' => '8c37f14bfdb13f8c46e0a359e7032491:4S',
                'created_in' => 'Admin'
            );
            $data['email'] = md5($data['firstname'].'-'.$data['middlename'].'-'.$data['lastname'].'-'.$data['dob']);
            $data['email'] = substr($data['email'], 0, 5).'@localhost.com';

            /** @var $customer Mage_Customer_Model_Customer */
            $customer = Mage::getModel('customer/customer')->setWebsiteId(0);
            $customer->loadByEmail($data['email']);
            if ($customer->getId()) {
                continue;
            }
            $customer->setData($data);

            $address = Mage::getModel('customer/address');
            $address->setData(array(
                                   'created_at' => date('Y-m-d H:i:s'),
                                   'updated_at' => date('Y-m-d H:i:s'),
                                   'is_active' => '1',
                                   'firstname' => isset($firstnameMiddlename[0]) ? $firstnameMiddlename[0] : '',
                                   'middlename' => isset($firstnameMiddlename[1]) ? $firstnameMiddlename[1] : '',
                                   'lastname' => isset($row[3]) ? $row[3] : '',
                                   'company' => '',
                                   'city' => $row[6],
                                   'country_id' => 'UA',
                                   'region' => '',
                                   'postcode' => '0000',
                                   'telephone' => to_phone($row[11]),
                                   'fax' => to_phone($row[9]),
                                   'vat_id' => '',
                                   'region_id' => '0',
                                   'street' => $row[4],
                                   'is_default_billing' => 1,
                                   'is_default_shipping' => 1,
                              ));
            $customer->addAddress($address);

            try {

                $customer->save();
            } catch (Exception $e) {
                echo $e->getMessage().'<br><br>';
                print_R($data);
            }

            $k++;
//            if ($k>12000) exit;
        }

        fclose($f);
        unlink($file);

        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('cg_forms')->__('%s of customers added.', $k));
        $this->_redirect('*/*/import');
    }




}


function prepareRow($row)
{
    foreach ($row as &$value) {
        $value = iconv('windows-1251', 'utf-8', trim($value));
    }
    return $row;
}

function to_phone($number)
{
    $number = preg_replace('/[^\d]+/', '', $number);
    switch (strlen($number)) {
        case 10:
            $number = '38' . $number;
            break;
        case 11:
            $number = '3' . $number;
            break;
        default:
            break;
    }
    if (strlen($number) != 12) {
        return $number;
    }
    $nomer = substr($number, -7);
    $code = substr($number, -10, 3);
    $prefix = substr($number, 0, 2);
    return sprintf('+%s(%s)%s', $prefix, $code,  $nomer);
}
