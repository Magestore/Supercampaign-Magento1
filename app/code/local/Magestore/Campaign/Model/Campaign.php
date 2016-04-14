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
include_once('lib/Mobile_Detect.php');
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
        //auto add priority
        if($this->getPriority() == null){
            $this->setPriority($this->getPriorityIncrement());
        }
    }

    public function getPriorityIncrement(){
        $highest = $this->getCollection();
        $highest->getSelect()->order('priority DESC');
        $highest = $highest->getFirstItem();
        return (int) $highest->getPriority() + 1;
    }

    /**
     * @param $date
     * @param string $format datetime format
     * @return mixed
     */
    public function toUTCTimezone($date, $format = null){
        $timezone = new DateTimeZone(Mage::getStoreConfig(
            Mage_Core_Model_Locale::XML_PATH_DEFAULT_TIMEZONE));
        if(!preg_match('/^(\s*)?\d{4}-\d{2}-\d{2}(\s{1})?(\d{2}:\d{2}:\d{2})?$/', $date)){
            $date = '';
        }
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
        if(!preg_match('/^(\s*)?\d{4}-\d{2}-\d{2}(\s{1})?(\d{2}:\d{2}:\d{2})?$/', $date)){
            $date = '';
        }
        $date = new DateTime($date, $utcZone);
        $localeZone = new DateTimeZone(Mage::getStoreConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_TIMEZONE));
        $date->setTimezone($localeZone);
        if($format == null){
            $format = 'Y-m-d H:i:s';
        }
        return $date->format($format);
    }

//    /**
//     * will remove in future
//     */
//    private function _addTimezone($date, $locale = null, $format = null){
//        $datetime = new Zend_Date($date);
//        if($locale == null){
//            $locale = Mage::getStoreConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_LOCALE);
//        }
//        if($format == null){
//            $format = 'y-MM-dd HH:mm:00';
//        }
//        $datetime->setLocale($locale)
//            ->setTimezone(Mage::getStoreConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_TIMEZONE));
//        return $datetime->get($format);
//    }
//
//    /**
//     * revert to save with UTC
//     * @param $date
//     * @param null $format
//     * @return mixed
//     */
//    private function _revertTimezone($date, $format = null){
//        $timezone = new DateTimeZone(Mage::getStoreConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_TIMEZONE));
//        $date = new DateTime($date, $timezone);
//        $utcZone = new DateTimeZone('UTC');
//        $date->setTimezone($utcZone);
//        if($format == null){
//            $format = 'Y-m-d H:i:s';
//        }
//        return $date->format($format);
//    }

//    protected function _beforeDelete(){
//        $this->getPopup()->delete(); //delete relationship models
//        $this->getHeadertext()->delete();
//        $this->getCountdown()->delete();
//        $this->getSidebar()->delete();
//        $this->getBannerhomepage()->delete();
//        $this->getBannerlistpage()->delete();
//        $this->getBannermenu()->delete();
//    }

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
            if($this->getData('coupon_code_type') == 'static'){
                $this->_coupon_code = $this->getData('coupon_code');
                return $this->_coupon_code;
            }elseif($this->getData('coupon_code_type') == 'promotion'){
                $promoQuoteId = $this->getData('promo_rule_id');
                $promo = Mage::getModel('salesrule/rule')->load($promoQuoteId);
                if($promo->getCouponType() == Mage_SalesRule_Model_Rule::COUPON_TYPE_SPECIFIC
                    && !$promo->getData('use_auto_generation')){
                    $this->_coupon_code = $promo->getCouponCode();
                    return $this->_coupon_code;
                }elseif($promo->getCouponType() == Mage_SalesRule_Model_Rule::COUPON_TYPE_SPECIFIC
                        && $promo->getData('use_auto_generation')){
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
            if($this->getUserIp() != ''){
                if($this->checkUserIP()){
                    $popups[] = $item;
                }
                continue;
            }
            if($item->checkShowFrequency()
                && $item->checkShowOnPage()
                && $item->checkExclude()
                && $item->checkFormSuccess()
                && $this->checkDevices()
                && $this->checkUserLogin()
                && $this->checkReturnCustomer()
                && $this->checkCustomerGroup()
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

    /*Functions below for Visitorsegment*/
    //z set visitorsegment check value
    public function checkDevices(){
        $detector = new Mobile_Detect();
        $devicetoshow = array();
        $devices = $this->getDevices();
        if($devices != ''){
            if(!is_array($devices)){
                $devicetoshow[] = $devices;
            }else{
                $devicetoshow = $devices;
            }
            //explode in array
            $sub_device = array();
            foreach ($devicetoshow as $subgr) {
                if(in_array(trim($subgr), $devicetoshow)){
                    $sub_device[] = explode(',', trim($subgr));
                }
            }
        }else{
            return false;
        }
        //end get value of device
        if($devices != ''){
            $tablet = $detector->isTablet();
            $mobile = $detector->isMobile();

            foreach($sub_device as $subg){
                foreach($subg as $sub){
                    if($sub == 'all_device'){
                        return true;
                    }
                    if($sub == 'pc_laptop'){
                        if($tablet == false && $mobile == false){
                            return true;
                        }
                    }
                    if($sub == 'tablet_mobile'){
                        if($tablet || $mobile){
                            return true;
                        }
                    }
                }
            }

            return false;
        }else{
            return false;
        }
    }

    //z set visitorsegment check user login
    public function checkUserLogin(){
        $user = $this->getLoginUser();
        if($user != ''){
            //if all user
            if($user == 'all_user'){
                return true;
            }else{
                //if registed or loged
                $login = Mage::getSingleton('customer/session')->isLoggedIn(); //Check if User is Logged In
                if($user == 'registed_loged'){
                    if($login){
                        return true;
                    }
                }
                //if register or logout
                if($user == 'logout_not_register'){
                    if($login == false){
                        return true;
                    }
                }
            }

            return false;
        }else{
            return false;
        }
    }


    /**
     * option show with new user or visited user
     * @return bool
     */
    //check enable cookie
    public function enableCookie(){
        return true; //cookie alway enabled
        $enable = $this->getCookiesEnabled();
        if($enable != ''){
            if($enable == 1){
                $this->checkReturnCustomer();
            }else{
                return false;
            }
        }
    }

    //z set visitorsegment check return customer
    public function checkReturnCustomer(){

        $login = Mage::getSingleton('customer/session')->isLoggedIn();
        if (Mage::getSingleton('customer/session')->isLoggedIn()) {

            $customer = Mage::getSingleton('customer/session')->getCustomer();
            $customer_name = $customer->getName();
        }
        $ipcustomer = Mage::helper('core/http')->getRemoteAddr();
        //$popupid = $this->getPopupId();
        $camPaignid = $this->getCampaignId();
        $getReturn = $this->getReturningUser();
        $cookiepopup = $this->getCookieTime();

        $fixip = str_replace(".","_",$ipcustomer);
        $customer_cookie = Mage::getSingleton('core/cookie')->get($fixip);
        $allcookie = Mage::getModel('core/cookie')->get();

        // if empty cookie time
        if($cookiepopup == '' || $cookiepopup < 1){
            return true;
        }

        //check cookie customer
        if($customer_cookie) {
            if($getReturn == 'alluser'){
                return true;
            }
            if($getReturn == 'new'){
                return false;
            }
            if($getReturn == 'return'){
                return true;
            }
        }
        //set cookie for new customer
        if(!$customer_cookie) {
            if($ipcustomer){
                //set cookie for customer
                $name = $ipcustomer;
                $value = $camPaignid;
                $period = $cookiepopup * 86400;
                Mage::getModel('core/cookie')->set($name, $value, $period);
            }
            return true;
        }
    }


    /**
     * option show or not with current customer's group
     * @return bool
     */
    public function checkCustomerGroup(){
        $grouptoshow = array();
        $group = $this->getCustomerGroupIds();
        if($group == ','){return true;}
        //call group code of group customer
        $login = Mage::getSingleton('customer/session')->isLoggedIn();
        $gid = Mage::getSingleton('customer/session')->getCustomerGroupId();
        $groupcustomer = Mage::getModel('customer/group')->load($gid);
        $groupcode = $groupcustomer->getCustomerGroupCode();
        if($group != ''){
            if(!is_array($group)){
                $grouptoshow[] = $group;
            }else{
                $grouptoshow = $group;
            }
            //explode in array
            $sub_group = array();
            foreach ($grouptoshow as $subgr) {
                if(in_array(trim($subgr), $grouptoshow)){
                    $sub_group[] = explode(',', trim($subgr));
                }
            }
        }
        //end get value of customer group
        if($group != ''){
            foreach($sub_group as $subg){
                foreach($subg as $sub){
                    if($sub ==''){return true;}

                        if($groupcode == $sub){
                            return true;
                        }
                }
            }
            return false;
        }
    }


    public function checkUserIP(){
        $ipcustomer = Mage::helper('core/http')->getRemoteAddr();
        $ipdata = $this->getUserIp();
        if($ipdata != ''){
            if($ipdata == $ipcustomer){
                return true;
            }
        }
        return false;
    }
    /*End for check visitorsegment*/

}
