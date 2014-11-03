<?php
/*
 *
 * @category   Community
 * @package    IntellectMoney
 * @copyright  http://intellectmoney.ru
 * @license    Open Software License (OSL 3.0)
 *
 */

/*
 * IntellectMoney payment module
 *
 * @author     intellectmoney.ru
 *
 */

class IntellectMoney_Im_Block_Info extends Mage_Payment_Block_Info {
    
    protected function _construct() {
        parent::_construct();
        $this->setTemplate('im/info.phtml');
    }
}