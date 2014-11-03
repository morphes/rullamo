<?php
/**
 * @author Adjustware
 */ 
class Medma_Background_Block_Adminhtml_Background_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('backgroundGrid');
		
		
	}

	protected function _prepareCollection()
	{
		$collection = Mage::getModel('background/background')->getCollection();
		

// Most Important Line for printing the above query
			//$collection->printlogquery(true);
 		      // exit(0);
//Most Important Line for printing the above query
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	protected function _prepareColumns()
	{
		$hlp =  Mage::helper('background');
		
		$this->addColumn('status', array(
			'header'    => $hlp->__('Status'),
			'index'     => 'status',
			'type'      => 'options',
			'width'     => '25px',
			'filter'    => false,
			'options'   => array(
				1 => 'Enabled',
				0 => 'Disabled',
			),
		));
		$this->addColumn('options', array(
			'header'    => $hlp->__('BG Option'),
			'index'     => 'options',
			'type'      => 'options',
			'width'     => '25px',
			'filter'    => false,
			'options'   => array(
				1 => 'Color',
				0 => 'Image',
			),
		));
		return parent::_prepareColumns();
	}

	public function getRowUrl($row)
	{
		return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}

	public function getMainButtonsHtml()
	{
		return '';
	}
}
