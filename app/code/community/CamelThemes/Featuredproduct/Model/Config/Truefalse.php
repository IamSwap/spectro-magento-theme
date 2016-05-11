<?php

/**
 * CamelThemes Featuredproduct
 * 
 * @category     CamelThemes
 * @package      CamelThemes_Featuredproduct 
 * @Class        CamelThemes_Featuredproduct_Model_Config_Truefalse
 */ 

class CamelThemes_Featuredproduct_Model_Config_Truefalse
{

    public function toOptionArray()
    {
        return array(
            array('value'=>'true', 'label'=>Mage::helper('adminhtml')->__('True')),
            array('value'=>'false', 'label'=>Mage::helper('adminhtml')->__('False'))
        );
    }

}
