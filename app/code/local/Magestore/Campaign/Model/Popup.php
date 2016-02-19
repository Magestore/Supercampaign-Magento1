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
                $cookie->set('popup_showed_'.$this->getData('popup_id'), 1, 86400);
                return true;

            case self::SHOW_FREQUENCY_ONLY_ONE:
                if($this->getCampaign()->getData('returning_user') == 'return'
                    || $this->getCampaign()->getData('returning_user') == 'new'
                ){
                    if(!$cookie->get('popup_showed_'.$this->getData('popup_id'))
                        && $this->getCampaign()->checkReturnCustomer()
                    ){
                        $cookie->set('popup_showed_'.$this->getData('popup_id'), 1, 86400);
                        return true;
                    }
                }else{
                    if(!$cookie->get('popup_showed_'.$this->getData('popup_id'))){
                        $cookie->set('popup_showed_'.$this->getData('popup_id'), 1, 86400);
                        return true;
                    }
                }
                break;

            case self::SHOW_FREQUENCY_ONLY_TRIGGER:
                return true;

            case self::SHOW_FREQUENCY_UNTIL_CLOSE:
                if(!$cookie->get('popup_closed_'.$this->getData('popup_id'))){
                    return true; //wait for user closed it
                }
                break;
        }
        return false;
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

    public function checkFormSuccess(){
        $cookie = Mage::getModel('core/cookie');
        if($cookie->get('is_form_success_'.$this->getId())){
            return false;
        }
        return true;
    }

    /**
     * @param string $specified
     * @param string $exclude
     * @return bool
     */
    public function checkUrl($specified = ''){
        if($specified == ''){
            $specified = $this->getSpecifiedUrl();
        }
        if($specified == ''){
            return true;
        }
        return Mage::helper('campaign')->checkInclude($specified);
    }

    public function checkExclude(){
        if(Mage::helper('campaign')->checkInclude($this->getExcludeUrl()) && $this->getExcludeUrl() != ''){
            return false;
        }
        return true;
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

    /**
     * run with Onestepcheckout of Magestore
     * @return bool
     */
    public function checkIsOnCheckoutPage(){
        $request = Mage::app()->getRequest();
        if(($request->getModuleName() == 'checkout'
                && $request->getControllerName() == 'onepage'
                && $request->getActionName() == 'index') ||
            ($request->getModuleName() == 'onestepcheckout'
                && $request->getControllerName() == 'index'
                && $request->getActionName() == 'index')){
            return true;
        }else{
            //if not on cart page is not show
            return false;
        }
    }

    public function checkShowOnHomePage(){
        if(Mage::getBlockSingleton('page/html_header')->getIsHomePage()) {
            return true;
        } else {
            return false;
        }
    }

    public function getCouponCode(){
        $campaign = Mage::getModel('campaign/campaign')->load($this->getCampaignId());
        if($campaign->getId()){
            return $campaign->getCouponCode();
        }else{
            return '';
        }
    }


    public function clearCookie(){
        $cookie = Mage::getModel('core/cookie');
        $cookie->delete('is_form_success_'.$this->getId());
        $cookie->delete('popup_showed_'.$this->getData('popup_id'));
        $cookie->delete('popup_closed_'.$this->getData('popup_id'));
        return $this;
    }

    protected function _beforeSave(){
        $this->clearCookie();
        return $this;
    }

    public function getCampaign(){
        if($this->getCampaignId()){
            return Mage::getModel('campaign/campaign')->load($this->getCampaignId());
        }
        return Varien_Object();
    }
}

