<?php

/**
 * CamelThemes Ajax 
 * 
 * @category     CamelThemes
 * @package      CamelThemes_Ajax 
 * @Class        CamelThemes_Ajax_IndexController
 */ 

require_once 'Mage/Checkout/controllers/CartController.php';

class CamelThemes_Ajax_IndexController extends Mage_Checkout_CartController {

    /**
     * Add product to cart
     */    
    public function addAction()
    {
        $cart   = $this->_getCart();
        $params = $this->getRequest()->getParams();

        if ($params['isAjax'] == 1) {

            $response = array();

            try {

                if (isset($params['qty'])) {
                    $filter = new Zend_Filter_LocalizedToNormalized(
                        array('locale' => Mage::app()->getLocale()->getLocaleCode())
                    );
                    $params['qty'] = $filter->filter($params['qty']);
                }
 
                $product = $this->_initProduct();
                $related = $this->getRequest()->getParam('related_product');
 
                /**
                 * Check product availability
                 */
                if (!$product) {
                    $_response = Mage::getModel('ajax/response');
                    $_response->setError(true);
                    $_response->setMessage($this->__('Unable to find Product ID'));                    
                    $_response->send();
                }
 
                $cart->addProduct($product, $params);
                if (!empty($related)) {
                    $cart->addProductsByIds(explode(',', $related));
                }
 
                $cart->save();
 
                $this->_getSession()->setCartWasUpdated(true);
 
                /**
                 * @todo remove wishlist observer processAddToCart
                 */
                Mage::dispatchEvent('checkout_cart_add_product_complete',
                    array('product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse())
                );
 
                if (!$cart->getQuote()->getHasError()){

                    $_response = Mage::getModel('ajax/response');
                    $_response->setMessage($this->__('%s was added to your shopping cart.', Mage::helper('core')->escapeHtml($product->getName())));

                    //append updated blocks
                    $this->getLayout()->getUpdate()->addHandle('ajax');
                    $this->loadLayout();

                    $_response->addUpdatedBlocks($_response);
                    $_response->send();
                    
                }

            } catch (Mage_Core_Exception $e) {

                $msg = "";
                if ($this->_getSession()->getUseNotice(true)) {
                    $msg = $e->getMessage();
                } else {
                    $messages = array_unique(explode("\n", $e->getMessage()));
                    foreach ($messages as $message) {
                        $msg .= $message.'<br/>';
                    }
                }

                $_response = Mage::getModel('ajax/response');
                $_response->setError(true);
                $_response->setMessage($this->__($msg));                    
                $_response->send();

            } catch (Exception $e) {

                $_response = Mage::getModel('ajax/response');
                $_response->setError(true);
                $_response->setMessage($this->__('Cannot add the item to shopping cart.'));                    
                $_response->send();

                Mage::logException($e);

            }

            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
            return;

        }else{
            return parent::addAction();
        }
    }



    /**
     * Update product configuration for a cart item
     */
    public function updateItemOptionsAction()
    {
        $cart   = $this->_getCart();
        $id = (int) $this->getRequest()->getParam('id');
        $params = $this->getRequest()->getParams();

        if (!isset($params['options'])) {
            $params['options'] = array();
        }
        try {
            if (isset($params['qty'])) {
                $filter = new Zend_Filter_LocalizedToNormalized(
                    array('locale' => Mage::app()->getLocale()->getLocaleCode())
                );
                $params['qty'] = $filter->filter($params['qty']);
            }

            $quoteItem = $cart->getQuote()->getItemById($id);

            if (!$quoteItem) {
                $_response = Mage::getModel('ajax/response');
                $_response->setError(true);
                $_response->setMessage($this->__('Quote item is not found.'));
                $_response->send(); 
            }

            $item = $cart->updateItem($id, new Varien_Object($params));
            if (is_string($item)) {
                Mage::throwException($item);
            }
            if ($item->getHasError()) {
                Mage::throwException($item->getMessage());
            }

            $related = $this->getRequest()->getParam('related_product');
            if (!empty($related)) {
                $cart->addProductsByIds(explode(',', $related));
            }

            $cart->save();

            $this->_getSession()->setCartWasUpdated(true);

            if (!$this->_getSession()->getNoCartRedirect(true)) {
                if (!$cart->getQuote()->getHasError()) {                    

                    $_response = Mage::getModel('ajax/response');

                    $_response->setMessage($this->__('%s was updated in your shopping cart.', Mage::helper('core')->escapeHtml($item->getProduct()->getName())));
                    
                    //update blocks
                    $this->getLayout()->getUpdate()->addHandle('ajax');
                    $this->loadLayout();

                    $_response->addUpdatedBlocks($_response);
                    $_response->send();                   

                }
                $this->_goBack();
            }

            $this->_redirect(Mage::helper('checkout/cart')->getCartUrl());

        } catch (Mage_Core_Exception $e) {

            if ($this->_getSession()->getUseNotice(true)) {
                $this->_getSession()->addNotice($e->getMessage());
            } else {
                $messages = array_unique(explode("\n", $e->getMessage()));
                foreach ($messages as $message) {
                    $this->_getSession()->addError($message);
                }
            }

            $url = $this->_getSession()->getRedirectUrl(true);

            if ($url) {
                $this->getResponse()->setRedirect($url);
            } else {
                $this->_redirectReferer(Mage::helper('checkout/cart')->getCartUrl());
            }

        } catch (Exception $e) {
            $this->_getSession()->addException($e, $this->__('Cannot update the item.'));
            Mage::logException($e);
            $this->_goBack();
        }

        $this->_redirect('*/*');
    }
    

    /**
     * Show quickview for product before adding it to cart
     */
    public function optionsAction(){

        $productId = $this->getRequest()->getParam('product_id');
        // Prepare helper and params
        $viewHelper = Mage::helper('catalog/product_view');
 
        $params = new Varien_Object();
        $params->setCategoryId(false);
        $params->setSpecifyOptions(false);
 
        // Render page
        try {
            
            $_response = Mage::getModel('ajax/response');
            $viewHelper->prepareAndRender($productId, $this, $params);           
            $_response->showProductOptions($_response);            
            $_response->send();

        } catch (Exception $e) {
            if ($e->getCode() == $viewHelper->ERR_NO_PRODUCT_LOADED) {
                if (isset($_GET['store'])  && !$this->getResponse()->isRedirect()) {
                    $this->_redirect('');
                } elseif (!$this->getResponse()->isRedirect()) {
                    $this->_forward('noRoute');
                }
            } else {
                Mage::logException($e);
                $this->_forward('noRoute');
            }
        }       
    }

    /**
     * Wishlist dependency
     */    
    protected function _getWishlist($wishlistId = null)
    {
        $wishlist = Mage::registry('wishlist');
        if ($wishlist) {
            return $wishlist;
        }
        try {
            if (!$wishlistId) {
                $wishlistId = $this->getRequest()->getParam('wishlist_id');
            }
            $customerId = Mage::getSingleton('customer/session')->getCustomerId();
            $wishlist = Mage::getModel('wishlist/wishlist');
            
            if ($wishlistId) {
                $wishlist->load($wishlistId);
            } else {
                $wishlist->loadByCustomer($customerId, true);
            }

            if (!$wishlist->getId() || $wishlist->getCustomerId() != $customerId) {
                $wishlist = null;
                Mage::throwException(
                    Mage::helper('wishlist')->__("Requested wishlist doesn't exist")
                );
            }
            
            Mage::register('wishlist', $wishlist);
        } catch (Mage_Core_Exception $e) {
            Mage::getSingleton('wishlist/session')->addError($e->getMessage());
        } catch (Exception $e) {
            Mage::getSingleton('wishlist/session')->addException($e,
            Mage::helper('wishlist')->__('Cannot create wishlist.')
            );
            return false;
        }
 
        return $wishlist;
    }

    /**
     * Add Product to wishlist.
     */    
    public function addwishAction()
    {
 
        $response = array();
        if (!Mage::getStoreConfigFlag('wishlist/general/active')) {
            $_response = Mage::getModel('ajax/response');
            $_response->setError(true);
            $_response->setMessage($this->__('Wishlist Has Been Disabled By Admin'));
            $_response->send();
        }
        if(!Mage::getSingleton('customer/session')->isLoggedIn()){            
            $_response = Mage::getModel('ajax/response');
            $_response->setError(true);
            $_response->setMessage($this->__('Please Login First'));
            $_response->send();
        }
 
        if(empty($response)){
            $session = Mage::getSingleton('customer/session');
            $wishlist = $this->_getWishlist();
            if (!$wishlist) {

                $_response = Mage::getModel('ajax/response');
                $_response->setError(true);
                $_response->setMessage($this->__('Unable to Create Wishlist'));
                $_response->send();

            }else{
 
                $productId = (int) $this->getRequest()->getParam('product');

                if (!$productId) {                   
                    $_response = Mage::getModel('ajax/response');
                    $_response->setError(true);
                    $_response->setMessage($this->__('Product Not Found'));
                    $_response->send();
                }else{ 

                    $product = Mage::getModel('catalog/product')->load($productId);

                    if (!$product->getId() || !$product->isVisibleInCatalog()) {
                        $_response = Mage::getModel('ajax/response');
                        $_response->setError(true);
                        $_response->setMessage($this->__('Cannot specify product.'));
                        $_response->send();
                    }else{
 
                        try {
                            $requestParams = $this->getRequest()->getParams();

                            if ($session->getBeforeWishlistRequest()) {
                                $requestParams = $session->getBeforeWishlistRequest();
                                $session->unsBeforeWishlistRequest();
                            }
                            $buyRequest = new Varien_Object($requestParams);
 
                            $result = $wishlist->addNewItem($product, $buyRequest);
                            if (is_string($result)) {
                                Mage::throwException($result);
                            }
                            $wishlist->save();
 
                            Mage::dispatchEvent(
                                'wishlist_add_product',
                                array(
                                    'wishlist'  => $wishlist,
                                    'product'   => $product,
                                    'item'      => $result
                                )
                            ); 
                            
                            $referer = $session->getBeforeWishlistUrl();
                            if ($referer) {
                                $session->setBeforeWishlistUrl(null);
                            } else {
                                $referer = $this->_getRefererUrl();
                            }
                            $session->setAddActionReferer($referer);
                            
                            Mage::helper('wishlist')->calculate();
                            
                            $message = $this->__('%1$s has been added to your wishlist.',
                            $product->getName(), Mage::helper('core')->escapeUrl($referer));
                            
                             
                            Mage::unregister('wishlist');
 
                            $_response = Mage::getModel('ajax/response');
                            $_response->setMessage($this->__($message));

                            $this->getLayout()->getUpdate()->addHandle('ajax');
                            $this->loadLayout();

                            $_response->addUpdatedBlocks($_response);
                            $_response->send();

                        }
                        catch (Mage_Core_Exception $e) {
                            $_response = Mage::getModel('ajax/response');
                            $_response->setError(true);
                            $_response->setMessage($this->__('An error occurred while adding item to wishlist: %s', $e->getMessage()));
                            $_response->send();
                        }
                        catch (Exception $e) {
                            mage::log($e->getMessage());
                            
                            $_response = Mage::getModel('ajax/response');
                            $_response->setError(true);
                            $_response->setMessage($this->__('An error occurred while adding item to wishlist.'));
                            $_response->send();
                        }
                    }
                }
            }
 
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        return;
    }

    /**
     * Add Products to compare.
     */ 
    public function compareAction(){

        $response = array();
        
        $productId = (int) $this->getRequest()->getParam('product');
        
        if ($productId && (Mage::getSingleton('log/visitor')->getId() || Mage::getSingleton('customer/session')->isLoggedIn())) {
            $product = Mage::getModel('catalog/product')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($productId);
 
            if ($product->getId()/* && !$product->isSuper()*/) {
                Mage::getSingleton('catalog/product_compare_list')->addProduct($product);

                $_response = Mage::getModel('ajax/response');
                $_response->setMessage($this->__('The product %s has been added to comparison list.', Mage::helper('core')->escapeHtml($product->getName())));

                //append updated blocks
                $this->getLayout()->getUpdate()->addHandle('ajax');
                $this->loadLayout();

                $_response->addUpdatedBlocks($_response);
                $_response->send();
                
            }
        }
        //$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        //return;
    }

    /**
     * Add Products to compare.
     */ 
    public function removecompareAction(){

        $response = array();

        $productId = (int) $this->getRequest()->getParam('product');

        $product = Mage::getModel('catalog/product')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($productId);

            if($product->getId()) {
                /** @var $item Mage_Catalog_Model_Product_Compare_Item */
                $item = Mage::getModel('catalog/product_compare_item');
                if(Mage::getSingleton('customer/session')->isLoggedIn()) {
                    $item->addCustomerData(Mage::getSingleton('customer/session')->getCustomer());
                } elseif ($this->_customerId) {
                    $item->addCustomerData(
                        Mage::getModel('customer/customer')->load($this->_customerId)
                    );
                } else {
                    $item->addVisitorId(Mage::getSingleton('log/visitor')->getId());
                }

                $item->loadByProduct($product);

                if($item->getId()) {
                    $item->delete();
                    
                    $_response = Mage::getModel('ajax/response');
                    $_response->setMessage($this->__('The product %s has been removed from comparison list.', $product->getName()));

                    //append updated blocks
                    $this->getLayout()->getUpdate()->addHandle('ajax');
                    $this->loadLayout();

                    $_response->addUpdatedBlocks($_response);
                    $_response->send();

                    Mage::dispatchEvent('catalog_product_compare_remove_product', array('product'=>$item));
                    Mage::helper('catalog/product_compare')->calculate();
                }
            }
        
        
    }


    public function deleteAction(){

        $id = (int) $this->getRequest()->getParam('id');


        if ($id) {
            try {
                $this->_getCart()->removeItem($id)
                        ->save();

            } catch (Exception $e) {
                $_response = Mage::getModel('ajax/response');
                $_response->setError(true);
                $_response->setMessage($this->__('Cannot remove the item.'));
                $_response->send();

                Mage::logException($e);
            }
        }
        

        $_response = Mage::getModel('ajax/response');


        $_response->setMessage($this->__('The product has been removed from cart.'));

        //append updated blocks
        $this->getLayout()->getUpdate()->addHandle('ajax');
        $this->loadLayout();


        $_response->addUpdatedBlocks($_response);

        $_response->send();
        
    }

    public function clearcartAction() {

        try {

            $cart = Mage::getSingleton('checkout/cart');
            foreach( Mage::getSingleton('checkout/session')->getQuote()->getItemsCollection() as $item ){
                $cart->removeItem( $item->getId() );
            }
            $cart->save();

        }  catch (Exception $e) {
                $_response = Mage::getModel('ajax/response');
                $_response->setError(true);
                $_response->setMessage($this->__('Cannot clear shopping cart.'));
                $_response->send();

                Mage::logException($e);
        }

        $_response = Mage::getModel('ajax/response');


        $_response->setMessage($this->__('The Shopping Cart has been cleared.'));

        //append updated blocks
        $this->getLayout()->getUpdate()->addHandle('ajax');
        $this->loadLayout();


        $_response->addUpdatedBlocks($_response);

        $_response->send(); 

    }

    public function updatecartAction() {

        
        $params = $this->getRequest()->getParams();


        try {

            $cart = Mage::getSingleton('checkout/cart');
            $items = $cart->getItems();
            
            foreach( Mage::getSingleton('checkout/session')->getQuote()->getItemsCollection() as $item ){
                 if($item->getId()==$params['item']){
                    $item->setQty($params['qty']); // UPDATE ONLY THE QTY, NOTHING ELSE!
                    $cart->save();
                    Mage::getSingleton('checkout/session')->setCartWasUpdated(true);
                }
            }
            
        }catch (Exception $e) {
                $_response = Mage::getModel('ajax/response');
                $_response->setError(true);
                $_response->setMessage($this->__('Cannot clear shopping cart.'));
                $_response->send();

                Mage::logException($e);
        }

        $_response = Mage::getModel('ajax/response');


        $_response->setMessage($this->__('The Shopping Cart updated successfully.'));

        //append updated blocks
        $this->getLayout()->getUpdate()->addHandle('ajax');
        $this->loadLayout();


        $_response->addUpdatedBlocks($_response);

        $_response->send(); 

    }


    /**
     * Initialize coupon
     */
    public function couponAction()
    {


        $couponCode = (string) $this->getRequest()->getParam('coupon');

        if ($this->getRequest()->getParam('remove') == 1) {
            $couponCode = '';
        }
        $oldCouponCode = $this->_getQuote()->getCouponCode();

        if (!strlen($couponCode) && !strlen($oldCouponCode)) {
            $this->_goBack();
            return;
        }

        try {
            $codeLength = strlen($couponCode);
            $isCodeLengthValid = $codeLength && $codeLength <= Mage_Checkout_Helper_Cart::COUPON_CODE_MAX_LENGTH;

            $this->_getQuote()->getShippingAddress()->setCollectShippingRates(true);
            $this->_getQuote()->setCouponCode($isCodeLengthValid ? $couponCode : '')
                ->collectTotals()
                ->save();            

        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
                $_response = Mage::getModel('ajax/response');
                $_response->setError(true);
                $_response->setMessage($this->__('Cannot apply the coupon code.'));
                $_response->send();

                Mage::logException($e);
        }       


        if ($codeLength) {
                if ($isCodeLengthValid && $couponCode == $this->_getQuote()->getCouponCode()) {
                    $_response = Mage::getModel('ajax/response');
                    $_response->setMessage($this->__('Coupon code "%s" was applied.', Mage::helper('core')->escapeHtml($couponCode)));
                    //append updated blocks
                    $this->getLayout()->getUpdate()->addHandle('ajax');
                    $this->loadLayout();

                    $_response->addUpdatedBlocks($_response);
                    $_response->send(); 
                } else { 
                    $_response = Mage::getModel('ajax/response');
                    $_response->setError(true);                   
                    $_response->setMessage($this->__('Coupon code "%s" is not valid.', Mage::helper('core')->escapeHtml($couponCode)));
                    $_response->send();
                }
            } else {
                $_response = Mage::getModel('ajax/response');
                $_response->setMessage($this->__('Coupon code was canceled.'));
                //append updated blocks
                $this->getLayout()->getUpdate()->addHandle('ajax');
                $this->loadLayout();
                $_response->addUpdatedBlocks($_response);
                $_response->send();
        }

    }




}
