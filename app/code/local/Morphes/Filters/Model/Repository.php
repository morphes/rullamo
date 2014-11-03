<?php
/**
 * @category    Morphes
 * @package     Morphes_Filters
 * @copyright   Copyright (c) http://www.morphes.ru
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/**
 * @author Morphes Team
 *
 */
class Morphes_Filters_Model_Repository {
    protected $_filters = array();

    public function getFilter($name) {
        return isset($this->_filters[$name]) ? $this->_filters[$name] : null;
    }

    public function setFilter($name, $filter) {
        $this->_filters[$name] = $filter;
    }
}