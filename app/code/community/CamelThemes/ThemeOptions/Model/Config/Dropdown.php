<?php

/**
 * CamelThemes ThemeOptions 
 * 
 * @category     CamelThemes
 * @package      CamelThemes_ThemeOptions 
 * @Class        CamelThemes_ThemeOptions_Model_Config_Dropdown
 */ 

class CamelThemes_ThemeOptions_Model_Config_Dropdown
{

	public function toOptionArray()
    {
        return array(
            array(
                'value' => 'key1',
                'label' => 'Value 1',
            ),
            array(
                'value' => 'key2',
                'label' => 'Value 2',
            ),
        );
    }

}
