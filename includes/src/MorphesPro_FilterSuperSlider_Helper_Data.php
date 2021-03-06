<?php
/** 
 * @category    Morphes
 * @package     MorphesPro_FilterSuperSlider
 * @copyright   Copyright (c) http://www.morphes.ru
 * @license     http://www.morphes.ru/license  Proprietary License
 */
/**
 * Generic helper functions for MorphesPro_FilterSuperSlider module. This class is a must for any module even if empty.
 * @author Morphes Team
 */
class MorphesPro_FilterSuperSlider_Helper_Data extends Mage_Core_Helper_Abstract {
    public function formatNumber($number, $options) {
        if ($options->getSliderThreshold() && $number >= $options->getSliderThreshold()) {
            return $this->_formatNumber(round($number / $options->getSliderThreshold(), $options->getSliderDecimalDigits2()),
                $options->getSliderNumberFormat2(),
                $options->getSliderDecimalDigits2(),
                $options->getThousandSeparator());
        }
        else {
            return $this->_formatNumber($number,
                $options->getSliderNumberFormat(),
                $options->getSliderDecimalDigits(),
                $options->getThousandSeparator());
        }
    }
    protected function _formatNumber($number, $unitOfMeasureFormat, $decimalDigits, $thousandSeparator) {
        $number = Mage::app()->getLocale()->getNumber($number);
        if ($thousandSeparator) {
            $number = number_format($number, $decimalDigits, $this->getDecimalSymbol(), $this->getGroupSymbol());
        }
        else {
            $format = sprintf('01.%dF', $decimalDigits);
            $number = sprintf('%' . $format, $number);
            $number = str_replace('.', $this->getDecimalSymbol(), $number);
        }

        return (string)($unitOfMeasureFormat ? str_replace('0', $number, $unitOfMeasureFormat) : $number);
    }
    public function beforeInput($options) {
        return mb_substr($options->getSliderNumberFormat(), 0, mb_strpos($options->getSliderNumberFormat(), '0'));
    }
    public function afterInput($options) {
        return mb_substr($options->getSliderNumberFormat(), mb_strpos($options->getSliderNumberFormat(), '0') + 1);
    }
    public function getAttributeUrl($name) {
        $query = array(
            $name => '__0__',
            Mage::getBlockSingleton('page/html_pager')->getPageVarName() => null // exclude current page from urls
        );
        return Mage::getUrl('*/*/*', array('_current' => true, '_use_rewrite' => true, '_query' => $query));
    }
    public function getDecimalSymbol() {
        $locale = Mage::app()->getLocale()->getLocaleCode();
        $symbols = Zend_Locale_Data::getList($locale, 'symbols');
        return $symbols['decimal'];
    }

    public function getGroupSymbol() {
        $locale = Mage::app()->getLocale()->getLocaleCode();
        $symbols = Zend_Locale_Data::getList($locale, 'symbols');
        return $symbols['group'];
    }
}