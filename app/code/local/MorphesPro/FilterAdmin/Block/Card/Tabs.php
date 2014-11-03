<?php
/**
 * @category    Morphes
 * @package     MorphesPro_FilterAdmin
 * @copyright   Copyright (c) http://www.morphes.ru
 * @license     http://www.morphes.ru/license  Proprietary License
 */

/**
 * Enter description here ...
 * @author Morphes Team
 *
 */
class MorphesPro_FilterAdmin_Block_Card_Tabs extends Morphes_Admin_Block_Crud_Card_Tabs {
	public function getActiveTabName() {
		if (($tabName = Mage::app()->getRequest()->getParam('tab')) 
			&& ($tabBlock = $this->getChild($tabName))
			&& !$tabBlock->isHidden()) 
		{
			return $tabName;
		}
		else {
			return 'general';
		}
	}
	public function getActiveTabBlock() {
		return $this->getChild($this->getActiveTabName());
	}
	protected function _beforeToHtml() {
		foreach ($this->getSortedChildren() as $tabName) {
			$tabBlock = $this->getChild($tabName);
			if ($tabName == $this->getActiveTabName()) {
				$this->addTab($tabName, $tabBlock);
			}
			else {
				$this->addTab($tabName, array(
					'id' => $tabBlock->getNameInLayout(),
					'label' => $tabBlock->getTabLabel(),
					'title' => $tabBlock->getTabTitle(),
					'class' => 'ajax',
					'url' => $tabBlock->getAjaxUrl(),
					'is_hidden' => $tabBlock->isHidden(),
				));
			}
		}
		$this->setActiveTab($this->getActiveTabName());
		return parent::_beforeToHtml();
	}
}