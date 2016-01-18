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
 * Campaign Block
 *
 * How to use:
 * {{block type="campaign/countdown" locate="product" product_id="123" campaign_id="123"}}
 * [locate] can replace by locate_view ext: locate_view="product"
 * [product_id] can replace by product ext: product="123"
 * values:
 * [locate]: product|header|popup|sidebar
 * [product]: Product ID
 * [campaign_id]: Campaign ID
 *
 *
 *
 *
 *
 * @category    Magestore
 * @package     Magestore_Campaign
 * @author      Magestore Developer
 */
class Magestore_Campaign_Block_Countdown extends Mage_Core_Block_Template
{
    const PREFIX_ID = 'countdown-';

    protected $_id; //id of countdown unique

    protected function _construct(){
        $this->_date = $this->extractEndDate();
    }
    /**
     * prepare block's layout
     *
     * @return Magestore_Campaign_Block_Campaign
     */
    public function _prepareLayout()
    {
        $this->setTemplate('campaign/countdown.phtml');
        return parent::_prepareLayout();
    }

    /**
     * get product ids from campaign countdown
     * @return string
     */
    public function getCampaignProducts(){
        $countdown = $this->getCountdown();
        if($countdown){
            return $countdown->getProductId();
        }
        return '';
    }

    /**
     * get product has set from frontend
     * @return mixed
     */
    public function getFrontProduct(){
        return $this->getProduct();
    }

    /**
     * get product from set current product
     * @return mixed
     */
    public function getProduct(){
        if($this->_product){
            return $this->_product;
        }elseif(parent::getProduct() || parent::getProductId() != ''){
            if(is_object(parent::getProduct())){
                return parent::getProduct();
            }else{
                return Mage::getModel('catalog/product')->load(parent::getProduct());
            }
            if(is_string(parent::getProductId())){
                return Mage::getModel('catalog/product')->load(parent::getProductId());
            }
        }
        return false;
    }

    /**
     * set the product if need to run countdown for a product
     * @param $product
     */
    public function setProduct($product){
        if(!is_object($product)){
            $this->_product = Mage::getModel('catalog/product')->load($product);
        }else{
            $this->_product = $product;
        }
        return $this;
    }

    /**
     * check can view with locate is selected in countdown setting
     * @return bool
     */
    public function canLocateView(){
        $countdown = $this->getCountdown();
        $option = Magestore_Campaign_Model_Countdown::getLocateOptionArray();
        $showIn = $countdown->getShowcountdown();
        $showIn = explode(',', $showIn);
        if(!$this->getLocateView()){
            return true;
        }
        foreach($showIn as $key){
            if($option[$key] == $this->getLocateView()){
                return true;
            }
        }
        return false;
    }

    /**
     * set locate of countdown for product or header text, ...
     * @param $type
     * @return $this
     */
    public function setLocateView($locate){
        $this->_locateView = $locate;
        return $this;
    }

    /**
     * you must set locate view for each locate
     * when set locate view is exactly to view
     * @return mixed
     */
    public function getLocateView(){
        if(!$this->_locateView){
            if($this->getLocate()){
                return $this->getLocate();
            }
            if(parent::getLocateView()){
                return parent::getLocateView();
            }
        }
        return $this->_locateView;
    }



    public function getActiveCountdown(){
        $campaign = $this->getCampaign();
        if($campaign){
            return $campaign->getCountdown();
        }
        return false;
    }

    /**
     * get countdown model by set or by from running campaign
     * @return bool
     */
    public function getCountdown(){
        if($this->_countdown){
            return $this->_countdown;
        }elseif($this->getCampaign()){
            return $this->getCampaign()->getCountdown();
        }
        return false;
    }

    public function setCountdown($object){
        $this->_countdown = $object;
        return $this;
    }

    /**
     * get campaign with next campaign is active and countdown status is enabled
     * @return bool
     */
    public function getCampaign()
    {
        //support set campaign from static block
        if(parent::getCampaignId()){
            $this->_campaign = Mage::getModel('campaign/campaign')->load(parent::getCampaignId());
        }
        //user register to transfer campaign to block
        if(Mage::registry('campaign')){
            $this->_campaign = Mage::registry('campaign'); //get from headertext block
            Mage::unregister('campaign'); //and unregister headertext
        }
        //finally get campaign by it's self
        if (!is_object($this->_campaign)) {
            $camps = Mage::getModel('campaign/campaign')->getActiveCampaignAll();
            foreach($camps as $campaign){
                if($campaign->getCountdown()->getStatus()
                    == Magestore_Campaign_Model_Countdown_Status::STATUS_ENABLED){
                    $this->_campaign = $campaign->load($campaign->getId());
                    break;
                }
            }
            if(!is_object($this->_campaign)){
                //get campaign is active first for default
                $this->_campaign = Mage::getModel('campaign/campaign')->getActiveCampaign();
            }
            //get the next campaign doesn't disable countdown
            if($this->_campaign->getId() == null){
                return false;
            }
        }
        return $this->_campaign;
    }

    /**
     * set campaign model for block countdown
     * @param $campaign
     * @return $this
     */
    public function setCampaign($campaign){
        if(!is_object($campaign)){
            $this->_campaign = Mage::getModel('campaign/campaign')->load($campaign);
        }else{
            $this->_campaign = $campaign;
        }
        return $this;
    }

    public function getStatus(){
        $campaign = $this->getCampaign();
        return $campaign->getCountdown()->getStatus();
    }

    /**
     * set id of countdown
     * @param $sid
     * @return $this
     */
    public function setId($sid){
        $this->_id = $sid;
        return $this;
    }

    /**
     * get id of countdown
     * @return string
     */
    public function getHtmlId(){
        $id = self::PREFIX_ID;
        if($this->_id){
            return $this->_id;
        }
        if($this->getId()){
            return $this->getId();
        }
        if($this->getLocate()){
            if($this->getLocate() == 'header'){
                $id .= 'header_'.$this->getCampaign()->getHeadertext()->getId();
            }
            elseif($this->getLocate() == 'sidebar'){
                $id .= 'sidebar_'.$this->getCampaign()->getSidebar()->getId();
            }
            elseif($this->getLocate() == 'popup'){
                $id .= 'popup_'.$this->getCampaign()->getPopup()->getId();
            }
            elseif($this->getLocate() == 'product'){
                if($this->getProduct()){
                    $id .= 'product_'.$this->getProduct()->getId();
                }
            }
        }

        $id .= sha1(rand().microtime());
        return $id;
    }

    /**
     * get date from end_date campaign
     * @return Zend_Date
     */
    public function extractEndDate(){
        if($this->getCampaign()){
            $datetime = new DateTime($this->getCampaign()->getEndTime());
            return new Zend_Date($datetime->getTimestamp());
        }
        return false;
    }

    public function getMinute(){
        if($this->_date){
            return $this->_date->toString('m', 'iso');
        }
        return '';
    }

    public function getHour(){
        if($this->_date){
            return $this->_date->toString('H', 'iso');
        }
        return '';
    }

    public function getDay(){
        if($this->_date){
            return $this->_date->toString('d', 'iso');
        }
        return '';
    }

    public function getMonth(){
        if($this->_date){
            return $this->_date->toString('M', 'iso');
        }
        return '';
    }

    public function getYear(){
        if($this->_date){
            return $this->_date->toString('yyyy', 'iso');
        }
        return '';
    }

    public function getTimezone(){
        $tz_offset = Mage::getSingleton('core/date')
            ->calculateOffset(Mage::app()->getStore()->getConfig('general/locale/timezone'));
        return floor($tz_offset/60/60);
    }

    /**
     * get style if countdown clock view
     * support for set param in {{block type="campaign/countdown" style="long"}}
     * @return string
     */
    public function getStyle(){
        $style = parent::getStyle();
        if(!$style) $style = 'medium';
        return $style;
    }

    /**
     * check all condition can active
     * @return bool
     */
    public function isActive(){
        if($this->getStatus() == Magestore_Campaign_Model_Countdown_Status::STATUS_ENABLED
            && $this->canLocateView()){
            if($this->getProduct() && is_object($this->getProduct())){
                $productIds = explode(',', $this->getCampaignProducts());
                if(!in_array($this->getProduct()->getId(), $productIds) && $this->getCampaignProducts() != ''){
                    return false;
                }
            }
            return true;
        }
        return false;
    }

}