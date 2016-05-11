<?php
/**
* CamelThemes Layered Navigation 
* 
* @category     CamelThemes
* @package      CamelThemes_Layerednav 
* @Class        CamelThemes_Layerednav_Block_Rewrite_RewriteCatalogsearchResult
*/ 
 
class CamelThemes_Layerednav_Block_Rewrite_RewriteCatalogsearchResult extends Mage_CatalogSearch_Block_Result
{   
    /**
     * Retrieve Search result list HTML output, wrapped with <div>
     * @return string
     */
    public function getProductListHtml()
    {
        $html = parent::getProductListHtml();
        $html = Mage::helper('layerednav')->wrapProducts($html);
        return $html;
    }
	
	/**
     * Set Search Result collection
     *
     * @return Mage_CatalogSearch_Block_Result
     */
    public function setListCollection()
    {
        //benz001  un-comment these two
        $this->getListBlock()
           ->setCollection($this->_getProductCollection());
       return $this;
    }
    /**
     * Retrieve loaded category collection
     *
     * @return Mage_CatalogSearch_Model_Mysql4_Fulltext_Collection
     */
    protected function _getProductCollection()
    {
        if (is_null($this->_productCollection)) {
            //$this->_productCollection = $this->getListBlock()->getLoadedProductCollection();
            $this->_productCollection = Mage::getSingleton('catalogsearch/layer')->getProductCollection();
        }
        
        return $this->_productCollection;
    }
	
} 