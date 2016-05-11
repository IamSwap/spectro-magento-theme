<?php

/**
 * CamelThemes Ajax 
 * 
 * @category     CamelThemes
 * @package      CamelThemes_Ajax 
 * @Class        CamelThemes_Ajax_SubscriberController
 */ 

require_once 'Mage/Newsletter/controllers/SubscriberController.php';
class CamelThemes_Ajax_SubscriberController extends Mage_Newsletter_SubscriberController {

    /**
     * Newsletter subscription action
     */
    public function newAjaxAction()
    {
        $response = array();

        $email = (string) $this->getRequest()->getParam('email');

        if ($this->getRequest()->isPost() && $this->getRequest()->getPost('email')) {
            $session            = Mage::getSingleton('core/session');
            $customerSession    = Mage::getSingleton('customer/session');
            $email              = (string) $this->getRequest()->getPost('email');

            try {
                if (!Zend_Validate::is($email, 'EmailAddress')) {
                    $_response = Mage::getModel('ajax/response');
                    $_response->setError(true);
                    $_response->setMessage($this->__('Please enter a valid email address.'));
                    $_response->send();
                }

                if (Mage::getStoreConfig(Mage_Newsletter_Model_Subscriber::XML_PATH_ALLOW_GUEST_SUBSCRIBE_FLAG) != 1 && 
                    !$customerSession->isLoggedIn()) {                    
                    $_response = Mage::getModel('ajax/response');
                    $_response->setError(true);
                    $_response->setMessage($this->__('Sorry, but administrator denied subscription for guests. Please <a href="%s">register</a>.', Mage::helper('customer')->getRegisterUrl()));
                    $_response->send();
                }

                $ownerId = Mage::getModel('customer/customer')
                        ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
                        ->loadByEmail($email)
                        ->getId();
                if ($ownerId !== null && $ownerId != $customerSession->getId()) {
                    $_response = Mage::getModel('ajax/response');
                    $_response->setError(true);
                    $_response->setMessage($this->__('This email address is already assigned to another user.'));
                    $_response->send();
                }

                $status = Mage::getModel('newsletter/subscriber')->subscribe($email);
                if ($status == Mage_Newsletter_Model_Subscriber::STATUS_NOT_ACTIVE) { 
                    $_response = Mage::getModel('ajax/response');
                    $_response->setMessage($this->__('Confirmation request has been sent.'));
                    $_response->send();
                }
                else {                    
                    $_response = Mage::getModel('ajax/response');
                    $_response->setMessage($this->__('Thank you for your subscription.'));
                    $_response->send();
                }
            }
            catch (Mage_Core_Exception $e) {                
                $_response = Mage::getModel('ajax/response');
                $_response->setError(true);
                $_response->setMessage($this->__('There was a problem with the subscription: %s', $e->getMessage()));
                $_response->send();
            }
            catch (Exception $e) {
                $_response = Mage::getModel('ajax/response');
                $_response->setError(true);
                $_response->setMessage($this->__('There was a problem with the subscription.'));
                $_response->send();
            }
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        return;
    }
}