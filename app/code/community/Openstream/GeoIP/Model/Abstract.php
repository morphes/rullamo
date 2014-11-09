<?php
require_once('lib/Geo/geo.php');
class Openstream_GeoIP_Model_Abstract
{
    protected $local_dir, $local_file, $local_archive, $remote_archive;

    public function __construct()
    {
        $this->local_dir = 'geoip';
        $this->local_file = Mage::getBaseDir('var') . '/' . $this->local_dir . '/GeoIP.dat';
        $this->local_archive = Mage::getBaseDir('var') . '/' . $this->local_dir . '/GeoIP.dat.gz';
        $this->remote_archive = 'http://www.maxmind.com/download/geoip/database/GeoLiteCountry/GeoIP.dat.gz';
	$o = array(); 
    	$o['charset'] = 'utf-8'; 
	$geo = new Geo($o);   
        $data = $geo->get_value(); 
        
        $ip = $geo->get_ip();
        $model_geoip = Mage::getModel('geoip/geoip')->getCollection();

        $city = $geo->get_value('city', true);

        $cities = Mage::getModel('directory/region_api')->items('RU');
        $only_cities = array();  
        foreach($cities as $city) {
            $name = explode(" (", $city['name']);
            $only_cities[$city['region_id']] = $name[0];
        }
        if(!isset($data['city'])) {
            $data['city'] = '';
        }
        if(in_array($data['city'], $only_cities)) {
            $data['code'] = array_search($data['city'], $only_cities);
        }
        $ip = $this->getClientIP();
        $geoip_model = Mage::getModel('geoip/geoip')->getCollection()->addFieldToFilter('ip', $ip)->getFirstItem();
        $find_ip = $geoip_model->getData();
        $found = 'no';
        if(!empty($find_ip)) {
            $model = Mage::getModel('geoip/geoip');  
            $founded_id = $find_ip['geoip_id'];
            $model->load($founded_id);
            $data['code'] = $model->getCityCode();
            if(isset($only_cities[$data['code']])) {
                $data['city'] = $only_cities[$data['code']];
            }
            $found = 'yes';
        }
    	$this->data = $data;

    	$session = Mage::getSingleton('core/session'); 
    	$geo_info = $session->getGeoIp();
    	if(isset($geo_info['is_changed'])) {
            if($found=='yes') {
                $session->setGeoIp($this->data);
            }
        } else {
    	    $session->setGeoIp($this->data);
        }

    }

    public function getClientIP() {
         $o = array();
         $geo = new Geo($o);
         return $geo->get_ip();
    }

    public function getArchivePath()
    {
        return $this->local_archive;
    }

    public function checkFilePermissions()
    {
        $helper = Mage::helper('geoip');
        $dir = Mage::getBaseDir('var') . '/' . $this->local_dir;
        if (file_exists($dir)) {
            if (!is_dir($dir)) {
                return sprintf($helper->__('%s exists but it is file, not dir.'), 'var/' . $this->local_dir);
            } elseif ((!file_exists($this->local_file) || !file_exists($this->local_archive)) && !is_writable($dir)) {
                return sprintf($helper->__('%s exists but files are not and directory is not writable.'), 'var/' . $this->local_dir);
            } elseif (file_exists($this->local_file) && !is_writable($this->local_file)) {
                return sprintf($helper->__('%s is not writable.'), 'var/' . $this->local_dir . '/GeoIP.dat');
            } elseif (file_exists($this->local_archive) && !is_writable($this->local_archive)) {
                return sprintf($helper->__('%s is not writable.'), 'var/' . $this->local_dir . '/GeoIP.dat.gz');
            }
        } elseif (!@mkdir($dir)) {
            return  sprintf($helper->__('Can\'t create %s directory.'), 'var/' . $this->local_dir);
        }
        return '';
    }

    public function update(){
        $helper = Mage::helper('geoip');

        $ret = array('status' => 'error');

        if ($permissions_error = $this->checkFilePermissions()) {
            $ret['message'] = $permissions_error;
        } else {
            $remote_file_size = $helper->getSize($this->remote_archive);
            if ($remote_file_size < 100000) {
                $ret['message'] = $helper->__('You are banned from downloading the file. Please try again in several hours.');
            } else {
                $_session = Mage::getSingleton('core/session');
                $_session->setData('_geoip_file_size', $remote_file_size);

                $src = fopen($this->remote_archive, 'r');
                $target = fopen($this->local_archive, 'w');
                stream_copy_to_stream($src, $target);
                fclose($target);
                if (filesize($this->local_archive)) {
                    if ($helper->unGZip($this->local_archive, $this->local_file)) {
                        $ret['status'] = 'success';
                        $format = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM);
                        $ret['date'] = Mage::app()->getLocale()->date(filemtime($this->local_file))->toString($format);
                    } else {
                        $ret['message'] = $helper->__('UnGzipping failed');
                    }
                } else {
                    $ret['message'] = $helper->__('Download failed.');
                }
            }
        }
        echo json_encode($ret);
    }
}
