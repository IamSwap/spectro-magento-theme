<?php
/**
* CamelThemes Layered Navigation 
* 
* @category     CamelThemes
* @package      CamelThemes_Layerednav 
* @Class        CamelThemes_Layerednav_Model_System_Config_Source_Category   
*/ 

class CamelThemes_Layerednav_Model_System_Config_Source_Category extends Varien_Object
{
    public function toOptionArray()
    {
        $options = array();
        
        $options[] = array(
                'value'=> 'breadcrumbs',
                'label' => Mage::helper('layerednav')->__('Breadcrumbs')
        );
        $options[] = array(
                'value'=> 'none',
                'label' => Mage::helper('layerednav')->__('None')
        );
        
        return $options;
    }
} 