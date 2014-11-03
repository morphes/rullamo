<?php
/**
 * Novaworks Blog Extension
 * @version   1.0 12.09.2013
 * @author    Novaworks http://www.novaworks.net <info@novaworks.net>
 * @copyright Copyright (C) 2010 - 2013 Novaworks
 */

class Novaworks_Blog_Block_Homeblog extends Novaworks_Blog_Block_Abstract
{
	
	/**
     * Initialize block's cache
     */
    protected function _construct()
    {
        parent::_construct();
			
		$this->addData(array(
            'cache_lifetime'    => 86400,
            'cache_tags'        => array(Novaworks_Blog_Model_Blog::CACHE_TAG),
			'cache_key'			=> Novaworks_Blog_Model_Blog::CACHE_TAG.'_homeblock'
        ));
		
		$this->setData('perPageOverride', 2);
    }
	
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

}
