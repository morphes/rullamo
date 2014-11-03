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
 * @method array getColumns()
 * @method string getColumn()
 * @method Morphes_Db_Model_Virtual_Result unsColumns()
 * @method Morphes_Db_Model_Virtual_Result unsColumn()
 * @method Morphes_Db_Model_Virtual_Result addColumn()
 * @method Morphes_Db_Model_Virtual_Result setColumns()
 * 
 */
class Morphes_Db_Model_Virtual_Result extends Morphes_Core_Model_Object {
	protected $_arrays = array(
		'columns' => array(),
	);
}