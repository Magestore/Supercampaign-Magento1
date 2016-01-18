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
 * @category    Magestore
 * @package     Magestore_Campaign
 * @author      Magestore Developer
 */
abstract class Magestore_Campaign_Block_Popup_Abstract extends Mage_Core_Block_Template
{
    abstract function getHtml();

    /**
     * prepare block's layout
     *
     * @return Magestore_Campaign_Block_Campaign
     */
    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    /**
     * active when still have 1 or more campaign active
     * @return bool
     */
    public function isActive()
    {
        if ($this->getAvailable()) {
            return true;
        }
        return false;
    }

    public function getPopupType()
    {
        return $this->getPopup()->getType();
    }

    /**
     * file use: app/design/frontend/default/default/template/campaign/coupon.phtml
     * @return bool
     */
    public function isShowCoupon()
    {
        if($this->getPopup()){
            if ($this->getPopup()->getCampaign()->getUseCoupon() == 1 && $this->getPopup()->getIsShowCoupon() == 1) {
                return true;
            }
        }
        return false;
    }

    /**
     * file use: app/design/frontend/default/default/template/campaign/coupon.phtml
     * @return string
     */
    public function getCouponCode()
    {
        //getCampaign did set from controller
        if ($this->getCampaign()) {
            return $this->getCampaign()->getCouponCode();
        }
        return '';
    }

    /**
     * get Popup Model from campaign model already set
     * @return bool
     */
    public function getCampaignPopup(){
        if(is_object($this->getCampaign())){
            if(Mage::registry('campaign_popup')){
                $this->_popup = Mage::registry('campaign_popup');
            }
            if(!$this->_popup){
                $this->_popup = $this->getCampaign()->getPopup();
            }
            if(!Mage::registry('campaign_popup')){
                Mage::register('campaign_popup', $this->_popup);
            }
            return $this->_popup;
        }
        return false;
    }

    /**
     * get Popup model by search available
     * @return object | bool
     */
    public function getPopup()
    {
        if (!is_object($this->_popup)) {
            if(Mage::registry('campaign_popup')){
                $this->_popup = Mage::registry('campaign_popup');
            }else{
                $pop = $this->getAvailable();
                if(is_object($pop)){
                    $this->_popup = $pop;
                    if($this->_popup->getId() == null){
                        return false;
                    }
                }else{
                    return false;
                }
            }
            if(!Mage::registry('campaign_popup')){
                Mage::register('campaign_popup', $this->_popup);
            }
        }
        return $this->_popup;
    }

    /**
     * get popup has accepted by includes - excludes and is active
     * @return array
     */
    public function getAvailable(){
        return Mage::getModel('campaign/popup')->getAvailable();
    }

    public function getCampaignId(){
        if($this->getPopup()){
            return $this->_popup->loadCampaign()->getId();
        }
        return 0;
    }

    public function getCampaign()
    {
        if (!is_object($this->_campaign)) {
            $this->_campaign = Mage::getModel('campaign/campaign')->getActiveCampaign();
            if($this->_campaign->getId() == null){
                return false;
            }
        }
        return $this->_campaign;
    }

    public function isShowFirstTime()
    {
        if($this->getPopup()){
            return $this->getPopup()->getIsShowFirstTime();
        }
        return false;
    }
}
