<?php
class CamelThemes_Slider_Model_Mysql4_Slider extends Mage_Core_Model_Mysql4_Abstract
{
     public function _construct()
     {
         $this->_init('slider/slider', 'id_camelthemes_slider');
     }
}