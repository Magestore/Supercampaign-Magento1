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

    public function _construct()
    {
        parent::_construct();
        $this->_init('campaign/popup');
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

//        $devicetoshow = array();
//        $devices = $this->getDevices();
//        if($devices != ''){
//
//            if(!is_array($devices)){
//                $devicetoshow[] = $devices;
//            }else{
//                $devicetoshow = $devices;
//            }
//
//            //explode in array
//            $sub_device = array();
//            $n=0;
//            foreach ($devicetoshow as $subdevice) {
//                if(in_array(trim($subdevice), $devicetoshow)){
//                    $sub_device[] = explode(',', trim($subdevice));
//                    $n = $n+1;
//                }
//            }
//        }else{
//            return array(0);
//        }
//        //zend_debug::dump($sub_device); die('323');
//        return $sub_device;
        return true;
    }

    public function checkUserLogin(){

//        $user = $this->getLoginUser();
//        if($user != ''){
//            //check session
//            $login = Mage::getSingleton('customer/session')->isLoggedIn(); //Check if User is Logged In
//            if($login)
//            {
//
//            }
//        }else{
//            return false;
//        }
//        zend_debug::dump($sub_case); die('userkkk');
//        return $sub_case;
        return true;
    }

    /**
     * option show with new user or visited user
     * @return bool
     */
    public function checkReturnCustomer(){
        return true;
    }

    /**
     * option show or not with current customer's group
     * @return bool
     */
    public function checkCustomerGroup(){
//        $customergroup = array();
//        $group = $this->getCustomerGroupIds();
//        if($group != ''){
//            if(!is_array($group)){
//                $customergroup[] = $group;
//            }else{
//                $customergroup = $group;
//            }
//            //explode in array
//            $sub_group = array();
//            foreach ($customergroup as $subgr) {
//                if(in_array(trim($subgr), $customergroup)){
//                    $sub_group[] = explode(',', trim($subgr));
//                }
//            }
//        }else{
//            return false;
//        }
//
//        //check session
//        $login = Mage::getSingleton('customer/session')->isLoggedIn(); //Check if User is Logged In
//        if($login)
//        {
//            $groupId = Mage::getSingleton('customer/session')->getCustomerGroupId(); //Get Customers Group ID
//
//            $group = Mage::getModel('customer/group')->load($groupId);
//
//            $namegroup = $group->getCustomerGroupCode();
//
//        }
//
//        //end check session
//        foreach($sub_group as $subg){
//            foreach($subg as $sub){
//                if($sub == 'all_group'){
//                    return true;
//                    break;
//                }
//            }
//        }
//
//        return $sub_group;
        return true;
    }


    public function checkUserIP(){
        if(!$this->getUserIp()){
            return false;
        }
        return true;
    }

    /*End for check visitorsegment*/
}

