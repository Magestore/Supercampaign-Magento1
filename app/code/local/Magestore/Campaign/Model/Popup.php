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

    public function _construct()
    {
        parent::_construct();
        $this->_init('campaign/popup');
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
            $specified = $this->getExcludeUrl();
        }
        return Mage::helper('campaign')->checkInclude($specified, $exclude);
    }


    /**
     * check product show condition
     * @param string $products
     * @return bool
     */
    public function checkProducts($products = ''){
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
            }
        }
        //search in array
        $isInArray = false;
        foreach (explode(',', $this->getProducts()) as $productId) {
            if(in_array(trim($productId), $productIds)){
                $isInArray = true;
                break;
            }
        }
        if(!empty($productIds) && !$isInArray){
            return false;
        }else{
            return true;
        }
    }

    public function checkDevices($devices = ''){
        return true;
    }

    public function checkCountry($countries = ''){
        return true;
    }

    public function checkUserLogin(){
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
        return true;
    }

    public function checkCartSubtotalLessThan(){
        return true;
    }

    public function checkUserIP(){
        if(!$this->getUserIp()){
            return false;
        }
        return true;
    }
}

