<?php
class Cg_Kernel_Helper_Data extends Mage_Core_Helper_Abstract
{
    public static function declension($int, $expressions)
    {
        if (count($expressions) < 3) $expressions[2] = $expressions[1];
        settype($int, "integer");
        $count = $int % 100;
        if ($count >= 5 && $count <= 20) {
            $result = $expressions['2'];
        } else {
            $count = $count % 10;
            if ($count == 1) {
                $result = $expressions['0'];
            } elseif ($count >= 2 && $count <= 4) {
                $result = $expressions['1'];
            } else {
                $result = $expressions['2'];
            }
        }
        return $result;
    }

    public static function getAgeString($years)
    {
        return $years . ' ' . self::declension($years, array('год','года','лет'));
    }
}
