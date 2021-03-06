<?php
/**
 * Novaworks Blog Extension
 * @version   1.0 12.09.2013
 * @author    Novaworks http://www.novaworks.net <info@novaworks.net>
 * @copyright Copyright (C) 2010 - 2013 Novaworks
 */

class Novaworks_Blog_Block_Blog extends Novaworks_Blog_Block_Abstract
{

    public function getPosts()
    {

        $collection = parent::_prepareCollection();

        $tag = $this->getRequest()->getParam('tag');
        if ($tag) {
            $collection->addTagFilter(urldecode($tag));
        }

        parent::_processCollection($collection);

        return $collection;
    }

    protected function _prepareLayout()
    {
        if ($this->isBlogPage() && ($breadcrumbs = $this->getCrumbs())) {

            parent::_prepareMetaData(self::$_helper);

            $tag = $this->getRequest()->getParam('tag', false);

            if ($tag) {
                $tag = urldecode($tag);
                $breadcrumbs->addCrumb('blog', array('label' => self::$_helper->getTitle(), 'title' => $this->__('Return to ' . self::$_helper->getTitle()), 'link' => $this->getBlogUrl()));
                $breadcrumbs->addCrumb('blog_tag', array('label' => $this->__('Tagged with "%s"', self::$_helper->convertSlashes($tag)), 'title' => $this->__('Tagged with "%s"', $tag)));
            } else {
                $breadcrumbs->addCrumb('blog', array('label' => self::$_helper->getTitle()));
            }
        }
    }

}
