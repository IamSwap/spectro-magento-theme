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

class Zeon_Manufacturer_Block_Left extends Mage_Core_Block_Template
{
    protected $_manufacturersCollection;

    /**
     * Retrieve Manufacturers collection
     *
     * @return Zeon_Manufacturer_Model_Resource_Manufacturer_Collection
     */
    protected function _getManufacturersCollection()
    {
        if (is_null($this->_manufacturersCollection)) {
            $this->_manufacturersCollection = Mage::getResourceModel('zeon_manufacturer/manufacturer_collection')
                                    ->distinct(true)
                                    ->addStoreFilter(Mage::app()->getStore()->getId())
                                    ->addFieldToFilter('status', Zeon_Manufacturer_Model_Status::STATUS_ENABLED)
                                    ->addOrder('sort_order', 'asc');
        }
        return $this->_manufacturersCollection;
    }

    /**
     * Retrieve loaded Manufacturers collection
     *
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    public function getManufacturersCollection()
    {
        return $this->_getManufacturersCollection();
    }
}