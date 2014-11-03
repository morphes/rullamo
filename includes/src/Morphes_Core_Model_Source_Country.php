<?php
/** 
 * @category    Morphes
 * @package     Morphes_Core
 * @copyright   Copyright (c) http://www.morphes.ru
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/**
 * @author Morphes Team
 *
 */
class Morphes_Core_Model_Source_Country extends Morphes_Core_Model_Source_Abstract {
    protected function _getAllOptions() {
        return Mage::getResourceModel('directory/country_collection')->load()->toOptionArray();
    }
}