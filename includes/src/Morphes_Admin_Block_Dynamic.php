<?php
/**
 * @category    Morphes
 * @package     Morphes_Admin
 * @copyright   Copyright (c) http://www.morphes.ru
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * FOR FUTURE USE: This is a wrapper block put around content which can be changed through AJAX calls
 * @author Morphes Team
 *
 */
class Morphes_Admin_Block_Dynamic extends Mage_Adminhtml_Block_Template {
	public function __construct()
	{
		parent::__construct();
		$this->setTemplate('morphes/admin/dynamic.phtml');
	}
}