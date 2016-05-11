<?php
/**
* CamelThemes Layered Navigation 
* 
* @category     CamelThemes
* @package      CamelThemes_Layerednav  
* @Class        CamelThemes_Layerednav_Block_Rewrite_RewriteCatalogCategoryView
*/ 

class CamelThemes_Layerednav_Block_Rewrite_RewriteCatalogCategoryView extends Mage_Catalog_Block_Category_View
{ 
    public function getProductListHtml()
    {
        $html = parent::getProductListHtml();
        if ($this->getCurrentCategory()->getIsAnchor()){
            $html = Mage::helper('layerednav')->wrapProducts($html);
        }
        return $html;
    }   
} 