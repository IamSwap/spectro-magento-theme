<?php
/**
* CamelThemes Layered Navigation
* 
* @category     CamelThemes
* @package      CamelThemes_Layerednav 
* @Class        CamelThemes_Layerednav_Block_Layer_Filter_Categorysearch
*/ 

class CamelThemes_Layerednav_Block_Layer_Filter_Categorysearch extends CamelThemes_Layerednav_Block_Layer_Filter_Category
{
    public function __construct()
    {

        parent::__construct();
		//Load Custom PHTML of category search
        $this->setTemplate('layerednav/filter_category_search.phtml');
		//Set Filter Model Name
        $this->_filterModelName = 'layerednav/layer_filter_categorysearch'; 
    }
    
} 