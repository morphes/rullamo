<?php
/**
 * @category    Clearandfizzy
 * @package     Clearandfizzy_EnhancedCMS
 * @copyright   Copyright (c) 2012 Clearandfizzy ltd. (http://www.clearandfizzy.com/)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *
 */
class Clearandfizzy_EnhancedCMS_Block_Adminhtml_Cms_Block extends Mage_Adminhtml_Block_Cms_Block
{


    public function __construct()
    {
        parent::__construct();

        $this->_addButton('importcsv', array(
        		'label'     => 'Import CSV',
        		'onclick'   => 'setLocation(\'' . $this->getImportUrl() .'\')',
        		'class'     => 'import',
        ));

    }


    private function getImportUrl() {
    	return $this->getUrl('*/cms_enhanced_block/uploadCsv');
    } // end

}
