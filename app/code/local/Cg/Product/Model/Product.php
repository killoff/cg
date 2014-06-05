<?php
class Cg_Product_Model_Product extends Mage_Core_Model_Abstract
{
    /**
     * Initialize resource model
     *
     */
    protected function _construct()
    {
        $this->_init('cg_product/product');
    }

    protected function _beforeSave()
    {
//        Mage::getResourceHelper()
    }

    protected function _afterLoad()
    {
    }

    public function getRoleIds()
    {
        return Mage::getResourceHelper('cg_product')->getProductRoleIds($this->getId());
    }

    protected function _afterSave()
    {
        // update protocol items (product_protocol) only if protocol text changed
        if (md5($this->getOrigData('protocol')) != md5($this->getData('protocol'))) {
//            header('Content-Type: text/html; charset=utf-8');
            $protocolRows = $this->parseProtocol($this->getData('protocol'));
//            print_r($protocolRows);exit;
            Mage::getResourceHelper('cg_product')->saveProtocolItems($this->getId(), $protocolRows);
        }
    }

    protected $_rows = array();

    public function parseProtocol($text)
    {
        $i = 1;
        $rows = explode("\n", $text);
        $isTemplate = false;
        foreach ($rows as $row) {
            $row = rtrim($row);
            // skip empty strings and comments
            if (empty($row) || strpos(ltrim($row), ';') === 0) {
                continue;
            }
            $level = 0;
            if (preg_match('/^(\s+)/', $row, $matches)) {
                $level = strlen($matches[1]);
            }

            if (strpos($row, '///') !== false) {
                $isTemplate = false;
                $i++;
                continue;
            }

            if ($isTemplate) {
                if (strpos(ltrim($row), '_') === 0) {
                    $row = "\n" . ltrim($row, "_");
                }
                $this->_rows[$i]['text'] .= $row;
                continue;
            }

            // template
            if (strpos($row, '<@P>') !== false) {
                $row = str_replace('<@P>', '<>', $row);
                $isTemplate = true;
            }

            $this->_rows[$i]['level'] = $level;
            $this->_rows[$i]['title'] = $this->_getProtocolRowTitle($row);
            $this->_rows[$i]['text'] = $this->_getProtocolRowText($row);
            $this->_rows[$i]['action'] = $this->_getProtocolRowAction($row);
            $this->_rows[$i]['parent'] = $this->_getParentRowIndex($i);

            if (!$isTemplate) {
                $i++;
            }
        }
        return $this->_rows;
    }

    protected function _getProtocolRowText($text)
    {
        $textStart = strpos($text, '<');
        $textEnd = strpos($text, '>');
        if ($textStart !== false && $textEnd !== false && $textEnd > $textStart) {
            return substr($text, $textStart + 1, $textEnd - $textStart - 1);
        }
        return '';
    }

    protected function _getProtocolRowTitle($text)
    {
        $textStart = strpos($text, '<');
        $textEnd = strpos($text, '>');
        if ($textStart !== false && $textEnd !== false && $textEnd > $textStart) {
            return trim(substr($text, 0, $textStart));
        }
        return trim($text);
    }

    protected function _getProtocolRowAction($text)
    {
        if (preg_match('/\/([a-z0-9]+)$/i', $text, $matches)) {
            return $matches[1];
        }
        return '';
    }

    protected function _getParentRowIndex($childIndex)
    {
        if (!isset($this->_rows[$childIndex - 1])) {
            return null;
        }
        for ($i = $childIndex - 1; $i >= 1; $i--) {
            if ($this->_rows[$i]['level'] < $this->_rows[$childIndex]['level']) {
                return $i;
            }
        }
        return null;
    }

}
