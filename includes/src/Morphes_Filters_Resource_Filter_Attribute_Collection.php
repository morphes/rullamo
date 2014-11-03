<?php
/**
 * @category    Morphes
 * @package     Morphes_Filters
 * @copyright   Copyright (c) http://www.morphes.ru
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Attribute definition collection for filters
 * @author Morphes Team
 *
 */
class Morphes_Filters_Resource_Filter_Attribute_Collection extends Morphes_Core_Resource_Attribute_Collection {
	public function __construct($resource=null) {
		$this->setEntityType(Morphes_Filters_Model_Filter::ENTITY);
		parent::__construct($resource);
	}
}