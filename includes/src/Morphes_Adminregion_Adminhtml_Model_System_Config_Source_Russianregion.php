<?php /**
 * Russian regions
 *
 */
class Morphes_Adminregion_Adminhtml_Model_System_Config_Source_Russianregion
{
    protected $_countries;
    protected $_options;

    public function toOptionArray($isMultiselect=false)
    {
        if (!$this->_options) {
            $countriesArray = Mage::getResourceModel('directory/country_collection')->load()
                ->toOptionArray(false);
            $this->_countries = array();


                $this->_countries['RU'] = 'Субъекты РФ';


            $countryRegions = array();
            $regionsCollection = Mage::getResourceModel('directory/region_collection')->load();
            foreach ($regionsCollection as $region) {
                $countryRegions[$region->getCountryId()][$region->getId()] = $region->getDefaultName();
            }
           // uksort($countryRegions, array($this, 'sortRegionCountries'));
            
            $this->_options = array();
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
                    
                    $this->_options[] = array('label'=>$this->_countries['RU'], 'value'=>$regionOptions);
                }
            }
        }
        $options = $this->_options;
        if(!$isMultiselect){
            array_unshift($options, array('value'=>'', 'label'=>''));
        }

        return $options;
    }

}
