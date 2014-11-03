<?php
class Freaks_Quotes_Block_Adminhtml_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm() {
            $countriesArray = Mage::getResourceModel('directory/country_collection')->load()->toOptionArray(false);
            $this->_countries = array();
            $this->_countries['RU'] = 'Субъекты РФ';
            $countryRegions = array();
            $regionsCollection = Mage::getResourceModel('directory/region_collection')->load();
            foreach ($regionsCollection as $region) {
                $countryRegions[$region->getCountryId()][$region->getId()] = $region->getDefaultName();
            }
            $options = array();
            foreach ($countryRegions as $countryId=>$regions) {
                if($countryId=='RU') {
                    $regionOptions = array();
                    foreach ($regions as $regionId=>$regionName) {
                        $name = explode(" (", $regionName);
                        if(isset($name[1])) {                            
                            	$regionOptions[] = array('label'=>$regionName, 'value'=>$regionId);
                        } else {
                            $city = explode(" ", $name[0]);
                            if(!isset($city[1]) or ($name[0]='Нижний Новгород')) {
                                $regionOptions[] = array('label'=>$regionName, 'value'=>$regionId);
                            }
                            
                        }
                    }
                    
                    $options[] = array('label'=>$this->_countries['RU'], 'value'=>$regionOptions);
                }
            }

        $quote = Mage::registry('current_quote');
        $form = new Varien_Data_Form();
        $fieldset = $form->addFieldset('edit_quote', array(
            'legend' => Mage::helper('freaks_quotes')->__('Подробности')
        ));

        if ($quote->getId()) {
            $fieldset->addField('id', 'hidden', array(
                'name'      => 'id',
                'required'  => true
            ));
        }


     /*   $fieldset->addField('name', 'text', array(
            'name'      => 'name',
            'title'     => Mage::helper('freaks_quotes')->__('Наименование'),
            'label'     => Mage::helper('freaks_quotes')->__('Наименование'),
            'maxlength' => '250',
            'required'  => true,
        ));*/
$fieldset->addField('name', 'select', array(
            'label'     => Mage::helper('freaks_quotes')->__('Населенный пункт'),
            'name'      => 'name',
            'values'    => 

                $options[0]['value']
                      
                        
            ,
            'class'=>'validate-digits',
            'value' => 1,
        ));
        
        $fieldset->addField('dscr', 'text', array(
            'name'      => 'dscr',
            'title'     => Mage::helper('freaks_quotes')->__('Километров (в пределах Моск. области за МКАД\'ом)'),
            'label'     => Mage::helper('freaks_quotes')->__('Километров (в пределах Моск. области за МКАД\'ом)'),
            'required'  => false,
        ));
$fieldset->addField('km1', 'text', array(
            'name'      => 'km1',
            'title'     => Mage::helper('freaks_quotes')->__('До 1 кг.'),
            'label'     => Mage::helper('freaks_quotes')->__('До 1 кг.'),
            'maxlength' => '250',
            'required'  => false,
        ));
$fieldset->addField('km3', 'text', array(
            'name'      => 'km3',
            'title'     => Mage::helper('freaks_quotes')->__('До 3 кг.'),
            'label'     => Mage::helper('freaks_quotes')->__('До 3 кг.'),
            'maxlength' => '250',
            'required'  => false,
        ));
$fieldset->addField('km5', 'text', array(
            'name'      => 'km5',
            'title'     => Mage::helper('freaks_quotes')->__('До 5 кг.'),
            'label'     => Mage::helper('freaks_quotes')->__('До 5 кг.'),
            'maxlength' => '250',
            'required'  => false,
        ));
$fieldset->addField('km10', 'text', array(
            'name'      => 'km10',
            'title'     => Mage::helper('freaks_quotes')->__('До 10 кг.'),
            'label'     => Mage::helper('freaks_quotes')->__('До 10 кг.'),
            'maxlength' => '250',
            'required'  => false,
        ));
$fieldset->addField('km15', 'text', array(
            'name'      => 'km15',
            'title'     => Mage::helper('freaks_quotes')->__('До 15 кг.'),
            'label'     => Mage::helper('freaks_quotes')->__('До 15 кг.'),
            'maxlength' => '250',
            'required'  => false,
        ));
$fieldset->addField('km20', 'text', array(
            'name'      => 'km20',
            'title'     => Mage::helper('freaks_quotes')->__('До 20 кг.'),
            'label'     => Mage::helper('freaks_quotes')->__('До 20 кг.'),
            'maxlength' => '250',
            'required'  => false,
        ));
$fieldset->addField('km25', 'text', array(
            'name'      => 'km25',
            'title'     => Mage::helper('freaks_quotes')->__('До 25 кг.'),
            'label'     => Mage::helper('freaks_quotes')->__('До 25 кг.'),
            'maxlength' => '250',
            'required'  => false,
        ));
$fieldset->addField('addressess', 'textarea', array(
            'name'      => 'addressess',
            'title'     => Mage::helper('freaks_quotes')->__('Адреса самовывоза'),
            'label'     => Mage::helper('freaks_quotes')->__('Адреса самовывоза'),
            'maxlength' => '250',
            'required'  => false,
            'note' => 'Введите адреса по маске "адрес1;адрес2;..."'
        ));
$fieldset->addField('costs', 'textarea', array(
            'name'      => 'costs',
            'title'     => Mage::helper('freaks_quotes')->__('Стоимость самовывоза (до 1кг, до 3кг, до 5кг, до 10кг, до 15кг, до 20кг, до 25кг)'),
            'label'     => Mage::helper('freaks_quotes')->__('Стоимость самовывоза (до 1кг, до 3кг, до 5кг, до 10кг, до 15кг, до 20кг, до 25кг)'),
            'maxlength' => '250',
            'required'  => false,
            'note' => 'Введите адреса по маске "стоимость1;стоимость2;..."'
        ));

 
        $form->setMethod('post');
        $form->setUseContainer(true);
        $form->setId('edit_form');
        $form->setAction($this->getUrl('*/*/save'));
        $form->setValues($quote->getData());
        $this->setForm($form);
    }
}
