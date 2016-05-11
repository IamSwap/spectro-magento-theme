<?php
/**
* CamelThemes Layered Navigation 
* 
* @category     CamelThemes
* @package      CamelThemes_Layerednav 
* @Class        CamelThemes_Layerednav_Model_Select   
*/ 

class CamelThemes_Layerednav_Model_Select extends Zend_Db_Select 
{
    public function __construct()
    {
    }

    public function setPart($part, $val){
        $this->_parts[$part] = $val;
    }   
} 