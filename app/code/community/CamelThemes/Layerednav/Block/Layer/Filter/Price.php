<?php
/**
* CamelThemes Layered Navigation 
* 
* @category     CamelThemes
* @package      CamelThemes_Layerednav 
* @Class        CamelThemes_Layerednav_Block_Layer_Filter_Price
*/  

class CamelThemes_Layerednav_Block_Layer_Filter_Price extends Mage_Catalog_Block_Layer_Filter_Price
{
    private $_filterType;  
    
    public function __construct()
    {
        parent::__construct(); 
		$this->_filterType = Mage::getStoreConfig('ajax/layerednav/price_style');
		//Load Custom PHTML of price
        $this->setTemplate('layerednav/filter_price_' . $this->_filterType . '.phtml');
		//Set Filter Model Name
        $this->_filterModelName = 'layerednav/layer_filter_price';
    }
    
    public function getVar(){
		//Get request variable name which is used for apply filter
        return $this->_filter->getRequestVar();
    }
    
    public function getClearUrl()
    {
        $_seoURL = '';
        $query = Mage::helper('layerednav')->getParams();
        if (!empty($query[$this->getVar()])){
			if (!empty($query[$this->getVar()])){
				$query[$this->getVar()] = null;
				$_seoURL = Mage::getUrl('*/*/*', array(
					'_use_rewrite' => true, 
					'_query'       => $query,
				)); 
			}
		}
        return $_seoURL;
    }
    
    public function isSelected($item)
    {
        return ($item->getValueString() == $this->_filter->getActiveState());        
    }
    
    public function getSymbol()
    {
		//To Get the current Currency Symbol 
		// Thanks to this blog (http://magento-developer-magento.blogspot.com/2011/11/how-to-get-current-currency-and.html)
        $_symbol = $this->getData('symbol');
        if (!$_symbol){
            $currency_code = Mage::app()->getStore()->getCurrentCurrencyCode();   
            $_symbol = trim(Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol());
            $this->setData('symbol', $_symbol);
        }
        return $_symbol;
    }
	
	public function getOffSet() {
		
		$minmaxArray = $this->_filter->getMinMaxPriceInt(); 
		$fromtoArray = explode(',', $this->_filter->getActiveState()); 
		
		
		
	}
} 