<?php
/**
 * Magestore
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Magestore
 * @package     Magestore_Campaign
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

/**
 * Campaign Index Controller
 *
 * @category    Magestore
 * @package     Magestore_Campaign
 * @author      Magestore Developer
 */
class Magestore_Campaign_AjaxformController extends Mage_Core_Controller_Front_Action
{
    public function subcriberAction(){
        $helper = Mage::helper('campaign');
        $popupId = $this->getRequest()->getParam('popup_id');
        $popup = Mage::getModel('campaign/popup')->load($popupId);
        $result = array('status'=>0, 'message'=>'');
        if ($this->getRequest()->isPost() && $this->getRequest()->getPost('email')) {
            $customerSession    = Mage::getSingleton('customer/session');
            $email              = (string) $this->getRequest()->getPost('email');

            try {
                if (!Zend_Validate::is($email, 'EmailAddress')) {
                    Mage::throwException($this->__('Please enter a valid email address.'));
                }

                if (Mage::getStoreConfig(Mage_Newsletter_Model_Subscriber::XML_PATH_ALLOW_GUEST_SUBSCRIBE_FLAG) != 1 &&
                    !$customerSession->isLoggedIn()) {
                    Mage::throwException($this->__('Sorry, but administrator denied subscription for guests. Please <a href="%s">register</a>.', Mage::helper('customer')->getRegisterUrl()));
                }

                $ownerId = Mage::getModel('customer/customer')
                    ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
                    ->loadByEmail($email)
                    ->getId();
                if ($ownerId !== null && $ownerId != $customerSession->getId()) {
                    Mage::throwException($this->__('This email address is already assigned to another user.'));
                }

                $status = Mage::getModel('newsletter/subscriber')->subscribe($email);
                if ($status == Mage_Newsletter_Model_Subscriber::STATUS_NOT_ACTIVE) {
                    $message = $this->__('Confirmation request has been sent.');
                }
                else {
                    $message = $this->__('Thank you for your subscription.');
                }
                $contentForSuccess = '';
                if($popup->getId()){
                    $contentForSuccess = $helper->convertContentToHtml($popup->getData('content_for_success'));
                    //set cookie to disappear popup
                    $cookie = Mage::getModel('core/cookie');
                    if($popup->getData('cookie_time')){
                        $cookie->set('is_form_success_'.$popupId, 1, $popup->getData('cookie_time'));
                    }else{
                        $cookie->set('is_form_success_'.$popupId, true);
                    }
                }
                $result = array('status'=>1, 'message'=>$message,
                    'content_for_success' => $contentForSuccess
                );
            }
            catch (Exception $e) {
                $result = array('status'=>0,
                    'message'=>$this->__('There was a problem with the subscription: %s', $e->getMessage())
                );
            }
        }else{
            $result = array('status'=>1, 'message'=> $this->__('Only accept method post')
            );
        }
        $json = json_encode($result);
        $this->getResponse()
            ->clearHeaders()
            ->setHeader('Content-Type', 'application/json')
            ->setBody($json);
    }


    /**
     * Create customer account action
     */
    public function registerAction()
    {
        $helper = Mage::helper('campaign');
        $popupId = $this->getRequest()->getParam('popup_id');
        $popup = Mage::getModel('campaign/popup')->load($popupId);
        $result = array('status'=>0, 'message'=>'');
        try{
            $session = Mage::getSingleton('customer/session');
            if ($session->isLoggedIn()) {
                $this->_redirect('*/*/');
                $result['status'] = 0;
                $result['message'] = $this->__('This email address is already assigned to another user.');
                Mage::throwException($this->__('This email address is already assigned to another user.'));
            }
            $session->setEscapeMessages(true); // prevent XSS injection in user input
            if ($this->getRequest()->isPost()) {
                $errors = array();

                if (!$customer = Mage::registry('current_customer')) {
                    $customer = Mage::getModel('customer/customer')->setId(null);
                }

                /* @var $customerForm Mage_Customer_Model_Form */
                $customerForm = Mage::getModel('customer/form');
                $customerForm->setFormCode('customer_account_create')
                    ->setEntity($customer);

                $customerData = $customerForm->extractData($this->getRequest());

                if ($this->getRequest()->getParam('is_subscribed', false)) {
                    $customer->setIsSubscribed(1);
                }

                /**
                 * Initialize customer group id
                 */
                $customer->getGroupId();

                if ($this->getRequest()->getPost('create_address')) {
                    /* @var $address Mage_Customer_Model_Address */
                    $address = Mage::getModel('customer/address');
                    /* @var $addressForm Mage_Customer_Model_Form */
                    $addressForm = Mage::getModel('customer/form');
                    $addressForm->setFormCode('customer_register_address')
                        ->setEntity($address);

                    $addressData    = $addressForm->extractData($this->getRequest(), 'address', false);
                    $addressErrors  = $addressForm->validateData($addressData);
                    if ($addressErrors === true) {
                        $address->setId(null)
                            ->setIsDefaultBilling($this->getRequest()->getParam('default_billing', false))
                            ->setIsDefaultShipping($this->getRequest()->getParam('default_shipping', false));
                        $addressForm->compactData($addressData);
                        $customer->addAddress($address);

                        $addressErrors = $address->validate();
                        if (is_array($addressErrors)) {
                            $errors = array_merge($errors, $addressErrors);
                        }
                    } else {
                        $errors = array_merge($errors, $addressErrors);
                    }
                }

                try {
                    $customerErrors = $customerForm->validateData($customerData);
                    if ($customerErrors !== true) {
                        $errors = array_merge($customerErrors, $errors);
                    } else {
                        $customerForm->compactData($customerData);
                        $customer->setPassword($this->getRequest()->getPost('password'));
                        $customer->setConfirmation($this->getRequest()->getPost('confirmation'));
                        $customerErrors = $customer->validate();
                        if (is_array($customerErrors)) {
                            $errors = array_merge($customerErrors, $errors);
                        }
                    }

                    $validationResult = count($errors) == 0;

                    if (true === $validationResult) {
                        $customer->save();

                        Mage::dispatchEvent('customer_register_success',
                            array('account_controller' => $this, 'customer' => $customer)
                        );

                        if ($customer->isConfirmationRequired()) {
                            $customer->sendNewAccountEmail(
                                'confirmation',
                                $session->getBeforeAuthUrl(),
                                Mage::app()->getStore()->getId()
                            );

                            $result['status'] = 0;
                            $result['message'] = $this->__('Account confirmation is required. Please, check your email for the confirmation link. To resend the confirmation email please <a href="%s">click here</a>.',
                                Mage::helper('customer')->getEmailConfirmationUrl($customer->getEmail()));

                        } else {
                            $session->setCustomerAsLoggedIn($customer);
                            $successUrl = Mage::getUrl('*/*/index', array('_secure'=>true));
                            if ($session->getBeforeAuthUrl()) {
                                $successUrl = $session->getBeforeAuthUrl(true);
                            }
                            $isJustConfirmed = false;
                            $customer->sendNewAccountEmail(
                                $isJustConfirmed ? 'confirmed' : 'registered',
                                '',
                                Mage::app()->getStore()->getId()
                            );
                            $contentForSuccess = '';
                            if($popup->getId()){
                                $contentForSuccess = $helper->convertContentToHtml($popup->getData('content_for_success'));
                                //set cookie to disappear popup
                                $cookie = Mage::getModel('core/cookie');
                                if($popup->getData('cookie_time')){
                                    $cookie->set('is_form_success_'.$popupId, 1, $popup->getData('cookie_time'));
                                }else{
                                    $cookie->set('is_form_success_'.$popupId, true);
                                }
                            }
                            $result = array(
                                'status' => 1,
                                'message' => $this->__('Thank you for registering with %s.', Mage::app()->getStore()->getFrontendName()),
                                'content_for_success' => $contentForSuccess,
                                'redirect_url' => $successUrl
                            );
                        }
                    } else {
                        $session->setCustomerFormData($this->getRequest()->getPost());
                        $result = array(
                            'status' => 0,
                            'message' => $errors
                        );
                    }
                } catch (Mage_Core_Exception $e) {
                    $session->setCustomerFormData($this->getRequest()->getPost());
                    if ($e->getCode() === Mage_Customer_Model_Customer::EXCEPTION_EMAIL_EXISTS) {
                        $url = Mage::getUrl('customer/account/forgotpassword');
                        $message = $this->__('There is already an account with this email address. If you are sure that it is your email address, <a href="%s">click here</a> to get your password and access your account.', $url);
                        $session->setEscapeMessages(false);
                    } else {
                        $message = $e->getMessage();
                    }

                    $result = array(
                        'status' => 0,
                        'message' => $message
                    );

                } catch (Exception $e) {
                    $session->setCustomerFormData($this->getRequest()->getPost());
                    $result = array(
                        'status' => 0,
                        'message' => $this->__('Cannot save the customer.')
                    );
                }
            }

        }catch(Exception $e){
            $result = array(
                'status' => 0,
                'message' => $e->getMessage()
            );
        }

        $json = json_encode($result);
        $this->getResponse()
            ->clearHeaders()
            ->setHeader('Content-Type', 'application/json')
            ->setBody($json);
    }
}

