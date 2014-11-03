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
 * PROPERTY METHODS
 * 
 * @method string getEntityName()
 * @method bool hasEntityName(()
 * @method Morphes_Db_Model_Replication_Target unsEntityName(()
 * @method Morphes_Db_Model_Replication_Target setEntityName(()
 * 
 * @method bool getIsKeyFilterApplied()
 * @method bool hasIsKeyFilterApplied(()
 * @method Morphes_Db_Model_Replication_Target unsIsKeyFilterApplied(()
 * @method Morphes_Db_Model_Replication_Target setIsKeyFilterApplied(()
 * 
 * @method bool getReplicable()
 * @method bool hasReplicable()
 * @method Morphes_Db_Replication_Target unsReplicable()
 * @method Morphes_Db_Replication_Target setReplicable()
 * 
 * COLLECTION METHODS
 * 
 * @method array getSourceEntityNames()
 * @method string getSourceEntityName()
 * @method bool hasSourceEntityName()
 * @method Morphes_Db_Model_Replication_Target setSourceEntityName()
 * @method Morphes_Db_Model_Replication_Target setSourceEntityNames()
 * @method Morphes_Db_Model_Replication_Target unsSourceEntityName()
 * 
 * @method array getSavedKeys()
 * @method string getSavedKey()
 * @method bool hasSavedKey()
 * @method Morphes_Db_Model_Replication_Target setSavedKey()
 * @method Morphes_Db_Model_Replication_Target setSavedKeys()
 * @method Morphes_Db_Model_Replication_Target unsSavedKey()
 * 
 * @method array getDeletedKeys()
 * @method string getDeletedKey()
 * @method bool hasDeletedKey()
 * @method Morphes_Db_Model_Replication_Target setDeletedKey()
 * @method Morphes_Db_Model_Replication_Target setDeletedKeys()
 * @method Morphes_Db_Model_Replication_Target unsDeletedKey()
 * 
 * @method array getSelects()
 * @method Varien_Db_Select getSelect()
 * @method bool hasSelect()
 * @method Morphes_Db_Model_Replication_Target setSelect()
 * @method Morphes_Db_Model_Replication_Target setSelects()
 * @method Morphes_Db_Model_Replication_Target unsSelect()
 * 
 * 
 */
class Morphes_Db_Model_Replication_Target extends Morphes_Core_Model_Object {
	protected $_collections = array(
		'source_entity_names' => array(),
		'saved_keys' => array(),
		'deleted_keys' => array(),
		'selects' => array(),
	);
}