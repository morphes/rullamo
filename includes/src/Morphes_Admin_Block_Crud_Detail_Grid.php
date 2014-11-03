<?php
/**
 * @category    Morphes
 * @package     Morphes_Admin
 * @copyright   Copyright (c) http://www.morphesdev.com
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Enter description here ...
 * @author Morphes Team
 *
 */
class Morphes_Admin_Block_Crud_Detail_Grid extends Morphes_Admin_Block_Crud_Grid {
    public function getRowUrl($row) {
	    return '';
    }
    public function getRowClass($row) {
    	return 'r-'.$row->getId();
    }
}