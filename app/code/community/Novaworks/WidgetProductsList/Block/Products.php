<?php
class Novaworks_WidgetProductsList_Block_Products
    extends Mage_Catalog_Block_Product_Abstract
{
    protected function _construct()
    {
        parent::_construct();
    }
    protected function _beforeToHtml()
    {
        $productMode = $this->_getData('is_mode');
        $products = null;
        switch ($productMode) {
            case 'latest' :
                $products = $this->getListLatestProducts();
                break;
            case 'bestseller' :
                $products = $this->getListBestSellerProducts();
                break;
            case 'mostviewed' :
                $products = $this->getListMostViewedProducts();
                break;
            case 'toprated' :
                $products = $this->getListTopratedProducts();
                break;
            case 'sale' :
                $products = $this->getListSaleProducts();
                break;
            case 'featured' :
                $attribute = Mage::getModel('eav/config')->getAttribute('catalog_product', 'featured');
                if(null===$attribute->getId()) {
                    $products = null;
                }else{
                    $products = $this->getListFeaturedProducts();
                }
                break;
            case 'new' :
                $products = $this->getListNewProducts();
                break;
        }
        $this->setProductCollection($products);

        return parent::_beforeToHtml();
    }

    public function getListLatestProducts($fieldorder = 'updated_at', $order = 'desc')
    {
        $storeId    = Mage::app()->getStore()->getId();
        $cateids = $this->getCategoryId();
        if($cateids) {
            $catIds = explode(',', $cateids);
            $arr_productids = $this->getProductIdsByCategories($catIds);
            $products = Mage::getResourceModel('catalog/product_collection')
                ->addAttributeToSelect('*')
                ->addMinimalPrice()
                ->addUrlRewrite()
                ->addTaxPercents()
                ->addStoreFilter()
                ->addIdFilter($arr_productids)
                ->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
                ->addFieldToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
                ->setOrder ($fieldorder,$order);
        } else {
            $products = Mage::getResourceModel('catalog/product_collection')
                ->addAttributeToSelect('*')
                ->addMinimalPrice()
                ->addFinalPrice()
                ->addStoreFilter()
                ->addUrlRewrite()
                ->addTaxPercents()
                ->setOrder ($fieldorder,$order);
        }
        $products->setPageSize($this->getProductCount())->setCurPage(1);
        return $products;
    }

    public function getListBestSellerProducts()
    {
        $cateids = $this->getCategoryId();
        $qty = $this->getProductCount()? $this->getProductCount() : 10;
        if($cateids) {
            $catIds = explode(',', $cateids);
            $ctf = array();
            foreach ($catIds as $k => $cat) {
                $ctf[]['finset'] = $cat;
            }
            $products = Mage::getModel('catalog/product')->getCollection();
            $products->addAttributeToSelect('*');
            $products->joinField('category_id', 'catalog/category_product', 'category_id', 'product_id = entity_id', null, 'left')
                     ->addAttributeToFilter('category_id',array($ctf));
        } else {
            $products = Mage::getModel('catalog/product')->getCollection();
            $products->addAttributeToSelect('*');
        }
        $orderItems = $this->getTableName('sales/order_item');
        $orderMain = $this->getTableName('sales/order');
        $products->getSelect()
            ->join(array('items'=>$orderItems), "items.product_id = e.entity_id", array('count'=>'SUM(items.qty_ordered)'))
            ->join(array('trus'=>$orderMain),"items.order_id = trus.entity_id",array())
            ->where('trus.status = ?', 'complete')
            ->group('e.entity_id')
            ->order('count DESC');
        $products->setPageSize($qty)->setCurPage(1);
        return $products;
    }

    public function getListMostViewedProducts()
    {
        $storeId    = Mage::app()->getStore()->getId();
        $cateids = $this->getCategoryId();
        if($cateids) {
            $catIds = explode(',', $cateids);
            $arr_productids = $this->getProductIdsByCategories($catIds);
            $products = Mage::getResourceModel('reports/product_collection')
                ->addAttributeToSelect('*')
                ->addMinimalPrice()
                ->addUrlRewrite()
                ->addTaxPercents()
                ->addAttributeToSelect(array('name', 'price', 'small_image')) //edit to suit tastes
                ->setStoreId($storeId)
                ->addStoreFilter($storeId)
                ->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
                ->addFieldToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
                ->addViewsCount()
                ->addIdFilter($arr_productids);
        } else {
            $products = Mage::getResourceModel('reports/product_collection')
                ->addAttributeToSelect('*')
                ->addMinimalPrice()
                ->addUrlRewrite()
                ->addTaxPercents()
                ->addAttributeToSelect(array('name', 'price', 'small_image')) //edit to suit tastes
                ->setStoreId($storeId)
                ->addStoreFilter($storeId)
                ->addViewsCount();
        }

        $products->setPageSize($this->getProductCount())->setCurPage(1);
        return $products;
    }

    public function getListFeaturedProducts()
    {
        $storeId    = Mage::app()->getStore()->getId();
        $cateids = $this->getCategoryId();
        if($cateids) {
            $catIds = explode(',', $cateids);
            $arr_productids = $this->getProductIdsByCategories($catIds);
            $products = Mage::getResourceModel('catalog/product_collection')
                ->addAttributeToSelect('*')
                ->addMinimalPrice()
                ->addUrlRewrite()
                ->addTaxPercents()
                ->addStoreFilter()
                ->addIdFilter($arr_productids)
                ->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
                ->addFieldToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
                ->addAttributeToFilter("featured", 1);
        } else {
            $products = Mage::getResourceModel('catalog/product_collection')
                ->addAttributeToSelect('*')
                ->addMinimalPrice()
                ->addUrlRewrite()
                ->addTaxPercents()
                ->addStoreFilter()
                ->addAttributeToFilter("featured", 1);
        }

        $products->setPageSize($this->getProductCount())->setCurPage(1);
        return $products;
    }
    public function getListTopratedProducts()
    {
        $storeId    = Mage::app()->getStore()->getId();
        $cateids = $this->getCategoryId();
        if($cateids) {
            $catIds = explode(',', $cateids);
            $arr_productids = $this->getProductIdsByCategories($catIds);
            $products = Mage::getResourceModel('reports/product_collection')
	        	->addAttributeToSelect('*')
	        	->addIdFilter($arr_productids)
                ->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
                ->addFieldToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
	        	->joinField('rating_summary', 'review/review_aggregate', 'rating_summary', 'entity_pk_value=entity_id',  array('entity_type' => 1, 'store_id' => Mage::app()->getStore()->getId()), 'left') 
	        	->setOrder('rating_summary', 'desc');          
        } else {
            $products = Mage::getResourceModel('reports/product_collection')
	        	->addAttributeToSelect('*')
                ->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
                ->addFieldToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
	        	->joinField('rating_summary', 'review/review_aggregate', 'rating_summary', 'entity_pk_value=entity_id',  array('entity_type' => 1, 'store_id' => Mage::app()->getStore()->getId()), 'left') 
	        	->setOrder('rating_summary', 'desc');  
        }

        $products->setPageSize($this->getProductCount())->setCurPage(1);
        return $products;
    }
    public function getListSaleProducts()
    {
        $todayDate  = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
        $storeId    = Mage::app()->getStore()->getId();
        $cateids = $this->getCategoryId();
        if($cateids) {
            $catIds = explode(',', $cateids);
            $arr_productids = $this->getProductIdsByCategories($catIds);
            $products = Mage::getResourceModel('catalog/product_collection')
                ->addAttributeToSelect('*')
                ->addMinimalPrice()
                ->addUrlRewrite()
                ->addTaxPercents()
                ->addStoreFilter()
                ->addIdFilter($arr_productids)
                ->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
                ->addFieldToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
                ->addAttributeToFilter('special_price', array('notnull' => 1)) // only products where special_price is not empty
                ->setOrder('special_price', 'ASC'); // sort products by attribute specia
        } else {
            $products = Mage::getResourceModel('catalog/product_collection')
                ->addAttributeToSelect('*')
                ->addMinimalPrice()
                ->addUrlRewrite()
                ->addTaxPercents()
                ->addStoreFilter()
                ->addAttributeToFilter('special_price', array('notnull' => 1)) // only products where special_price is not empty
                ->setOrder('special_price', 'ASC'); // sort products by attribute specia
    
        }
        $products->setPageSize($this->getProductCount())->setCurPage(1);
        return $products;
    }
    public function getListNewProducts()
    {
        $todayDate  = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
        $storeId    = Mage::app()->getStore()->getId();
        $cateids = $this->getCategoryId();
        if($cateids) {
            $catIds = explode(',', $cateids);
            $arr_productids = $this->getProductIdsByCategories($catIds);
            $products = Mage::getResourceModel('catalog/product_collection')
                ->addAttributeToSelect('*')
                ->addMinimalPrice()
                ->addUrlRewrite()
                ->addTaxPercents()
                ->addStoreFilter()
                ->addIdFilter($arr_productids)
                ->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
                ->addFieldToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
                ->addAttributeToFilter('news_from_date', array('date'=>true, 'to'=> $todayDate))
                ->addAttributeToFilter(array(array('attribute'=>'news_to_date', 'date'=>true, 'from'=>$todayDate), array('attribute'=>'news_to_date', 'is' => new Zend_Db_Expr('null'))),'','left')
                ->addAttributeToSort('news_from_date','desc');
        } else {
            $products = Mage::getResourceModel('catalog/product_collection')
                ->addAttributeToSelect('*')
                ->addMinimalPrice()
                ->addUrlRewrite()
                ->addTaxPercents()
                ->addStoreFilter()
                ->addAttributeToFilter('news_from_date', array('date'=>true, 'to'=> $todayDate))
                ->addAttributeToFilter(array(array('attribute'=>'news_to_date', 'date'=>true, 'from'=>$todayDate), array('attribute'=>'news_to_date', 'is' => new Zend_Db_Expr('null'))),'','left')
                ->addAttributeToSort('news_from_date','desc');
        }
        $products->setPageSize($this->getProductCount())->setCurPage(1);
        return $products;
    }
	public function getProductsFilterList($catid) {
		$products = Mage::getModel('catalog/product')
                ->getCollection()
                ->joinField('category_id', 'catalog/category_product', 'category_id', 'product_id = entity_id', null, 'left')
                ->addAttributeToFilter('category_id', array('in' => array('finset' => $catid)))
                ->addAttributeToSelect('*')
                 ->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
                ->addFieldToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED);
        $products->setPageSize($this->getProductCount())->setCurPage(1);
    	return $products;
	}
    public function getCategoryName($id){
	    $catagory_model = Mage::getModel('catalog/category');
	    $categories = $catagory_model->load($id); // where $id will be the known category id
	    return $categories->getName();
	}
    public function getCacheKeyInfo()
    {
        if (NULL === $this->_cacheKeyArray)
        {
            $this->_cacheKeyArray = array(
                'MAGENTHEME_PRODUCTSSCROLLER',
                Mage::app()->getStore()->getCurrentCurrency()->getCode(),
                Mage::app()->getStore()->getId(),
                Mage::getDesign()->getPackageName(),
                Mage::getDesign()->getTheme('template'),
                Mage::getSingleton('customer/session')->getCustomerGroupId(),
                'template' => $this->getTemplate(),
                $this->getBlockName(),
                $this->getCategoryId(),
                $this->getIsMode(),
                $this->getProductCount(),
                $this->getShowItems(),
                $this->getIsResponsive(),
                $this->getTimeout(),
                $this->getInitDelay(),
                (int)Mage::app()->getStore()->isCurrentlySecure()
            );
        }
        return $this->_cacheKeyArray;
    }

    public function getProductsByCategory($catId) {
        $storeId    = Mage::app()->getStore()->getId();
        $collection = Mage::getResourceModel('catalog/product_collection')
            ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
            ->setStoreId($storeId)
            ->addStoreFilter($storeId)
            ->addMinimalPrice()
            ->addTaxPercents()
        ;
        if($catId) {
            $category = Mage::getModel('catalog/category')->load($catId);
            if($category->getId()) {
                $collection->addCategoryFilter($category);
            }
        }

        return $collection;
    }

    public function getProductIdsByCategories($catIds) {
        $productIds = array();
        if(count($catIds)) {
            foreach($catIds as $catId) {
                $productIdArr = $this->getProductsByCategory($catId);
                if(count($productIdArr)) {
                    foreach($productIdArr as $product) {
                        $productIds[] = $product->getId();
                    }
                }
            }
        }
        $productIds = array_unique($productIds);
        return $productIds;
    }
    public function getFrontendHash()
    {
        return md5( implode("+", $this->getCacheKeyInfo()) );
    }

    public function getTableName($modelEntity)
    {
        try {
            $table = Mage::getSingleton('core/resource')->getTableName($modelEntity);
        } catch (Exception $e){
            Mage::throwException($e->getMessage());
        }
        return $table;
    }
}