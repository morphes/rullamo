<?php
/**

 */


class Intex_Yandex_Model_Entity_Attribute_Source_OfferType extends Mage_Eav_Model_Entity_Attribute_Source_Table
{
    /**
     * Retrive all attribute options
     *
     * @return array
     */
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = array(
                array(
                    'label' => Mage::helper('yandex')->__('vendor.model'),
                    'value' =>  0
                ),
                array(
                    'label' => Mage::helper('yandex')->__('artist.title'),
                    'value' =>  1
                ),
                array(
                    'label' => Mage::helper('yandex')->__('tour'),
                    'value' =>  2
                ),
                array(
                    'label' => Mage::helper('yandex')->__('ticket'),
                    'value' =>  2
                ),
                array(
                    'label' => Mage::helper('yandex')->__('event-ticket'),
                    'value' =>  2
                ),
            );
        }
        return $this->_options;
    }
}