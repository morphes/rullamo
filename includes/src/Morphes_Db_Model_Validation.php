<?php
/**
 * @category    Morphes
 * @package     Morphes_Db
 * @copyright   Copyright (c) http://www.morphes.ru
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Enter description here ...
 * @author Morphes Team
 *
 * ARRAY METHODS
 * 
 * @method array getErrors()
 * @method string getError()
 * @method Morphes_Db_Model_Validation unsErrors()
 * @method Morphes_Db_Model_Validation unsError()
 * @method Morphes_Db_Model_Validation addError()
 * @method Morphes_Db_Model_Validation setErrors()
 * 
 */
class Morphes_Db_Model_Validation extends Morphes_Core_Model_Object {
	protected $_arrays = array(
		'errors' => array(),
	);
} 