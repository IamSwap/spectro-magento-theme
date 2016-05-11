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

class Zeon_Manufacturer_Block_Adminhtml_Manufacturer_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * Load Wysiwyg on demand and Prepare layout
     */
    protected function _prepareLayout()
    { 
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }

    /**
     * Set form id prefix, set values if manufacturer is editing
     *
     * @return Zeon_Manufacturer_Block_Adminhtml_Manufacturer_Edit_Tab_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $htmlIdPrefix = 'manufacturer_information_';
        $form->setHtmlIdPrefix($htmlIdPrefix);
        $fieldsetHtmlClass = 'fieldset-wide';
        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig(array('tab_id' => $this->getTabId()));

        /* @var $model Zeon_Manufacturer_Model_Manufacturer */
        $model = Mage::registry('current_manufacturer');
        $contents = $model->getDescription();

        $fieldset = $form->addFieldset(
            'base_fieldset', 
            array(
                'legend'=>Mage::helper('zeon_manufacturer')->__('Manufacturer information'),
                'class'    => $fieldsetHtmlClass,
            )
        );

        if ($model->getManufacturerId()) {
            $fieldset->addField(
                'manufacturer_id', 
                'hidden', 
                array(
                    'name'    => 'manufacturer_id',
                )
            );
        }

        $fieldset->addField(
            'manufacturer', 
            'select', 
            array(
                'label'    => Mage::helper('zeon_manufacturer')->__('Manufacturer'),
                'name'     => 'manufacturer',
                'required' => true,
                'options'  => Mage::getModel('zeon_manufacturer/options')->getManufacturers(),
            )
        );

        $fieldset->addField(
            'identifier', 
            'text', 
            array(
                'label'    => Mage::helper('zeon_manufacturer')->__('Identifier'),
                'name'     => 'identifier',
                'required' => false,
            )
        );

        $fieldset->addField(
            'status', 
            'select', 
            array(
                'label'    => Mage::helper('zeon_manufacturer')->__('Status'),
                'name'     => 'status',
                'required' => 'true',
                'disabled' => (bool)$model->getIsReadonly(),
                'options'  => Mage::getModel('zeon_manufacturer/status')->getAllOptions(),
            )
        );

        if (!$model->getId()) {
            $model->setData('status', Zeon_Manufacturer_Model_Status::STATUS_ENABLED);
        }
        /**
         * Check is single store mode
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField(
                'store_ids', 
                'multiselect', 
                array(
                    'name'      => 'store_ids[]',
                    'label'     => Mage::helper('zeon_manufacturer')->__('Visible In'),
                    'required'  => true,
                    'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
                    'value'        => $model->getStoreIds(),
                )
            );
        } else {
            $fieldset->addField(
                'store_id', 
                'hidden', 
                array(
                    'name'    => 'store_ids[]',
                    'value'    => Mage::app()->getStore(true)->getId()
                )
            );
            $model->setStoreIds(Mage::app()->getStore(true)->getId());
        }

        $fieldset->addField(
            'is_display_home', 
            'select', 
            array(
                'label'    => Mage::helper('zeon_manufacturer')->__('Display on Frontend'),
                'name'     => 'is_display_home',
                'required' => 'true',
                'disabled' => (bool)$model->getIsReadonly(),
                'options'  => Mage::getModel('zeon_manufacturer/status')->getAllOptions(),
           )
        ); 

        $options[] = array(
            'value'     => '',
            'label'     => '',
        );

        $fieldset->addField(
            'manufacturer_logo', 
            'Thumbnail', 
            array(
                'label'     => Mage::helper('zeon_manufacturer')->__('Manufacturer Logo'),
                'required' => 'true',
                'name'      => 'manufacturer_logo',
            )
        );

        $fieldset->addField(
            'manufacturer_banner', 
            'Thumbnail', 
            array(
                'label'     => Mage::helper('zeon_manufacturer')->__('Manufacturer Banner'),
                'required'  => false,
                'name'      => 'manufacturer_banner',
            )
        );

        $fieldset->addField(
            'description', 
            'editor', 
            array(
                'name'      => 'description',
                'label'     => Mage::helper('zeon_manufacturer')->__('Description'),
                'title'     => Mage::helper('zeon_manufacturer')->__('Description'),
                'style'     => 'height:36em',
                'required'  => true,
                'config'    => $wysiwygConfig,
            )
        );	

        $fieldset->addField(
            'sort_order', 
            'text', 
            array(
                'label'        => Mage::helper('zeon_manufacturer')->__('Sort Order'),
                'name'         => 'sort_order',
            )
        );
        $form->setValues($model->getData());
        $this->setForm($form);
        return $this;
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper('zeon_manufacturer')->__('Manufacturer Information');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    /**
     * Returns status flag about this tab can be showen or not
     *
     * @return true
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return true
     */
    public function isHidden()
    {
        return false;
    }
}