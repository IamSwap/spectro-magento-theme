<?php
/**
 * zeonsolutions inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.zeonsolutions.com/shop/license-enterprise.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * This package designed for Magento ENTERPRISE edition
 * =================================================================
 * zeonsolutions does not guarantee correct work of this extension
 * on any other Magento edition except Magento ENTERPRISE edition.
 * zeonsolutions does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   Zeon
 * @package    Zeon_Manufacturer
 * @version    0.0.1
 * @copyright  @copyright Copyright (c) 2013 zeonsolutions.Inc. (http://www.zeonsolutions.com)
 * @license    http://www.zeonsolutions.com/shop/license-enterprise.txt
 */

class Zeon_Manufacturer_Helper_Url extends Mage_Core_Helper_Url
{
    /**
     * Symbol convert table
     *
     * @var array
     */
    protected $_convertTable = array(
        '&amp;' => 'and',   '@' => 'at',    'Â©' => 'c', 'Â®' => 'r', 'Ã€' => 'a',
        'Ã�' => 'a', 'Ã‚' => 'a', 'Ã„' => 'a', 'Ã…' => 'a', 'Ã†' => 'ae','Ã‡' => 'c',
        'Ãˆ' => 'e', 'Ã‰' => 'e', 'Ã‹' => 'e', 'ÃŒ' => 'i', 'Ã�' => 'i', 'ÃŽ' => 'i',
        'Ã�' => 'i', 'Ã’' => 'o', 'Ã“' => 'o', 'Ã”' => 'o', 'Ã•' => 'o', 'Ã–' => 'o',
        'Ã˜' => 'o', 'Ã™' => 'u', 'Ãš' => 'u', 'Ã›' => 'u', 'Ãœ' => 'u', 'Ã�' => 'y',
        'ÃŸ' => 'ss','Ã ' => 'a', 'Ã¡' => 'a', 'Ã¢' => 'a', 'Ã¤' => 'a', 'Ã¥' => 'a',
        'Ã¦' => 'ae','Ã§' => 'c', 'Ã¨' => 'e', 'Ã©' => 'e', 'Ãª' => 'e', 'Ã«' => 'e',
        'Ã¬' => 'i', 'Ã­' => 'i', 'Ã®' => 'i', 'Ã¯' => 'i', 'Ã²' => 'o', 'Ã³' => 'o',
        'Ã´' => 'o', 'Ãµ' => 'o', 'Ã¶' => 'o', 'Ã¸' => 'o', 'Ã¹' => 'u', 'Ãº' => 'u',
        'Ã»' => 'u', 'Ã¼' => 'u', 'Ã½' => 'y', 'Ã¾' => 'p', 'Ã¿' => 'y', 'Ä€' => 'a',
        'Ä�' => 'a', 'Ä‚' => 'a', 'Äƒ' => 'a', 'Ä„' => 'a', 'Ä…' => 'a', 'Ä†' => 'c',
        'Ä‡' => 'c', 'Äˆ' => 'c', 'Ä‰' => 'c', 'ÄŠ' => 'c', 'Ä‹' => 'c', 'ÄŒ' => 'c',
        'Ä�' => 'c', 'ÄŽ' => 'd', 'Ä�' => 'd', 'Ä�' => 'd', 'Ä‘' => 'd', 'Ä’' => 'e',
        'Ä“' => 'e', 'Ä”' => 'e', 'Ä•' => 'e', 'Ä–' => 'e', 'Ä—' => 'e', 'Ä˜' => 'e',
        'Ä™' => 'e', 'Äš' => 'e', 'Ä›' => 'e', 'Äœ' => 'g', 'Ä�' => 'g', 'Äž' => 'g',
        'ÄŸ' => 'g', 'Ä ' => 'g', 'Ä¡' => 'g', 'Ä¢' => 'g', 'Ä£' => 'g', 'Ä¤' => 'h',
        'Ä¥' => 'h', 'Ä¦' => 'h', 'Ä§' => 'h', 'Ä¨' => 'i', 'Ä©' => 'i', 'Äª' => 'i',
        'Ä«' => 'i', 'Ä¬' => 'i', 'Ä­' => 'i', 'Ä®' => 'i', 'Ä¯' => 'i', 'Ä°' => 'i',
        'Ä±' => 'i', 'Ä²' => 'ij','Ä³' => 'ij','Ä´' => 'j', 'Äµ' => 'j', 'Ä¶' => 'k',
        'Ä·' => 'k', 'Ä¸' => 'k', 'Ä¹' => 'l', 'Äº' => 'l', 'Ä»' => 'l', 'Ä¼' => 'l',
        'Ä½' => 'l', 'Ä¾' => 'l', 'Ä¿' => 'l', 'Å€' => 'l', 'Å�' => 'l', 'Å‚' => 'l',
        'Åƒ' => 'n', 'Å„' => 'n', 'Å…' => 'n', 'Å†' => 'n', 'Å‡' => 'n', 'Åˆ' => 'n',
        'Å‰' => 'n', 'ÅŠ' => 'n', 'Å‹' => 'n', 'ÅŒ' => 'o', 'Å�' => 'o', 'ÅŽ' => 'o',
        'Å�' => 'o', 'Å�' => 'o', 'Å‘' => 'o', 'Å’' => 'oe','Å“' => 'oe','Å”' => 'r',
        'Å•' => 'r', 'Å–' => 'r', 'Å—' => 'r', 'Å˜' => 'r', 'Å™' => 'r', 'Åš' => 's',
        'Å›' => 's', 'Åœ' => 's', 'Å�' => 's', 'Åž' => 's', 'ÅŸ' => 's', 'Å ' => 's',
        'Å¡' => 's', 'Å¢' => 't', 'Å£' => 't', 'Å¤' => 't', 'Å¥' => 't', 'Å¦' => 't',
        'Å§' => 't', 'Å¨' => 'u', 'Å©' => 'u', 'Åª' => 'u', 'Å«' => 'u', 'Å¬' => 'u',
        'Å­' => 'u', 'Å®' => 'u', 'Å¯' => 'u', 'Å°' => 'u', 'Å±' => 'u', 'Å²' => 'u',
        'Å³' => 'u', 'Å´' => 'w', 'Åµ' => 'w', 'Å¶' => 'y', 'Å·' => 'y', 'Å¸' => 'y',
        'Å¹' => 'z', 'Åº' => 'z', 'Å»' => 'z', 'Å¼' => 'z', 'Å½' => 'z', 'Å¾' => 'z',
        'Å¿' => 'z', 'Æ�' => 'e', 'Æ’' => 'f', 'Æ ' => 'o', 'Æ¡' => 'o', 'Æ¯' => 'u',
        'Æ°' => 'u', 'Ç�' => 'a', 'ÇŽ' => 'a', 'Ç�' => 'i', 'Ç�' => 'i', 'Ç‘' => 'o',
        'Ç’' => 'o', 'Ç“' => 'u', 'Ç”' => 'u', 'Ç•' => 'u', 'Ç–' => 'u', 'Ç—' => 'u',
        'Ç˜' => 'u', 'Ç™' => 'u', 'Çš' => 'u', 'Ç›' => 'u', 'Çœ' => 'u', 'Çº' => 'a',
        'Ç»' => 'a', 'Ç¼' => 'ae','Ç½' => 'ae','Ç¾' => 'o', 'Ç¿' => 'o', 'É™' => 'e',
        'Ð�' => 'jo','Ð„' => 'e', 'Ð†' => 'i', 'Ð‡' => 'i', 'Ð�' => 'a', 'Ð‘' => 'b',
        'Ð’' => 'v', 'Ð“' => 'g', 'Ð”' => 'd', 'Ð•' => 'e', 'Ð–' => 'zh','Ð—' => 'z',
        'Ð˜' => 'i', 'Ð™' => 'j', 'Ðš' => 'k', 'Ð›' => 'l', 'Ðœ' => 'm', 'Ð�' => 'n',
        'Ðž' => 'o', 'ÐŸ' => 'p', 'Ð ' => 'r', 'Ð¡' => 's', 'Ð¢' => 't', 'Ð£' => 'u',
        'Ð¤' => 'f', 'Ð¥' => 'h', 'Ð¦' => 'c', 'Ð§' => 'ch','Ð¨' => 'sh','Ð©' => 'sch',
        'Ðª' => '-', 'Ð«' => 'y', 'Ð¬' => '-', 'Ð­' => 'je','Ð®' => 'ju','Ð¯' => 'ja',
        'Ð°' => 'a', 'Ð±' => 'b', 'Ð²' => 'v', 'Ð³' => 'g', 'Ð´' => 'd', 'Ðµ' => 'e',
        'Ð¶' => 'zh','Ð·' => 'z', 'Ð¸' => 'i', 'Ð¹' => 'j', 'Ðº' => 'k', 'Ð»' => 'l',
        'Ð¼' => 'm', 'Ð½' => 'n', 'Ð¾' => 'o', 'Ð¿' => 'p', 'Ñ€' => 'r', 'Ñ�' => 's',
        'Ñ‚' => 't', 'Ñƒ' => 'u', 'Ñ„' => 'f', 'Ñ…' => 'h', 'Ñ†' => 'c', 'Ñ‡' => 'ch',
        'Ñˆ' => 'sh','Ñ‰' => 'sch','ÑŠ' => '-','Ñ‹' => 'y', 'ÑŒ' => '-', 'Ñ�' => 'je',
        'ÑŽ' => 'ju','Ñ�' => 'ja','Ñ‘' => 'jo','Ñ”' => 'e', 'Ñ–' => 'i', 'Ñ—' => 'i',
        'Ò�' => 'g', 'Ò‘' => 'g', '×�' => 'a', '×‘' => 'b', '×’' => 'g', '×“' => 'd',
        '×”' => 'h', '×•' => 'v', '×–' => 'z', '×—' => 'h', '×˜' => 't', '×™' => 'i',
        '×š' => 'k', '×›' => 'k', '×œ' => 'l', '×�' => 'm', '×ž' => 'm', '×Ÿ' => 'n',
        '× ' => 'n', '×¡' => 's', '×¢' => 'e', '×£' => 'p', '×¤' => 'p', '×¥' => 'C',
        '×¦' => 'c', '×§' => 'q', '×¨' => 'r', '×©' => 'w', '×ª' => 't', 'â„¢' => 'tm',
    );

    /**
     * Check additional instruction for convertation table in configuration
     */
    public function __construct()
    {
        $convertNode = Mage::getConfig()->getNode('default/url/convert');
        if ($convertNode) {
            foreach ($convertNode->children() as $node) {
                $this->_convertTable[strval($node->from)] = strval($node->to);
            }
        }
    }

    /**
     * Get chars convertation table
     *
     * @return array
     */
    public function getConvertTable()
    {
        return $this->_convertTable;
    }

    /**
     * Process string based on convertation table
     *
     * @param   string $string
     * @return  string
     */
    public function format($string)
    {
        return strtr($string, $this->getConvertTable());
    }
}
