<?php


class Morphes_CheckoutComment_Block_Widget_Comment extends Morphes_CheckoutComment_Block_Widget_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('morphes/checkoutcomment/widget/comment.phtml');
    }

    public function isEnabled() {

        return $this->getConfig('enable_comment');
    }
}