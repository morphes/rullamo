<?php
/**
 * @category    Morphes
 * @package     Morphes_Core
 * @copyright   Copyright (c) http://www.morphes.ru
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Source for options of filter being filterable
 * @author Morphes Team
 *
 */
class Morphes_Core_Model_Source_Yesno extends Morphes_Core_Model_Source_Abstract {
	protected function _getAllOptions() {
		return array(
            array('value' => '1', 'label' => Mage::helper('core')->__('Yes')),
            array('value' => '0', 'label' => Mage::helper('core')->__('No')),
        );
	}
}