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
class Magestore_Campaign_Model_Campaign extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('campaign/campaign');
    }


    /**
     * zeus get model countdown
     * @return Magestore_Campaign_Model_Countdown
     */
    /*public function getCountdown(){
        if(!($this->_countdown instanceof Magestore_Campaign_Model_Countdown) || !$this->_countdown->getId()){
            $this->_countdown = Mage::getModel('campaign/countdown');
            $this->_countdown->load($this->getId(), 'campaign_id');
        }
        if( !($this->_countdown instanceof Magestore_Campaign_Model_Countdown) ){
            $this->_countdown = new Magestore_Campaign_Model_Countdown();
        }
        $this->_countdown->setCampaign($this);
        return $this->_countdown;
    }*/



    /**
     * campaign active is campaign have highest priority, minimum time end and greater than current time of each store
     */
    public function getActiveCampaign(){
        $collection = $this->getActiveCampaignAll();
        $campaign = $collection->getFirstItem();
        $campaign->load($campaign->getId()); //load model ro run completely
        return $campaign;
    }

    /**
     * get all campaign is active by priority
     * @return mixed collection campaign
     */
    public function getActiveCampaignAll(){
        $collection = $this->getCollection();
        $store = Mage::app()->getStore();
        $current_date = Mage::getModel('core/date')->gmtDate();
        $collection->addFieldToFilter(array('store','store'), array(array('finset'=>$store->getId()),array('finset'=>0)))
            ->addFieldToFilter('status', '1')
            ->addFieldToFilter('end_time', array('from'=>$current_date))
            ->getSelect()
            ->order('priority DESC')
            ->order('end_time ASC')
        ;
        return $collection;
    }

    public function getStores(){
        if($this->getStore() !== ''){
            return explode(',', trim($this->getStore(), ','));
        }
        return array(0);
    }

    //load start_time with convert timezone
    public function getStartTime(){
        return $this->toLocaleTimezone(parent::getStartTime());
    }

    /**
     * @return string format Y-m-d H:i:s
     */
    public function getEndTime(){
        return $this->toLocaleTimezone(parent::getEndTime());
    }

    //auto convert timezone
    protected function _afterLoad(){
        if($this->getEndTime() == $this->getStartTime() && $this->getId() == null){
            $timestamp = Mage::getModel('core/date')->gmtTimestamp();
            $date = date('Y-m-d H:i:s', $timestamp + 24*60*59);
            $this->setEndTime($date);
        }
    }


    protected function _beforeSave(){
        //auto convert timezone to GMT+0
        //$this->setData('start_time', $this->_revertTimezone(parent::getStartTime()));
        //$this->setData('end_time', $this->_revertTimezone(parent::getEndTime()));
        //auto add priority
        if($this->getPriority() == null){
            $highest = $this->getCollection();
            $highest->getSelect()->order('priority DESC');
            $highest = $highest->getFirstItem();
            $this->setPriority((int) $highest->getPriority() + 1);
        }
    }

    /**
     * @param $date
     * @param string $format datetime format
     * @return mixed
     */
    public function toUTCTimezone($date, $format = null){
        $timezone = new DateTimeZone(Mage::getStoreConfig(
            Mage_Core_Model_Locale::XML_PATH_DEFAULT_TIMEZONE));
        $date = new DateTime($date, $timezone);
        $utcZone = new DateTimeZone('UTC');
        $date->setTimezone($utcZone);
        if($format == null){
            $format = 'Y-m-d H:i:s';
        }
        return $date->format($format);
    }

    /**
     * convert date in UTC to date in Locale timezone
     * @param $date
     * @param null $format
     * @return string
     */
    public function toLocaleTimezone($date, $format = null){
        $utcZone = new DateTimeZone('UTC');
        $date = new DateTime($date, $utcZone);
        $localeZone = new DateTimeZone(Mage::getStoreConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_TIMEZONE));
        $date->setTimezone($localeZone);
        if($format == null){
            $format = 'Y-m-d H:i:s';
        }
        return $date->format($format);
    }

    /**
     * will remove in future
     */
    private function _addTimezone($date, $locale = null, $format = null){
        $datetime = new Zend_Date($date);
        if($locale == null){
            $locale = Mage::getStoreConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_LOCALE);
        }
        if($format == null){
            $format = 'y-MM-dd HH:mm:00';
        }
        $datetime->setLocale($locale)
            ->setTimezone(Mage::getStoreConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_TIMEZONE));
        return $datetime->get($format);
    }

    /**
     * revert to save with UTC
     * @param $date
     * @param null $format
     * @return mixed
     */
    private function _revertTimezone($date, $format = null){
        $timezone = new DateTimeZone(Mage::getStoreConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_TIMEZONE));
        $date = new DateTime($date, $timezone);
        $utcZone = new DateTimeZone('UTC');
        $date->setTimezone($utcZone);
        if($format == null){
            $format = 'Y-m-d H:i:s';
        }
        return $date->format($format);
    }

    protected function _beforeDelete(){
//        $this->getPopup()->delete(); //delete relationship models
//        $this->getHeadertext()->delete();
//        $this->getCountdown()->delete();
//        $this->getSidebar()->delete();
//        $this->getBannerhomepage()->delete();
//        $this->getBannerlistpage()->delete();
//        $this->getBannermenu()->delete();
    }

    //return false = co email roi
    public function saveEmail($name, $email){
        try{
            Mage::getModel('campaign/maillist')
                ->setCampaignId($this->getId())
                ->setEmail($email)
                ->setName($name)
                ->setUsedCoupon(0)
                ->setCouponCode($this->getCouponCode())
                ->setStartTime(Mage::getModel('core/date')->gmtDate())
                ->setExpiredTime($this->getEndTime())
                ->save();
        }catch (Exception $e){
            return false;
        }
        return true;
    }

    /**
     * generate coupon code to save to maillist
     * get coupon code form each email
     * @return string
     */
    public function getCouponCode(){
        if(!$this->_coupon_code){
            if($this->getGiftCodeType() == 'static'){
                $this->_coupon_code = $this->getData('coupon_code');
                return $this->_coupon_code;
            }elseif($this->getGiftCodeType() == 'promotion'){
                $promoQuoteId = $this->getPromoQuoteId();
                $promo = Mage::getModel('salesrule/rule')->load($promoQuoteId);
                if($promo->getCouponType() == Mage_SalesRule_Model_Rule::COUPON_TYPE_SPECIFIC){
                    $this->_coupon_code = $promo->getCouponCode();
                    return $this->_coupon_code;
                }elseif($promo->getCouponType() == Mage_SalesRule_Model_Rule::COUPON_TYPE_AUTO){
                    $coupons = $promo->getCoupons();
                    foreach ($coupons as $coupon) {
                        if($coupon->getTimesUsed() < $coupon->getUsageLimit()){
                            $mails = Mage::getModel('campaign/maillist')->getCollection();
                            $mails->addFieldToFilter('coupon_code', $coupon->getCode());
                            $max = floor($coupon->getUsageLimit()/$coupon->getUsagePerCustomer());
                            if($max < 1){
                                $max = 1;
                            }
                            if($mails->getSize() < $max){
                                $this->_coupon_code = $coupon->getCode();
                                return $this->_coupon_code;
                            }
                        }
                    }
                }else{
                    $this->_coupon_code = '';
                }
            }
        }
        return $this->_coupon_code;
    }

    /**
     * get all campaign
     */
    static public function getCampaignOption(){
        $options = array();
        $campaignCollection = Mage::getModel('campaign/campaign')->getCollection();
        $options[] = array(
            'value'	=> '',
            'label'	=> Mage::helper('campaign')->__('-- Please select campaign --')
        );

        foreach ($campaignCollection as $campaign)
            $options[] = array(
                'value'	=> $campaign->getId(),
                'label'	=> $campaign->getName()
            );
        return $options;
    }

    /**
     * get all popup can show of this campaign
     */
    public function getPopupsAvailable(){
        $popups = array();
        $collection = $this->getPopupCollection();
        foreach ($collection as $item) {
            //priority from highest to lowest
            if($item->checkUserIP()){
                $popups[] = $item;
                continue;
            }
            if($item->checkShowFrequency()
                &&$item->checkShowOnPage()
                && $item->checkDevices()
                && $item->checkUserLogin()
                && $item->checkReturnCustomer()
                && $item->checkCustomerGroup()
            ){
                $popups[] = $item;
                continue;
            }

        }
        return $popups;
    }
    /**
     * get all popup as collection, filter by status and store
     */
    public function getPopupCollection(){
        $store = Mage::app()->getStore();
        $collection = Mage::getModel('campaign/popup')->getCollection();
        $collection->addFieldToFilter('campaign_id', $this->getId());
        $collection->addFieldToFilter('status', Magestore_Campaign_Model_Popup::STATUS_ENABLE);
        $collection->addFieldToFilter(array('store','store'), array(array('finset'=>$store->getId()),array('finset'=>0)));
        $collection->getSelect()
            ->order('priority ASC');
        return $collection;
    }



}
