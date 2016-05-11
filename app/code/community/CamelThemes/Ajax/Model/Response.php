<?php


/**
 * CamelThemes Ajax 
 * 
 * @category     CamelThemes
 * @package      CamelThemes_Ajax 
 * @Class        CamelThemes_Ajax_Model_Response
 */ 

class CamelThemes_Ajax_Model_Response extends Mage_Catalog_Block_Product_Abstract {

    public function send() {
        Zend_Json::$useBuiltinEncoderDecoder = true;
        if ($this->getError())
            $this->setStatus('ERROR');
        else
            $this->setStatus('SUCCESS');
        Mage::app()->getFrontController()->getResponse()->setHeader('Content-Type', 'text/plain')->setBody(Zend_Json::encode($this->getData()));
        Mage::app()->getFrontController()->getResponse()->sendResponse();
        die;
    }

    public function addUpdatedBlocks(&$_response) {
        $updated_blocks = unserialize(Mage::getStoreConfig('ajax/general/update_blocks'));

        if ($updated_blocks) {
            $layout = Mage::getSingleton('core/layout');
            $res = array();

            foreach ($updated_blocks['id'] as $index => $block) {
                $value = $layout->getBlock($updated_blocks['xml'][$index]);

                if ($value) {
                    $tmp['key'] = $block;
                    $tmp['value'] = $value->toHtml();
                    $res[] = $tmp;
                }
            }
            if (!empty($res)) {
                $_response->setUpdateBlocks($res);
            }
        }
    }



    public function addConfigurableOptionsBlock(&$_response) {
        $layout = Mage::getSingleton('core/layout');
        $res = '';

        $_product = Mage::registry('current_product');

        $layout->getUpdate()->addHandle('ajax_configurable_options');
        
        if ($_product->getTypeId() == 'bundle')
        $layout->getUpdate()->addHandle('ajax_bundle_options');        

        // set unique cache ID to bypass caching
        $cacheId = 'LAYOUT_'.Mage::app()->getStore()->getId().md5(join('__', $layout->getUpdate()->getHandles()));
        $layout->getUpdate()->setCacheId($cacheId);

        $layout->getUpdate()->load();
        $layout->generateXml();
        $layout->generateBlocks();
        
        $value = $layout->getBlock('ajax.configurable.options');        
        
        if ($value) {
            $res .= $value->toHtml();
        }
        
        if ($_product->getTypeId() == 'bundle') {
            $value = $layout->getBlock('product.info.bundle');        
            
            if ($value) {
                $res .= $value->toHtml();
            }
        }
        
        if (!empty($res)) {
            $_response->setConfigurableOptionsBlock($res);
        }
    }

    public function addGroupProductItemsBlock(&$_response) {
        $layout = Mage::getSingleton('core/layout');
        $res = '';


        $layout->getUpdate()->addHandle('ajax_grouped_options');

        // set unique cache ID to bypass caching
        $cacheId = 'LAYOUT_'.Mage::app()->getStore()->getId().md5(join('__', $layout->getUpdate()->getHandles()));
        $layout->getUpdate()->setCacheId($cacheId);

        $layout->getUpdate()->load();
        $layout->generateXml();
        $layout->generateBlocks();

        $value = $layout->getBlock('ajax.grouped.options');

        if ($value) {
            $res .= $value->toHtml();
        }

        if (!empty($res)) {
            $_response->setConfigurableOptionsBlock($res);
        }
    }
    

    public function showProductOptions(&$_response) {
        $layout = Mage::getSingleton('core/layout');
        $res = '';
        

        $layout->getUpdate()->addHandle('ajax_index_options');

        // set unique cache ID to bypass caching
        $cacheId = 'LAYOUT_'.Mage::app()->getStore()->getId().md5(join('__', $layout->getUpdate()->getHandles()));
        $layout->getUpdate()->setCacheId($cacheId);

        $layout->getUpdate()->load();
        $layout->generateXml();
        $layout->generateBlocks();

        $value = $layout->getBlock('product.info');

        if ($value) {
            $res .= $value->toHtml();
        }

        if (!empty($res)) {
            $_response->setProductOptionsBlock($res);
        }
    }
}