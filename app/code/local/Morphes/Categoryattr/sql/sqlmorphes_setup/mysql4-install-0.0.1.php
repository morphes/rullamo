<?php 
$installer = $this;
$installer->startSetup();
 ///asdfasdf
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');



// $setup->addAttribute('catalog_category', 'catalog_attr', array(
//     'type' => 'varchar',      
//     'group'     => 'Категория-фильтр',
//     'backend' => '',
//     'frontend' => '',
//     'label' => 'Атрибут',
//     'input' => 'select',    
//     'class' => 'brend',
//     'source' => 'categoryattr/source_Sourceattr',  
//     'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
//     'visible' => true,
//     'required' => false,
//     'user_defined' => false,
//     'default' => '0',       
//     'searchable' => false,
//     'filterable' => false,
//     'comparable' => false,
//     'visible_on_front' => false,
//     'unique' => false,
//     'position' => 1
// ));
$setup->addAttribute('catalog_category', 'sales_attr', array(
    'group'         => 'General',
    'input'         => 'select',
    'type'          => 'varchar',
    'label'         => 'Акция на странице информации по заказу?',
    'backend'       => '',
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => true,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'option' => array(
            'value' => array(
                0 => array('Да'),
                1 => array('Нет'),                
            ),
        ),
    'default' => array(0)
));



$installer->endSetup();