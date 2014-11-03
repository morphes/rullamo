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

class IntellectMoney_Im_Block_Redirect extends Mage_Core_Block_Abstract
{
    protected function _toHtml()
    {
        $im = Mage::getModel('im/checkout');

        $form = new Varien_Data_Form();
        $form->setAction($im->getIntellectMoneyUrl())
            ->setId('pay')
            ->setName('pay')
            ->setMethod('POST')
            ->setUseContainer(true);
        $im->getIntellectMoneyCheckoutFormFields();
        foreach ($im->getIntellectMoneyCheckoutFormFields() as $field=>$value) {
           $form->addField($field, 'hidden', array('name'=>$field, 'value'=>$value));
        }

        $html = '<html><body>';
        $html.= $this->__('You will be redirected to IntellectMoney in a few seconds.');
        $html.= '<br>';
        $html.= $form->toHtml();
        $html.= '<br>';
        $html.= '<script type="text/javascript">document.getElementById("pay").submit();</script>';
        $html.= '</body></html>';
        

        return $html;
    }
}
