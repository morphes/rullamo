<?php
/**
 * @category    Morphes
 * @package     Morphes_Filters
 * @copyright   Copyright (c) http://www.morphes.ru
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Base class for sources of filter templates
 * @author Morphes Team
 *
 */
class Morphes_Filters_Model_Source_Display extends Morphes_Core_Model_Source_Abstract {
	protected $_filterType = ''; // this should be filled in derived classes
	
	protected function _getAllOptions() {
		/* @var $core Morphes_Core_Helper_Data */ $core = Mage::helper(strtolower('Morphes_Core'));
		$result = array();
		
		foreach ($core->getSortedXmlChildren(Mage::getConfig()->getNode('morphes_filters/display'), $this->_filterType) as $key => $options) {
			$module = isset($options['module']) ? ((string)$options['module']) : 'morphespro_filteradmin'; 
    		$result[] = array('label' => Mage::helper($module)->__((string)$options->title), 'value' =>  $key);
		}
		return $result;
	}
	public function getDbType() {
		return 'varchar(255)';
	}
	public function getDbDefaultValue() {
		return '';
	}
}