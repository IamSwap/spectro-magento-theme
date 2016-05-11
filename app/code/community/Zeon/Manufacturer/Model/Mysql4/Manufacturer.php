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

class Zeon_Manufacturer_Model_Mysql4_Manufacturer extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        // Note that the manufacturer_id refers to the key field in your database table.
        $this->_init('zeon_manufacturer/manufacturer', 'manufacturer_id');
    }

    /**
     * Process Manufacturer data before saving
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Zeon_Manufacturer_Model_Resource_Manufacturer
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        if (!$this->getIsUniqueManufacturerToStores($object)) {
            Mage::throwException(
                Mage::helper('zeon_manufacturer')->__('A manufacturer URL key for specified store already exists.')
            );
        }

        if ($this->isNumericManufacturerIdentifier($object)) {
            Mage::throwException(
                Mage::helper('zeon_manufacturer')->__('The Manufacturer URL key cannot consist only of numbers.')
            );
        }
        // modify create / update dates
        if ($object->isObjectNew() && !$object->hasCreationTime()) {
            $object->setCreationTime(Mage::getSingleton('core/date')->gmtDate());
        }

        $object->setUpdateTime(Mage::getSingleton('core/date')->gmtDate());

        return parent::_beforeSave($object);
    }

     /**
     *  Check whether manufacturer identifier is valid
     *
     *  @param    Mage_Core_Model_Abstract $object
     *  @return   bool
     */

    protected function isNumericManufacturerIdentifier(Mage_Core_Model_Abstract $object)
    {
        return preg_match('/^[0-9]+$/', $object->getData('identifier'));
    }

    /**
     * Check for unique of identifier of manufacturer(s) to selected store(s).
     *
     * @param Mage_Core_Model_Abstract $object
     * @return bool
     */
    public function getIsUniqueManufacturerToStores(Mage_Core_Model_Abstract $object)
    {
        if (Mage::app()->isSingleStoreMode() || !$object->hasData('store_ids')) {
            $stores = array(Mage_Core_Model_App::ADMIN_STORE_ID);
        } else {
            $stores = (array)$object->getData('store_ids');
        }

        $selectId = $this->_getCommanManufacturer($object->getData('manufacturer'), $stores);
        $fetchedSelectId = $this->_getWriteAdapter()->fetchRow($selectId);

        if (!$object['manufacturer_id']) {
            if ($fetchedSelectId['manufacturer_id']) {
                return false;
            }
        } elseif ($object['manufacturer'] == $fetchedSelectId['manufacturer'] && $object['manufacturer_id'] != $fetchedSelectId['manufacturer_id']) {
                return false;
        }

        $select = $this->_getLoadByIdentifierSelect($object->getData('identifier'), $stores);

        if ($object->getId()) {
            $select->where('mps.manufacturer_id <> ?', $object->getId());
        }

        if ($this->_getWriteAdapter()->fetchRow($select)) {
            return false;
        }
        return true;
    }

    /**
     * Load store Ids array
     *
     * @param Zeon_Manufacturer_Model_Manufacturer $object
     */
    public function loadStoreIds(Zeon_Manufacturer_Model_Manufacturer $object)
    {
        $pollId   = $object->getId();
        $storeIds = array();
        if ($pollId) {
            $storeIds = $this->lookupStoreIds($pollId);
        }
        $object->setStoreIds($storeIds);
    }

    /**
     * Get store ids to which specified item is assigned
     *
     * @param int $id
     * @return array
     */
    public function lookupStoreIds($id)
    {
        return $this->_getReadAdapter()->fetchCol(
            $this->_getReadAdapter()->select()
            ->from(
                $this->getTable(
                    'zeon_manufacturer/store'
                ),
                'store_id'
            )
            ->where("{$this->getIdFieldName()} = :id_field"),
            array(':id_field' => $id)
        );
    }

    /**
     * Get manufacturer name
     *
     * @param int $id
     * @return array
     */
    public function getManufacturerName($id, $storeId)
    {
        $stores = array(Mage_Core_Model_App::ADMIN_STORE_ID, $storeId);
        $select = $this->_getReadAdapter()->select()
            ->from(array('eaov' => $this->getTable('eav/attribute_option_value')))
            ->join(
                array('eao' => $this->getTable('eav/attribute_option')),
                'eaov.option_id = eao.option_id',
                array()
            )
           ->where('eao.option_id = ?', $id)
           ->where('eaov.store_id IN (?)', $stores);

        $select->reset(Zend_Db_Select::COLUMNS)
            ->columns('eaov.value');
            return $this->_getReadAdapter()->fetchOne($select);
    }

    /**
     * Retrieve load select with filter by manufacturer, store and activity
     *
     * @param string $identifier
     * @param int|array $store
     * @param int $isActive
     * @return Varien_Db_Select
     */
    protected function _getCommanManufacturer($id, $store, $isActive = null)
    {
        $select = $this->_getReadAdapter()->select()
            ->from(array('mp' => $this->getMainTable()))
            ->join(
                array('mps' => $this->getTable('zeon_manufacturer/store')),
                'mp.manufacturer_id = mps.manufacturer_id',
                array()
            )
            ->where('mp.manufacturer = ?', $id)
            ->where('mps.store_id IN (?)', $store);

        if (!is_null($isActive)) {
            $select->where('mp.status = ?', $isActive);
        }
        return $select;
    }

    /**
     * Retrieve load select with filter by identifier, store and activity
     *
     * @param string $identifier
     * @param int|array $store
     * @param int $isActive
     * @return Varien_Db_Select
     */
    protected function _getLoadByIdentifierSelect($identifier, $store, $isActive = null)
    {
        $select = $this->_getReadAdapter()->select()
            ->from(array('mp' => $this->getMainTable()))
            ->join(
                array('mps' => $this->getTable('zeon_manufacturer/store')),
                'mp.manufacturer_id = mps.manufacturer_id',
                array()
            )
            ->where('mp.identifier = ?', $identifier)
            ->where('mps.store_id IN (?)', $store);

        if (!is_null($isActive)) {
            $select->where('mp.status = ?', $isActive);
        }
        return $select;
    }

    /**
     * Check if manufacturer identifier exist for specific store
     * return page id if page exists
     *
     * @param string $identifier
     * @param int $storeId
     * @return int
     */

    public function checkIdentifier($identifier, $storeId)
    {
        $stores = array(Mage_Core_Model_App::ADMIN_STORE_ID, $storeId);
        $select = $this->_getLoadByIdentifierSelect($identifier, $stores, 1);
        $select->reset(Zend_Db_Select::COLUMNS)
            ->columns('mp.manufacturer_id')
            ->order('mps.store_id DESC')
            ->limit(1);

        return $this->_getReadAdapter()->fetchOne($select);
    }

    /**
     * Delete current manufacturer from the table zeon_manufacturer_store and then
     * insert to update "manufacturer to store" relations
     *
     * @param Mage_Core_Model_Abstract $object
     */
    public function saveManufacturerStore(Mage_Core_Model_Abstract $object)
    {
        /** stores */
        $deleteWhere = $this->_getReadAdapter()->quoteInto('manufacturer_id = ?', $object->getId());
        $this->_getReadAdapter()->delete($this->getTable('zeon_manufacturer/store'), $deleteWhere);

        foreach ($object->getStoreIds() as $storeId) {
            $manufacturerStoreData = array(
            'manufacturer_id'   => $object->getId(),
            'store_id'  => $storeId
            );
            $this->_getWriteAdapter()->insert($this->getTable('zeon_manufacturer/store'), $manufacturerStoreData);
        }
    }
}