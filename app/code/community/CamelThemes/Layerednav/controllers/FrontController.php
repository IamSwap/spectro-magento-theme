<?php

/**
 * CamelThemes Layered Navigation 
 * 
 * @category     CamelThemes
 * @package      CamelThemes_Layerednav
 * @Class        CamelThemes_Layerednav_FrontController  
 */
class CamelThemes_Layerednav_FrontController extends Mage_Core_Controller_Front_Action {

    public function categoryAction() {
        // init category
        $categoryId = (int) $this->getRequest()->getParam('id', false);
        if (!$categoryId) {
            $this->_forward('noRoute');
            return;
        }

        $category = Mage::getModel('catalog/category')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($categoryId);
        Mage::register('current_category', $category);


        $this->loadLayout();

        $response = array();
        $response['layer'] = $this->getLayout()->getBlock('layer')->toHtml();
        $response['products'] = $this->getLayout()->getBlock('root')->toHtml();

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        
        
    }

    public function searchAction() {
        $this->loadLayout();
        $response = array();
        $response['layer'] = $this->getLayout()->getBlock('layer')->toHtml();
        $response['products'] = $this->getLayout()->getBlock('root')->setIsSearchMode()->toHtml();
        
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
    }

}
