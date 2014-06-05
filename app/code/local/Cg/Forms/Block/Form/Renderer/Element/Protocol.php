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
 * Form fieldset renderer
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Cg_Forms_Block_Form_Renderer_Element_Protocol
    extends Mage_Adminhtml_Block_Widget_Form_Renderer_Fieldset_Element
    implements Varien_Data_Form_Element_Renderer_Interface
{
    /**
     * Form element which re-rendering
     *
     * @var Varien_Data_Form_Element_Fieldset
     */
    protected $_element;

    /**
     * Constructor
     */
    protected function _construct()
    {
        $this->setTemplate('cg/forms/form/renderer/element/protocol.phtml');

        $items = $this->getItems();
        if (is_array($items)) {
            $metadata = array();
            $children = array();
            foreach ($items as $row) {
                $metadata[$row['row_id']] = $row;
                $children[$row['parent_id']][] = $row['row_id'];
            }
            $this->setItemsMetadata($metadata);
            $this->setItemsChildren($children);
        }
    }

    /**
     * Retrieve an element
     *
     * @return Varien_Data_Form_Element_Fieldset
     */
    public function getElement()
    {
        return $this->_element;
    }

    /**
     * Render element
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $this->_element = $element;
        return $this->toHtml();
    }

    public function getItemsMetadataJson()
    {
//        foreach ($this->getDataSetDefault('items_metadata', array()) as $i => $row) {
//
//            var metadata = {"1":{"row_id":"1","parent_id":"0","title":"\u041b\u043e\u043a\u0442\u0435\u0432\u044b\u0435 \u0441\u0443\u0441\u0442\u0430\u0432\u044b","text":"","action":"","product_id":"29"},"2"
//        }
        $metadata = $this->getDataSetDefault('items_metadata', array());
        return json_encode($metadata);
    }

    public function getItemsChildrenJson()
    {
        $a = '_    ЗАКЛЮЧЕНИЕ: Ультразвуковых признаков патолог.';
        $children = $this->getDataSetDefault('items_children', array());
        return json_encode($children);
    }
}
