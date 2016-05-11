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

class Zeon_Manufacturer_Adminhtml_Manufacturer_ListController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->_title($this->__('Zeon Extensions'))->_title($this->__('Manufacturer'));
        $this->loadLayout();
        $this->_setActiveMenu('zextensions/zeon_manufacturer');
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * Edit action
     *
     */
    public function editAction()
    {
        $id     = $this->getRequest()->getParam('id');
        $model = $this->_initManufacturer('id');
        $model  = Mage::getModel('zeon_manufacturer/manufacturer')->load($id);

        if (!$model->getId() && $id) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('zeon_manufacturer')->__('This manufacturer no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $this->_title($model->getId() ? $model->getTitle() : $this->__('Add Manufacturer'));

        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $model->addData($data);
        }

        $this->loadLayout();
        $this->_setActiveMenu('zextensions/zeon_manufacturer');

        $this->_addBreadcrumb(
            $id ? Mage::helper('zeon_manufacturer')->__('Edit Manufacturer') : Mage::helper('zeon_manufacturer')->__('Add Manufacturer'),
            $id ? Mage::helper('zeon_manufacturer')->__('Edit Manufacturer') : Mage::helper('zeon_manufacturer')->__('Add Manufacturer')
        )->renderLayout();
    }

    /**
     * Save action
     */
    public function saveAction()
    {
        $redirectBack = $this->getRequest()->getParam('back', false);
        if ($data = $this->getRequest()->getPost()) {
            $id = $this->getRequest()->getParam('id');
            $model = $this->_initManufacturer();

            if (!$model->getId() && $id) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('zeon_manufacturer')->__('This manufacturer no longer exists.')
                );
                $this->_redirect('*/*/');
                return;
            }
            $manufacturerName = $model->getManufacturerName($data['manufacturer'], Mage::app()->getStore()->getId());
            $identifier = $data['identifier'] ? $data['identifier'] : $manufacturerName;
            $data['identifier'] = Mage::getModel('zeon_manufacturer/url')->formatUrlKey($identifier);
            // save manufacturer logo and banner
            $fieldName = array();
            $fieldName[0] =  'manufacturer_logo';
            $fieldName[1] =  'manufacturer_banner';
            if ($count = count($_FILES)) {
                for ($i = 0; $i < $count; $i++) {
                    if (isset($_FILES[$fieldName[$i]]['name']) && $_FILES[$fieldName[$i]]['tmp_name'] != '') {
                        $uploader = new Varien_File_Uploader($fieldName[$i]);
                        $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
                        $uploader->setAllowRenameFiles(true);
                        $uploader->setFilesDispersion(false);
                        $path = Mage::getBaseDir('media') . DS . 'manufacturer' . DS;
                        $_FILES[$fieldName[$i]]['name'] = str_replace(' ', '-', $_FILES[$fieldName[$i]]['name']);
                        $uploader->save($path, $_FILES[$fieldName[$i]]['name']);
                        $data[$fieldName[$i]] = $_FILES[$fieldName[$i]]['name'];
                        if ($_FILES['manufacturer_logo']['name'] && $_FILES['manufacturer_logo']['tmp_name'] != '') {
                            $model->setManufacturerLogo($uploader->getUploadedFileName());
                        } else {
                            $model->setManufacturerBanner($uploader->getUploadedFileName());
                        }
                    } else {
                        if (isset($data[$fieldName[$i]]['delete']) && $data[$fieldName[$i]]['delete'] == 1) {
                            $data[$fieldName[$i]] = '';
                        } else {
                            unset($data[$fieldName[$i]]);
                        }
                    }
                }
            }
            // save model
            try {
                if (!empty($data)) {
                    $model->addData($data);
                    Mage::getSingleton('adminhtml/session')->setFormData($data);
                }
                $model->save();
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('zeon_manufacturer')->__('The manufacturer has been saved.')
                );
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
                $redirectBack = true;
            } catch (Exception $e) {
                $this->_getSession()->addError(
                    Mage::helper('zeon_manufacturer')->__('Unable to save the manufacturer.')
                );
                $redirectBack = true;
                Mage::logException($e);
            }
            if ($redirectBack) {
                $this->_redirect('*/*/edit', array('id' => $model->getId()));
                return;
            }
        }
        $this->_redirect('*/*/');
    }

    /**
     * Delete action
     */
    public function deleteAction()
    {
        // check if we know what should be deleted
        if ($id = $this->getRequest()->getParam('id')) {
            try {
            // init model and delete
            $model = Mage::getModel('zeon_manufacturer/manufacturer');
            $model->load($id);
            $model->delete();
            // display success message
            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('zeon_manufacturer')->__('The manufacturer has been deleted.')
            );
            // go to grid
            $this->_redirect('*/*/');
            return;
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addError(
                    Mage::helper('zeon_manufacturer')->__(
                        'An error occurred while deleting manufacturer data. Please review log and try again.'
                    )
                );
                Mage::logException($e);
                // save data in session
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                // redirect to edit form
                $this->_redirect('*/*/edit', array('id' => $id));
                return;
            }
        }
        // display error message
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('zeon_manufacturer')->__('Unable to find a manufacturer to delete.')
        );
        // go to grid
        $this->_redirect('*/*/');
    }

    /**
     * Delete specified manufacturer using grid massaction
     *
     */
    public function massDeleteAction()
    {
        $ids = $this->getRequest()->getParam('manufacturer');
        if (!is_array($ids)) {
            $this->_getSession()->addError($this->__('Please select manufacturer(s).'));
        } else {
            try {
                foreach ($ids as $id) {
                    $model = Mage::getSingleton('zeon_manufacturer/manufacturer')->load($id);
                    $model->delete();
                }

                $this->_getSession()->addSuccess($this->__('Total of %d record(s) have been deleted.', count($ids)));
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addError(
                    Mage::helper('zeon_manufacturer')->__(
                        'An error occurred while mass deleting manufacturer. Please review log and try again.'
                    )
                );
                Mage::logException($e);
                return;
            }
        }
        $this->_redirect('*/*/index');
    }

     /**
     * Update specified manufacturer status using grid massaction
     *
     */
    public function massStatusAction()
    {
        $ids = $this->getRequest()->getParam('manufacturer');
        if (!is_array($ids)) {
            $this->_getSession()->addError($this->__('Please select manufacturer(s).'));
        } else {
            try {
                foreach ($ids as $id) {
                    $model = Mage::getSingleton('zeon_manufacturer/manufacturer')->load($id);
                    $model->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess($this->__('Total of %d record(s) have been updated', count($ids)));
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addError(
                    Mage::helper('zeon_manufacturer')->__(
                        'An error occurred while mass updating manufacturer. Please review log and try again.'
                    )
                );
                Mage::logException($e);
                return;
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * Load Manufacturer from request
     *
     * @param string $idFieldName
     * @return Zeon_Manufacturer_Model_Manufacturer $model
     */
    protected function _initManufacturer($idFieldName = 'manufacturer_id')
    {
        $id = (int)$this->getRequest()->getParam($idFieldName);
        $model = Mage::getModel('zeon_manufacturer/manufacturer');
        if ($id) {
            $model->load($id);
        }
        if (!Mage::registry('current_manufacturer')) {
            Mage::register('current_manufacturer', $model);
        }
        return $model;
    }

    /**
     * Render Manufacturer grid
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

}