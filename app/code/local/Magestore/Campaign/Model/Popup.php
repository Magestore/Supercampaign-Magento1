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
include('lib/Mobile_Detect.php');
class Magestore_Campaign_Model_Popup extends Mage_Core_Model_Abstract
{
    const STATUS_ENABLE = 1;
    const STATUS_DISABLE = 0;

    const SHOW_ON_PRODUCT_PAGE = 'product';
    const SHOW_ON_CATEGORY_PAGE = 'category';
    const SHOW_ON_CART_PAGE = 'cart_page';
    const SHOW_ON_CHECKOUT_PAGE = 'checkout_page';
    const SHOW_ON_HOME_PAGE = 'home_page';
    const SHOW_ON_URLS_PAGE = 'specified_url';
    const SHOW_ON_ALL_PAGE = 'all_page';
    const SHOW_ON_OTHER_PAGE = 'other_page';

    //show frequency
    const SHOW_FREQUENCY_EVERY_TIME = 'every_time';
    const SHOW_FREQUENCY_ONLY_ONE = 'only_once';
    const SHOW_FREQUENCY_ONLY_TRIGGER = 'all_page';
    const SHOW_FREQUENCY_UNTIL_CLOSE = 'until_close';

    public function _construct()
    {
        parent::_construct();
        $this->_init('campaign/popup');
    }

    /**
     * Return true value is show
     * @return bool
     */
    public function checkShowFrequency(){
        $cookie = Mage::getModel('core/cookie');
        switch($this->getShowingFrequency()){
            case self::SHOW_FREQUENCY_EVERY_TIME:
                return true;

            case self::SHOW_FREQUENCY_ONLY_ONE:
                if($cookie->get('popup_showed_'.$this->getData('popup_id'))){
                    return false;
                }
                break;

            case self::SHOW_FREQUENCY_ONLY_TRIGGER:
                return true;

            case self::SHOW_FREQUENCY_UNTIL_CLOSE:
                if($cookie->get('popup_closed_'.$this->getData('popup_id'))){
                    return false;
                }
                break;

        }
        return true;
    }

    /**
     * Return true value is show
     * @return bool
     */
    public function checkShowOnPage(){
        switch($this->getShowOnPage()){
            case self::SHOW_ON_PRODUCT_PAGE:
                return $this->checkProducts();

            case self::SHOW_ON_CATEGORY_PAGE:
                return $this->checkShowCategory();

            case self::SHOW_ON_CART_PAGE:
                return $this->checkIsOnCartPage();

            case self::SHOW_ON_CHECKOUT_PAGE:
                return $this->checkIsOnCheckoutPage();

            case self::SHOW_ON_HOME_PAGE:
                return $this->checkShowOnHomePage();

            case self::SHOW_ON_URLS_PAGE:
                return $this->checkUrl();

            case self::SHOW_ON_ALL_PAGE:
                return true;
        }
        return true;
    }

    /**
     * @param string $specified
     * @param string $exclude
     * @return bool
     */
    public function checkUrl($specified = '', $exclude = ''){
        if($specified == ''){
            $specified = $this->getSpecifiedUrl();
        }
        if($exclude == ''){
            $exclude = $this->getExcludeUrl();
        }
        if($specified == ''){
            return false;
        }
        return Mage::helper('campaign')->checkInclude($specified, $exclude);
    }


    /**
     * check product show condition
     * @param string $products
     * @return bool
     */
    public function checkProducts($products = ''){
        $isInProductPage = false;
        $productIds = array();
        if($products != ''){
            if(!is_array($products)){
                $productIds[] = $products;
            }else{
                $productIds = $products;
            }
        }else{
            $request = Mage::app()->getRequest();
            if($request->getControllerName() == 'product' && $request->getActionName() == 'view'){
                $productIds[] = $request->getParam('id');
                $isInProductPage = true;
            }
        }
        //search in product array
        $isInSelected = false;
        foreach (explode(',', $this->getProducts()) as $productId) {
            if(in_array(trim($productId), $productIds)){
                $isInSelected = true;
                break;
            }
        }
        if($isInProductPage){
            if($isInSelected || $this->getProducts() == '' || $this->getProducts() == '0'){
                return true;
            }
        }
        return false;
    }

    public function checkShowCategory(){
        $categories = $this->getCategories();
        $categoryIds = explode(',', $categories);
        foreach ($categoryIds as $catId) {
            $categoryIds[] = trim($catId);
        }
        $request = Mage::app()->getRequest();
        if($request->getControllerName() == 'category' && $request->getActionName() == 'view'){
            $currentCatId = $request->getParam('id');
            if(in_array($currentCatId, $categoryIds) || $categories == '' || $categories == '0'){
                //show
                return true;
            }else{
                return false;
            }
        }
        return false;
    }

    public function checkIsOnCartPage(){
        $request = Mage::app()->getRequest();
        if($request->getModuleName() == 'checkout'
            && $request->getControllerName() == 'cart'
            && $request->getActionName() == 'index'){
            return true;
        }else{
            //if not on cart page is not show
            return false;
        }
    }


    /*Functions below for Visitorsegment*/
    //z set visitorsegment check value
    public function checkDevices(){
        $detector = new Mobile_Detect();
        $devicetoshow = array();
        $devices = $this->getDevices();
        if($devices != ''){
            if(!is_array($group)){
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
        $enable = $this->getCookiesEnabled();
        if($enable != ''){
            if($enable == 1){
                $this->checkReturnCustomer();
            }else{
                return false;
            }
        }
    }

    public function checkReturnCustomer(){

        $login = Mage::getSingleton('customer/session')->isLoggedIn();
        if (Mage::getSingleton('customer/session')->isLoggedIn()) {

            $customer = Mage::getSingleton('customer/session')->getCustomer();
            $customer_name = $customer->getName();
        }
        $ipcustomer = Mage::helper('core/http')->getRemoteAddr();
        $popupid = $this->getPopupId();
        $camPaignid = $this->getCampaignId();
        $getReturn = $this->getReturningUser();
        $cookiepopup = $this->getCookieTime();

        $customer_cookie = Mage::getModel('core/cookie')->get($ipcustomer);
        $allcookie = Mage::getModel('core/cookie')->get();

        //check cookie customer
        if(isset($_COOKIE[$ipcustomer])) {
            if($getReturn == 'new'){
                return false;
            }
            if($getReturn == 'return'){
                return true;
            }
        }
        //set cookie for new customer
        if(!isset($_COOKIE[$ipcustomer])) {
                if($ipcustomer){
                        //set cookie for customer
                        $name = $ipcustomer;
                        $value = $customer_name;
                        $period = $cookiepopup;
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

        }else{
            return false;
        }

        //end get value of device
        if($group != ''){

            foreach($sub_group as $subg){
                foreach($subg as $sub){

                    if($sub == 'all_group'){
                        return true;

                    }
                    if($sub == 'not_loged_in'){
                        if($login == false){
                            if($groupcode == 'NOT LOGGED IN'){
                                return true;
                            }
                        }

                    }
                    if($sub == 'general'){

                        if($groupcode == 'General'){
                            return true;
                        }


                    }
                    if($sub == 'wholesale'){

                        if($groupcode == 'Wholesale'){
                            return true;
                        }

                    }
                    if($sub == 'vip_member'){

                        if($groupcode == 'VIP Member'){
                            return true;
                        }
                        break;
                    }
                    if($sub == 'private_sale_member'){

                        if($groupcode == 'Private Sales Member'){
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

