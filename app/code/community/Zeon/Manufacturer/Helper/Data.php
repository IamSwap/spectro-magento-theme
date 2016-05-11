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

class Zeon_Manufacturer_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_PATH_ENABLED = 'zeon_manufacturer/general/is_enabled';
    const XML_PATH_DEFAULT_ATTRIBUTE_CODE = 'zeon_manufacturer/frontend/manufacturers_attribute_code';
    const XML_PATH_DEFAULT_META_TITLE = 'zeon_manufacturer/frontend/meta_title';
    const XML_PATH_DEFAULT_META_KEYWORDS = 'zeon_manufacturer/frontend/meta_keywords';
    const XML_PATH_DEFAULT_META_DESCRIPTION = 'zeon_manufacturer/frontend/meta_description';
    
    public function setIsModuleEnabled($value)
    {
        Mage::getModel('core/config')->saveConfig(self::XML_PATH_ENABLED, $value);
    }

    /**
     * Retrieve default title for manufacturers
     *
     * @return string
     */
    public function getManufacturersAttributeCode()
    {
        return Mage::getStoreConfig(self::XML_PATH_DEFAULT_ATTRIBUTE_CODE);
    }

    /**
     * Retrieve default title for manufacturers
     *
     * @return string
     */
    public function getDefaultTitle()
    {
        return Mage::getStoreConfig(self::XML_PATH_DEFAULT_META_TITLE);
    }

    /**
     * Retrieve default meta keywords for manufacturers
     *
     * @return string
     */
    public function getDefaultMetaKeywords()
    {
        return Mage::getStoreConfig(self::XML_PATH_DEFAULT_META_KEYWORDS);
    }

    /**
     * Retrieve default meta description for manufacturers
     *
     * @return string
     */
    public function getDefaultMetaDescription()
    {
        return Mage::getStoreConfig(self::XML_PATH_DEFAULT_META_DESCRIPTION);
    }
    
    /**
     * Retrieve Template processor for Block Content
     *
     * @return Varien_Filter_Template
     */
    public function getBlockTemplateProcessor()
    {
        return Mage::getModel('zeon_manufacturer/template_filter');
    }
}