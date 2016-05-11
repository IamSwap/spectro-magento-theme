<?php

/**
 * CamelThemes ThemeOptions 
 * 
 * @category     CamelThemes
 * @package      CamelThemes_ThemeOptions 
 * @Class        CamelThemes_ThemeOptions_Model_Config_Sliderstyle
 */ 

class CamelThemes_ThemeOptions_Model_Config_Sliderstyle
{

	public function toOptionArray()
    {
        return array(
            array(
                'value' => 'slide',
                'label' => 'Slide',
            ),
            array(
                'value' => 'fade',
                'label' => 'Fade',
            ),
        );
    }

}
