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
    const CMS_PREFIX = 'super_campaign';

    public function _construct()
    {
        parent::_construct();
        $this->_init('campaign/campaign');
        $this->_headertext = Mage::getModel('campaign/headertext')
            ->load($this->getId(), 'campaign_id');
    }

    public function getCmsPrefix(){
        return self::CMS_PREFIX;
    }

    /**
     * get model popup
     * @param string $type
     * @return Magestore_Campaign_Model_Popup
     */
    public function getPopup($type = ''){
        if($this->getId() == null){ //if create new campaign
            $this->_popup = new Magestore_Campaign_Model_Popup();
        }
        if(!$this->_popup){
            $this->_popup = Mage::getModel('campaign/popup');
            $this->_popup->load($this->getId(), 'campaign_id');
            if($type){
                $this->_popup->reloadModelType($type);
            }
        }
        if( !($this->_popup instanceof Magestore_Campaign_Model_Popup) ){
            $this->_popup = new Magestore_Campaign_Model_Popup();
        }
        $this->_popup->setCampaign($this);
        return $this->_popup;
    }

    public function getSidebar(){
        if(!$this->_sidebar){
            $this->_sidebar = Mage::getModel('campaign/sidebar');
            $this->_sidebar->load($this->getId(), 'campaign_id');
        }
        if( !($this->_sidebar instanceof Magestore_Campaign_Model_Sidebar) ){
            $this->_sidebar = new Magestore_Campaign_Model_Sidebar();
        }
        $this->_sidebar->setCampaign($this);
        return $this->_sidebar;
    }

    /**
     * get model header text
     * @return Magestore_Campaign_Model_Headertext
     */
    public function getHeadertext(){
        if(!($this->_headertext instanceof Magestore_Campaign_Model_Headertext) || !$this->_headertext->getId()){
            $this->_headertext = Mage::getModel('campaign/headertext');
            $this->_headertext->load($this->getId(), 'campaign_id');
        }
        if( !($this->_headertext instanceof Magestore_Campaign_Model_Headertext) ){
            $this->_headertext = new Magestore_Campaign_Model_Headertext();
        }
        $this->_headertext->setCampaign($this);
        return $this->_headertext;
    }

    /**
     * zeus get model banner listing page
     * @return Magestore_Campaign_Model_Bannerlistpage
     */
    public function getBannerlistpage(){
        if(!($this->_bannerlistpage instanceof Magestore_Campaign_Model_Bannerlistpage) || !$this->_bannerlistpage->getId()){
            $this->_bannerlistpage = Mage::getModel('campaign/bannerlistpage');
            $this->_bannerlistpage->load($this->getId(), 'campaign_id');
        }
        if( !($this->_bannerlistpage instanceof Magestore_Campaign_Model_Bannerlistpage) ){
            $this->_bannerlistpage = new Magestore_Campaign_Model_Bannerlistpage();
        }
        $this->_bannerlistpage->setCampaign($this);
        return $this->_bannerlistpage;
    }

    /**
     * zeus get model banner menu
     * @return Magestore_Campaign_Model_Bannermenu
     */
    public function getBannermenu(){
        if(!($this->_bannermenu instanceof Magestore_Campaign_Model_Bannermenu) || !$this->_bannermenu->getId()){
            $this->_bannermenu = Mage::getModel('campaign/bannermenu');
            $this->_bannermenu->load($this->getId(), 'campaign_id');
        }
        if( !($this->_bannermenu instanceof Magestore_Campaign_Model_Bannermenu) ){
            $this->_bannermenu = new Magestore_Campaign_Model_Bannermenu();
        }
        $this->_bannermenu->setCampaign($this);
        return $this->_bannermenu;
    }


    /**
     * zeus get model banner homepage
     * @return Magestore_Campaign_Model_Bannerhomepage
     */
    public function getBannerhomepage(){
        if(!($this->_bannerhomepage instanceof Magestore_Campaign_Model_Bannerhomepage) ||
            !$this->_bannerhomepage->getId()){
            $this->_bannerhomepage = Mage::getModel('campaign/bannerhomepage');
            $this->_bannerhomepage->load($this->getId(), 'campaign_id');
        }
        if( !($this->_bannerhomepage instanceof Magestore_Campaign_Model_Bannerhomepage) ){
            $this->_bannerhomepage = new Magestore_Campaign_Model_Bannerhomepage();
        }
        $this->_bannerhomepage->setCampaign($this);
        return $this->_bannerhomepage;
    }


    /**
     * zeus get model countdown
     * @return Magestore_Campaign_Model_Countdown
     */
    public function getCountdown(){
        if(!($this->_countdown instanceof Magestore_Campaign_Model_Countdown) || !$this->_countdown->getId()){
            $this->_countdown = Mage::getModel('campaign/countdown');
            $this->_countdown->load($this->getId(), 'campaign_id');
        }
        if( !($this->_countdown instanceof Magestore_Campaign_Model_Countdown) ){
            $this->_countdown = new Magestore_Campaign_Model_Countdown();
        }
        $this->_countdown->setCampaign($this);
        return $this->_countdown;
    }



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
        if($this->getEndTime() == null && $this->getId() == null){
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
        $this->getPopup()->delete(); //delete relationship models
        $this->getHeadertext()->delete();
        $this->getCountdown()->delete();
        $this->getSidebar()->delete();
        $this->getBannerhomepage()->delete();
        $this->getBannerlistpage()->delete();
        $this->getBannermenu()->delete();
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

}
