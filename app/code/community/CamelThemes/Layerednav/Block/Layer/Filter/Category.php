<?php

/**
 * CamelThemes Layered Navigation
 * 
 * @category     CamelThemes
 * @package      CamelThemes_Layerednav
 * @Class        CamelThemes_Layerednav_Block_Layer_Filter_Category
 */
class CamelThemes_Layerednav_Block_Layer_Filter_Category extends Mage_Catalog_Block_Layer_Filter_Category {

    public function __construct() {  
        parent::__construct();
        //Load Custom PHTML of category 
        $this->setTemplate('layerednav/filter_category.phtml');
        //Set Filter Model Name
        $this->_filterModelName = 'layerednav/layer_filter_category';
    }

    public function getVar() {
        //Get request variable name which is used for apply filter
        return $this->_filter->getRequestVar();
    }

    public function getClearUrl() {
        //Get URL and rewrite with SEO frieldly URL
        $_seoURL = '';
        //Get request filters with URL 
        $query = Mage::helper('layerednav')->getParams();
        if (!empty($query[$this->getVar()])) {
            $query[$this->getVar()] = null;
            $_seoURL = Mage::getUrl('*/*/*', array(
                        '_use_rewrite' => true,
                        '_query' => $query,
            ));
        }

        return $_seoURL;
    }

}
