<?php

/**
 * CamelThemes Featuredproduct 
 * 
 * @category     CamelThemes
 * @package      CamelThemes_Featuredproduct 
 * @Class        CamelThemes_Featuredproduct_Block_Featuredproduct 
 */ 


class CamelThemes_Featuredproduct_Block_Featuredproduct extends Mage_Catalog_Block_Product_Abstract
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
    public function getFeaturedproduct()     
    { 
        if (!$this->hasData('featuredproduct')) {
            $this->setData('featuredproduct', Mage::registry('featuredproduct'));
        }
        return $this->getData('featuredproduct');
    }
	public function getProducts()
    {
    	$storeId    = Mage::app()->getStore()->getId();
		$products = Mage::getResourceModel('catalog/product_collection')
			->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
			->addMinimalPrice()
			->addUrlRewrite()
			->addTaxPercents()			
			->addStoreFilter()
			->setOrder($this->getConfig('sort'),$this->getConfig('direction'))
			->addAttributeToFilter("featured", 1);		
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);
        $products->setPageSize($this->getConfig('qty'))->setCurPage(1);
        $this->setProductCollection($products);
    }
	public function getConfig($att) 
	{
		$config = Mage::getStoreConfig('featuredproduct');
		if (isset($config['featuredproduct_config']) ) {
			$value = $config['featuredproduct_config'][$att];
			return $value;
		} else {
			throw new Exception($att.' value not set');
		}
	}
}