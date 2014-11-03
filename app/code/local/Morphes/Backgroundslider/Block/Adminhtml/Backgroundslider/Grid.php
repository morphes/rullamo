<?php
/**
 * @author Adjustware
 */ 
class Morphes_Backgroundslider_Block_Adminhtml_Backgroundslider_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('backgroundGrid');
		
		
	}

	protected function _prepareCollection()
	{
		$collection = Mage::getModel('backgroundslider/backgroundslider')->getCollection();
		

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
			'header'    => $hlp->__('Статус'),
			'index'     => 'status',
			'type'      => 'options',
			'width'     => '25px',
			'filter'    => false,
			'options'   => array(
				1 => 'Включено',
				0 => 'Отключено',
			),
		));
		$this->addColumn('color_value', array(
			'header'    => $hlp->__('Ссылка'),
			'index'     => 'color_value',
			 'type'      => 'text',
			'width'     => '25px',
			'filter'    => false,
			'renderer' => 'backgroundslider/adminhtml_backgroundslider_renderer_link'			
			
		));
		$this->addColumn('options', array(
			'header'    => $hlp->__('Изображение'),
			'align' => 'left',
            'index' => 'options',
            'width'     => '97',
            'filter'    => false,
            'renderer' => 'backgroundslider/adminhtml_backgroundslider_renderer_image'
		));
		$this->addColumn('orders', array(
			'header'    => $hlp->__('Порядок вывода'),
			'index'     => 'orders',
			 'type'      => 'text',
			'width'     => '25px',
			'filter'    => false,
			// 'renderer' => 'backgroundslider/adminhtml_backgroundslider_renderer_link'						
		));
		$this->addColumn('type', array(
			'header'    => $hlp->__('Тип'),
			'index'     => 'type',
			'type'      => 'options',
			'width'     => '25px',
			'filter'    => false,
			'options'   => array(
				1 => 'Баннер',
				0 => 'Слайдер',
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
