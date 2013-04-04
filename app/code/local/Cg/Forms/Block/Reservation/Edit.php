<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Poll edit form
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */

class Cg_Forms_Block_Reservation_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'cg_forms';
        $this->_controller = 'reservation';

        $this->_updateButton('save', 'label', Mage::helper('cg_forms')->__('Save Reservation'));
        $this->_updateButton('delete', 'label', Mage::helper('cg_forms')->__('Delete Reservation'));
//        $this->_removeButton('delete');
//        $this->_updateButton('print', 'label', Mage::helper('cg_forms')->__('Print Reservation'));
        $this->_removeButton('reset');
        $this->setValidationUrl($this->getUrl('*/*/validate', array('id' => $this->getRequest()->getParam($this->_objectId))));
    }

    public function getHeaderText()
    {
        if( Mage::registry('current_reservation')->getId() ) {
            return Mage::helper('cg_forms')->__("Edit Form '%s'", $this->htmlEscape(Mage::registry('current_reservation')->getId()));
        } else {
            return Mage::helper('cg_forms')->__('New Form');
        }
    }

    public function getValidationUrl()
    {
        return false;
    }
}
