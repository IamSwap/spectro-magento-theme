<?php
/**
 * CamelThemes ThemeOptions 
 * 
 * @category     CamelThemes
 * @package      CamelThemes_ThemeOptions 
 * @Class        CamelThemes_ThemeOptions_Block_Adminhtml_Page_Menu
 */ 

class CamelThemes_ThemeOptions_Block_Adminhtml_Page_Menu extends Mage_Adminhtml_Block_Page_Menu {

    public function __construct() {
        parent::__construct ();
    }


    public function getMenuLevel($menu, $level = 0)
    {
        
       
        $html = '<ul ' . (!$level ? 'id="nav"' : '') . '>' . PHP_EOL;
        foreach ($menu as $index => $item) {

                $html .= '<li ' . (!empty($item['children']) ? 'onmouseover="Element.addClassName(this,\'over\')" '
                    . 'onmouseout="Element.removeClassName(this,\'over\')"' : '') . ' class="'
                    . (!$level && !empty($item['active']) ? ' active' : '') . ' '
                    . (!empty($item['children']) ? ' parent' : '')
                    . (!empty($level) && !empty($item['last']) ? ' last' : '')
                    . ' level' . $level . '"> <a href="' . $item['url'] . '" '
                    . (!empty($item['title']) ? 'title="' . $item['title'] . '"' : '') . ' '
                    . (!empty($item['click']) ? 'onclick="' . $item['click'] . '"' : '') . ' class="'
                    . ($level === 0 && !empty($item['active']) ? 'active' : '') . '">'

                    // only for SPECTRO menu we'll change html code returned
                    . ($index == 'camelthemes' ? $item['label'] : '<span>' 
                    . $this->escapeHtml($item['label']) . '</span>') . '</a>' . PHP_EOL;

                if (!empty($item['children'])) {
                    $html .= $this->getMenuLevel($item['children'], $level + 1);
                }
                $html .= '</li>' . PHP_EOL;


        }
        $html .= '</ul>' . PHP_EOL;

        return $html;



    }
}