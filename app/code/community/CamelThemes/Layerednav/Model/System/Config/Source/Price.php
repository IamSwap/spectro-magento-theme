<?php
/**
* CamelThemes Layered Navigation 
* 
* @category     CamelThemes
* @package      CamelThemes_Layerednav
* @Class        CamelThemes_Layerednav_Model_System_Config_Source_Price   
*/ 

class CamelThemes_Layerednav_Model_System_Config_Source_Price extends Varien_Object
{
    public function toOptionArray()
    {
        $options = array();
        
        $options[] = array(
                'value'=> 'default',
                'label' => Mage::helper('layerednav')->__('Default')
        );
        $options[] = array(
                'value'=> 'slider',
                'label' => Mage::helper('layerednav')->__('Slider')
        );
        $options[] = array(
                'value'=> 'input',
                'label' => Mage::helper('layerednav')->__('Input')
        );
        
        return $options;
    }
} 