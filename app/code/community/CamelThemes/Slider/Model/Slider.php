<?php
class CamelThemes_Slider_Model_Slider extends Mage_Core_Model_Abstract
{
     public function _construct()
     {
         parent::_construct();
         $this->_init('slider/slider');
     }
}