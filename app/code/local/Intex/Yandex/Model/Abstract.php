<?php
/**

 */
abstract class Intex_Yandex_Model_Abstract extends Mage_Core_Model_Abstract
{
    /**
     * Get collection instance
     *
     * @param  string $name
     * @return object
     */
    protected function _esc($str)
    {
        $str = htmlspecialchars($str, ENT_COMPAT, 'UTF-8');
        return str_replace("'", '&apos;', $str);
    }
}