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
 * Campaign Observer Model
 * 
 * @category    Magestore
 * @package     Magestore_Campaign
 * @author      Magestore Developer
 */
class Magestore_Campaign_Model_Observer
{
    /**
     * process controller_action_predispatch event
     *
     * @return Magestore_Campaign_Model_Observer
     */
    public function controllerActionPredispatch($observer)
    {
        $action = $observer->getEvent()->getControllerAction();
        return $this;
    }


    public function subscribedToNewsletter(Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent();
        $subscriber = $event->getDataObject();
        $data = $subscriber->getData();
        $statusChange = $subscriber->getIsStatusChanged();

        // Trigger if user is now subscribed and there has been a status change:
        if ($data['subscriber_status'] == "1" && $statusChange == true) {
            $name = 'popupcampaign';
            $customer_cookie = Mage::getModel('core/cookie')->get($name);
            $popupid = substr ($customer_cookie, 5);
            $model_popup = Mage::getModel('campaign/popup')->load($popupid);
            $campaignid = $model_popup->getCampaignId();
            $subscriber->setCampaignId($campaignid);
            $subscriber->save();
        }
        return $observer;
    }


    /**
     * update final price product for get add to cart and checkout
     * @param $observer
     * @return $this
     */
//    public function catalogGetFinalPrice($observer){
//        $product = $observer->getEvent()->getProduct();
//        $orinalPrice = $product->getFinalPrice();
//        $campaign = Mage::getModel('campaign/campaign')->getActiveCampaign(); //only one campaign is effect to product
//        $countdown = $campaign->getCountdown();
//        if($countdown->getStatus() != 1){
//            return $this; //countdown when disabled
//        }
//        $productsCountdown = explode(',', $countdown->getProductId());
//        $localeCode = Mage::getStoreConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_LOCALE);
//        $tz_code = Mage::getStoreConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_TIMEZONE);
//        $start_date = new Zend_Date($campaign->getStartTime());
//        $end_date = new Zend_Date($campaign->getEndTime());
//        $gmt_date = Mage::getSingleton('core/date')->gmtDate();
//        $today = new Zend_Date($gmt_date);
//        $today->setLocale($localeCode)
//            ->setTimezone($tz_code); //add current locate
//        $currDate = new DateTime($today->toString('yyyy-MM-d H:m:s'));
//        $starDate = new DateTime($start_date->toString('yyyy-MM-d H:m:s'));
//        $endDate = new DateTime($end_date->toString('yyyy-MM-d H:m:s'));
//        $priceStart = (float) $countdown->getPriceStart();
//        $downPrice = (float) $countdown->getDownPrice();
//        $diff = (int) $currDate->diff($starDate)->days;
//        if($currDate->getTimestamp() >= $starDate->getTimestamp() &&
//            $currDate->getTimestamp() <= $endDate->getTimestamp() &&
//            $diff >= 0 &&
//            $countdown->getTypeCountdown() == Magestore_Campaign_Model_Countdown_Type::PRICE &&
//            $priceStart > 0
//        ) {
//            //check accept only product set in countdown
//            if (in_array($product->getId(), $productsCountdown)
//            ) {
//                //$price = $product->getFinalPrice();
//                if ($downPrice) {
//                    //add down price to final price with day x nth
//                    $finalPriceToSet = (float)$priceStart + ($diff * (float)$downPrice);
//                    if($finalPriceToSet < $orinalPrice){
//                        $product->setFinalPrice($finalPriceToSet);
//                    }
//                } else {
//                    // with price start only
//                    $product->setFinalPrice((float)$priceStart); //set price start for product day 1
//                }
//            }
//        }
//
//
//        return $this;
//    }

    /**
     * update price product on listing page
     * @param $observer
     */
//    public function catalogBlockProductListCollection($observer){
//        $products = $observer->getEvent()->getCollection();
//        $campaign = Mage::getModel('campaign/campaign')->getActiveCampaign(); //only one campaign is effect to product
//        $countdown = $campaign->getCountdown();
//        if($countdown->getStatus() != 1){
//            return $this; //countdown when disabled
//        }
//        $productsCountdown = explode(',', $countdown->getProductId());
//        $localeCode = Mage::getStoreConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_LOCALE);
//        $tz_code = Mage::getStoreConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_TIMEZONE);
//        $start_date = new Zend_Date($campaign->getStartTime());
//        $end_date = new Zend_Date($campaign->getEndTime());
//        $gmt_date = Mage::getSingleton('core/date')->gmtDate();
//        $today = new Zend_Date($gmt_date);
//        $today->setLocale($localeCode)
//            ->setTimezone($tz_code); //add current locate
//        $currDate = new DateTime($today->toString('yyyy-MM-d H:m:s'));
//        $starDate = new DateTime($start_date->toString('yyyy-MM-d H:m:s'));
//        $endDate = new DateTime($end_date->toString('yyyy-MM-d H:m:s'));
//        $priceStart = (float) $countdown->getPriceStart();
//        $downPrice = (float) $countdown->getDownPrice();
//        $diff = (int) $currDate->diff($starDate)->days;
//        if($currDate->getTimestamp() >= $starDate->getTimestamp() &&
//            $currDate->getTimestamp() <= $endDate->getTimestamp() &&
//            $diff >= 0 &&
//            $countdown->getTypeCountdown() == Magestore_Campaign_Model_Countdown_Type::PRICE &&
//            $priceStart > 0
//        ) {
//            foreach ($products as $product) {
//                //check accept only product set in countdown
//                if (in_array($product->getId(), $productsCountdown)
//                ) {
//                    //$price = $product->getFinalPrice();
//                    if ($downPrice) {
//                        //add down price to final price with day x nth
//                        $orinalPrice = $product->getFinalPrice();
//                        $finalPriceToSet = (float)$priceStart + ($diff * (float)$downPrice);
//                        if($finalPriceToSet < $orinalPrice){
//                            $product->setFinalPrice($finalPriceToSet);
//                        }
//                    } else {
//                        // with price start only
//                        $product->setFinalPrice((float)$priceStart); //set price start for product day 1
//                    }
//                }
//            }
//        }
//        return $this;
//    }
}