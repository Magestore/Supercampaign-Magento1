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
 * Campaign Model
 * 
 * @category    Magestore
 * @package     Magestore_Campaign
 * @author      Magestore Developer
 */
class Magestore_Campaign_Model_Popup_Type_Game_Player extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('campaign/popup_type_game_player');
    }

    public function getPlayerByMaillist($id){
        $collection = $this->getCollection();
        $collection->addFieldToFilter('maillist_id', $id);
        return $collection->getFirstItem();
    }

    public function saveEmail($email, $name = ''){
        try{
            $this->_maillist = Mage::getModel('campaign/maillist')
                ->setCampaignId($this->getCampaignId())
                ->setEmail($email)
                ->setName($name)
                ->setUsedCoupon(0)
                ->setCouponCode($this->getPoints())
                ->setStartTime(Mage::getModel('core/date')->gmtDate())
                ->setExpiredTime($this->getData('expired_date')) //date from end_time campaign
                ->save();
            $this->setMaillistId($this->_maillist->getId()); //set maillist id foreign key
        }catch (Exception $e){
            return false;
        }
        return true;
    }


    public function findCustomerByEmail($customer_email)
    {
        $customer = Mage::getModel("customer/customer");
        $customer->setWebsiteId(Mage::app()->getWebsite()->getId());
        $customer->loadByEmail($customer_email);
        if ($customer->getId())
            return $customer;
        else
            return null;
    }

    public function createCustomer($email, $name)
    {
        $this->setIsNewCustomer(true);
        $websiteId = Mage::app()->getWebsite()->getId();
        $store = Mage::app()->getStore();
        $customer = Mage::getModel("customer/customer");
        $customer->setWebsiteId($websiteId)
            ->setStore($store)
            ->setFirstname($name)
            ->setLastname($name)
            ->setEmail($email)
            ->setPassword($customer->generatePassword(8));
        try {
            $customer->save();
            //$customer->sendNewAccountEmail();
            $customer->setConfirmation(null);
            $customer->setStatus(1);
            $customer->save();
            return $customer;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * send email to customer when playing game is complete
     * vars store, customer, first_name
     * character_name, money_received
     * points_received, points_expired
     * email, password
     * @param $game
     * @param $customer
     */
    public function sendEmail($customer){
        $store = Mage::app()->getStore($customer->getStoreId());
        if(Mage::getStoreConfig('campaign/email/enabled', $store)){
            $translate = Mage::getSingleton('core/translate');
            $translate->setTranslateInline(false);
            if($customer->getIsNewCustomer()) {
                //when Email is new customer
                $template_id = Mage::getStoreConfig('campaign/email/new_customer', $store);
            }else {
                $template_id = Mage::getStoreConfig('campaign/email/old_customer', $store);
            }
            Mage::getModel('core/email_template')
                ->setDesignConfig(array(
                    'area'  => 'frontend',
                    'store' => $store->getId()
                ))
                ->sendTransactional(
                    $template_id, //template email id
                    Mage::getStoreConfig('rewardpoints/email/sender', $store), //user sender config rewardpoints
                    $this->getEmail(),
                    $this->getName(),
                    array(
                        'store'     => $store,
                        'customer'  => $customer,
                        'first_name' => $customer->getFirstname(),
                        'character_name' => $this->getCharacterName(),
                        'money_received' => $this->getPoints(),
                        'points_received' => $this->getPoints(),
                        'points_expired' => Mage::helper('core')->formatDate($this->getData('expired_date'), 'medium', true),
                        'email' => $this->getEmail(),
                        'password' => $customer->getPassword()
                    )
                );
            $translate->setTranslateInline(true);
        }
    }
}