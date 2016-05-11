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

class Zeon_Manufacturer_Model_Mysql4_Manufacturer_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('zeon_manufacturer/manufacturer');
        $this->_map['fields']['manufacturer_id'] = 'main_table.manufacturer_id';
        $this->_map['fields']['update_time'] = 'main_table.update_time';
        $this->_map['fields']['status'] = 'main_table.status';
    }

    /**
     * Add stores column
     *
     * @return Zeon_Manufacturer_Model_Mysql4_Manufacturer_Collection
     */
    protected function _afterLoad()
    {
        parent::_afterLoad();
        if ($this->getFlag('add_stores_column')) {
            $this->_addStoresVisibility();
        }
        $this->_addManufacturerName();
        return $this;
    }

    /**
     * Set add stores column flag
     *
     * @return Zeon_Manufacturer_Model_Mysql4_Manufacturer_Collection
     */
    public function addStoresVisibility()
    {
        $this->setFlag('add_stores_column', true);
        return $this;
    }

    /**
     * Collect and set stores ids to each collection item
     * Used in manufacturer grid as Visible in column info
     *
     * @return Zeon_Manufacturer_Model_Mysql4_Manufacturer_Collection
     */
    protected function _addStoresVisibility()
    {
        $manufacturerIds = $this->getColumnValues('manufacturer_id');
        $manufacturerStores = array();
        if (sizeof($manufacturerIds)>0) {
            $select = $this->getConnection()->select()
                ->from($this->getTable('zeon_manufacturer/store'), array('store_id', 'manufacturer_id'))
                ->where('manufacturer_id IN(?)', $manufacturerIds);
            $manufacturerRaw = $this->getConnection()->fetchAll($select);
            foreach ($manufacturerRaw as $manufacturer) {
                if (!isset($manufacturerStores[$manufacturer['manufacturer_id']])) {
                    $manufacturerStores[$manufacturer['manufacturer_id']] = array();
                }

                $manufacturerStores[$manufacturer['manufacturer_id']][] = $manufacturer['store_id'];
            }
        }

        foreach ($this as $item) {
            if (isset($manufacturerStores[$item->getId()])) {
                $item->setStores($manufacturerStores[$item->getId()]);
            } else {
                $item->setStores(array());
            }
        }

        return $this;
    }

    /**
     * Collect and set Manufacturer title to each collection item
     * Used in manufacturer grid as Category in column info
     *
     * @return Zeon_Manufacturer_Model_Mysql4_Manufacturer_Collection
     */
    protected function _addManufacturerName()
    {
        $manufacturerIds = $this->getColumnValues('manufacturer');
        $manufacturers = array();
            if (sizeof($manufacturerIds)>0) {
            $select = $this->getConnection()->select()
                ->from($this->getTable('eav/attribute_option_value'), array('option_id','value'))
                ->where('option_id IN(?)', $manufacturerIds);
            $manufacturerRaw = $this->getConnection()->fetchAll($select);

                foreach ($manufacturerRaw as $manufacturer) {
                    $manufacturers[$manufacturer['option_id']] = array();
                    $manufacturers[$manufacturer['option_id']] = $manufacturer['value'];
                }
            }

        foreach ($this as $item) {
            if (isset($manufacturers[$item->getManufacturer()])) {
                $item->setManufacturer($manufacturers[$item->getManufacturer()]);
            } else {
                $item->setManufacturer('');
            }
        }
        return $this;
    }

    /**
     * Add Filter by store
     *
     * @param int|array $storeIds
     * @param bool $withAdmin
     * @return Zeon_Manufacturer_Model_Mysql4_Manufacturer_Collection
     */
    public function addStoreFilter($storeIds, $withAdmin = true)
    {
        if (!$this->getFlag('store_filter')) {
            if ($withAdmin) {
                $storeIds = array(0, $storeIds);
            }

            $this->getSelect()->join(
                array('store_table' => $this->getTable('zeon_manufacturer/store')),
                'main_table.manufacturer_id = store_table.manufacturer_id',
                array()
            )
            ->where('store_table.store_id in (?)', $storeIds);
            $this->setFlag('store_filter', true);
        }
        return $this;
    }

    /**
     * Add Filter by category
     *
     * @param string $manufacturerName
     * @return Zeon_Manufacturer_Model_Mysql4_Manufacturer_Collection
     */
    public function addManufacturerNameFilter($manufacturerName)
    {
        if (!$this->getFlag('manufacturer_name_filter')) {
            $this->getSelect()->join(
                array('manufacturer_name_table' => $this->getTable('eav_attribute_option_value')),
                'main_table.manufacturer = manufacturer_name_table.option_id',
                array()
            )
            ->where('manufacturer_name_table.value like (?)', $manufacturerName);

            $this->setFlag('manufacturer_name_filter', true);
        }
        return $this;
    }
}