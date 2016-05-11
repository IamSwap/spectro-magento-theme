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

class Zeon_Manufacturer_Block_Adminhtml_Manufacturer_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * Initialize manufacturer edit page. Set management buttons
     *
     */
    public function __construct()
    {
        $this->_objectId = 'id';
        $this->_controller = 'adminhtml_manufacturer';
        $this->_blockGroup = 'zeon_manufacturer';

        parent::__construct();

        $this->_updateButton('save', 'label', Mage::helper('zeon_manufacturer')->__('Save Manufacturer'));
        $this->_updateButton('delete', 'label', Mage::helper('zeon_manufacturer')->__('Delete Manufacturer'));

        $this->_addButton(
            'save_and_edit_button', array(
            'label'   => Mage::helper('zeon_manufacturer')->__('Save and Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class'   => 'save'
            ), 100
        );
        $this->_formScripts[] = "
            function saveAndContinueEdit() {
            editForm.submit($('edit_form').action + 'back/edit/');}";
    }

    /**
     * Get current loaded manufacturer ID
     *
     */
    public function getManufacturerId()
    {
        return Mage::registry('current_manufacturer')->getId();
    }

    /**
     * Get header text for manufacturer edit page
     *
     */
    public function getHeaderText()
    {
        if (Mage::registry('current_manufacturer')->getId()) {
            return $this->htmlEscape(Mage::registry('current_manufacturer')->getTitle());
        } else {
            return Mage::helper('zeon_manufacturer')->__('New Manufacturer');
        }
    }

    /**
     * Get form action URL
     *
     */
    public function getFormActionUrl()
    {
        return $this->getUrl('*/*/save');
    }
}