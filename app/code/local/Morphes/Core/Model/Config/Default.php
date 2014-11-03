<?php
/**
 * @category    Morphes
 * @package     Morphes_Core
 * @copyright   Copyright (c) http://www.morphes.ru
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Default value provider which gets value from global configuration
 * @author Morphes Team
 *
 */
class Morphes_Core_Model_Config_Default {
	public function getDefaultValue($model, $attributeCode, $path) {
		return Mage::getStoreConfig($path);
	}
	public function getUseDefaultLabel() {
		return Mage::helper('morphes_core')->__('Use System Configuration');
	}
}