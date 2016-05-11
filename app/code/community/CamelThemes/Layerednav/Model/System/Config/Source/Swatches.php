<?php
/**
* CamelThemes Layered Navigation 
* 
* @category     CamelThemes
* @package      CamelThemes_Layerednav 
* @Class        CamelThemes_Layerednav_Model_System_Config_Source_Price   
*/ 

class CamelThemes_Layerednav_Model_System_Config_Source_Swatches extends Varien_Object
{
    public function toOptionArray()
    {
        $options = array();
        
        $options[] = array(
                'value'=> 'link',
                'label' => Mage::helper('layerednav')->__('Links Only')
        );
        $options[] = array(
                'value'=> 'icons',
                'label' => Mage::helper('layerednav')->__('Icons Only')
        );
        $options[] = array(
                'value'=> 'iconslinks',
                'label' => Mage::helper('layerednav')->__('Icons + Links')
        );
        
        return $options;
    }
} 