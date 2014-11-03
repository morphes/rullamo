<?php
  /**
     * Mtoo Co.
     *
     * NOTICE OF LICENSE
     *
     * This source file is subject to the EULA
     * that is bundled with this package in the file LICENSE.txt.
     * It is also available through the world-wide-web at this URL:
     * http://www.magento800.com/LICENSE.txt
     *
     * @category   Mtoo
     * @package    Mtoo_Navitation
     * @copyright  Copyright (c) 2010 Mtoo Co. (http://www.magento800.com)
     * @license    http://www.magento800.com/LICENSE.txt
     */
/**
 * This is template for AW_Vidtest_Block_Product_View
 */
class Mtoo_Navigation_Block_Navigation extends Mage_Core_Block_Template
{

	  protected function _construct()
    {
        $this->addData(array(
            'cache_lifetime'    => false,
            'cache_tags'        => array(Mage_Catalog_Model_Category::CACHE_TAG, Mage_Core_Model_Store_Group::CACHE_TAG),
        ));
    }

    /**
     * Retrieve Key for caching block content
     *
     * @return string
     */
    public function getCacheKey()
    {
        return 'CATALOG_NAVIGATION_' . Mage::app()->getStore()->getId()
            . '_' . Mage::getDesign()->getPackageName()
            . '_' . Mage::getDesign()->getTheme('template')
            . '_' . Mage::getSingleton('customer/session')->getCustomerGroupId();
    }

	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getNavigation()     
     { 
        if (!$this->hasData('navigation')) {
            $this->setData('navigation', Mage::registry('navigation'));
        }
        return $this->getData('navigation');
        
    }
	/**
     * Get catagories of current store
     *
     * @return Varien_Data_Tree_Node_Collection
     */
    public function getStoreCategories()
    {
        $helper = Mage::helper('catalog/category');
        return $helper->getStoreCategories();
    }
    
 /**
     * Get url for category data
     *
     * @param Mage_Catalog_Model_Category $category
     * @return string
     */
    public function getCategoryUrl($category)
    {
        if ($category instanceof Mage_Catalog_Model_Category) {
            $url = $category->getUrl();
        } else {
            $url = $this->_getCategoryInstance()
                ->setData($category->getData())
                ->getUrl();
        }

        return $url;
    }
    
 	protected function _getCategoryInstance()
    {
        if (is_null($this->_categoryInstance)) {
            $this->_categoryInstance = Mage::getModel('catalog/category');
        }
        return $this->_categoryInstance;
    }
    public function drawItem($category, $level=0, $last=false)
    {
    	$html = '';
        if (!$category->getIsActive()) {
            return $html;
        }
        $hasChildren = $this->hasChildren($category);
        $children = $this->getChildrens($category);
        $html.='<div class="litem">';
        if ($hasChildren)
        	$html.='<span><a href="'.$this->getCategoryUrl($category).'">'.$this->htmlEscape($category->getName()).'</a>
        <!--<em class="dow_ico"></em>-->
        </span>';
        else
        	$html.='<span><a href="'.$this->getCategoryUrl($category).'">'.$this->htmlEscape($category->getName()).'</a></span>';
        $htmlChildren='';
        if ($hasChildren){
        	$htmlChildren.='<div style="width: '.$this->getWidth($category).'px;" class="subitem">';
        	$count=0;
        	$ad=0;
        	$htmlChildren.='<div class="fleft">';
        	 foreach ($children as $child) {
        	 	 if ($child->getIsActive()) {
        	 	 	$count++;
        	 	 	$htmlChildren.='<div class="subitem_list fleft">';
        	 	 	$htmlChildren.='<ul>';
        	 	 	$htmlChildren.='<li class="sub_category_self addborder"><a href="'.$this->getCategoryUrl($child).'">'.$this->htmlEscape($child->getName()).'</a>';
        	 	 	if ($this->hasChildren($child))
        	 	 	{
        	 	 		$childChildrens=$this->getChildrens($child);
        	 	 		$htmlChildren.='<ul>';
        	 	 		 foreach ($childChildrens as $Children) {
        	 	 		 	if ($Children->getIsActive())
        	 	 		 	{
        	 	 				$htmlChildren.='<li><a href="'.$this->getCategoryUrl($Children).'" class="red">'.$this->htmlEscape($Children->getName()).'</a></li>';
        	 	 		 	}
        	 	 		 }
        	 	 		$htmlChildren.='</ul>';        	 	 	
        	 	 	}
        	 	 	$htmlChildren.='</li>';
        	 	 	$htmlChildren.='</ul>';
        	 	 	$htmlChildren.='</div>';
        	 	 	if ($count%3==0)
        	 	 	{
        	 	 		$htmlChildren.='<div class="clear"></div>';
        	 	 	}
        	 	 }
        	 }
        	 $htmlChildren.='</div>';
        	 if ($category->getCategoryAd()){
        	 	$htmlChildren.='<div class="fright special">'.$category->getCategoryAd().'</div>';
        	 }
             $htmlChildren.='<div class="clear"></div>';
        	 $htmlChildren.='</div>';
        }
      
        
        if (!empty($htmlChildren)) {
        	$html.=$htmlChildren;
        }
        $html.='</div>';
        return $html;
    }
    public function hasChildren ($category)
    {
     	if (Mage::helper('catalog/category_flat')->isEnabled()) {
            $children = $category->getChildrenNodes();
            $childrenCount = count($children);
        } else {
            $children = $category->getChildren();
            $childrenCount = $children->count();
        }
        return $children && $childrenCount;
    }
    public function getChildrens($category)
    {
    	if (Mage::helper('catalog/category_flat')->isEnabled()) {
            $children = $category->getChildrenNodes();
        } else {
            $children = $category->getChildren();
        }
        return $children;
    }
	
	
	/**
     * Retrieve div.subitem width
     * @return int
     */


    public function getWidth($category)
    {
    	$width=220;
    	if (Mage::helper('catalog/category_flat')->isEnabled()) {
            $children = $category->getChildrenNodes();
            $childrenCount = count($children);
        } else {
            $children = $category->getChildren();
            $childrenCount = $children->count();
        }
		
        if($childrenCount>=3)
        {
			if ($category->getCategoryAd()){
	        	$width=$width*4;
			}else{
				$width=$width*3;
			}

        	
        }else
        {
			if ($category->getCategoryAd()){
	        	$width=$width*($childrenCount+1);
			}else{
				$width=$width*$childrenCount;
			}

        }
        return $width;
        	
    }
}