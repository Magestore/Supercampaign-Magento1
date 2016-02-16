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
class Magestore_Campaign_Block_Coupon extends Mage_Core_Block_Template
{

    public function _construct(){
        return parent::_construct();
    }
    /**
     * prepare block's layout
     *
     * @return Magestore_Campaign_Block_Campaign
     */
    public function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->setTemplate('campaign/coupon.phtml');
        return $this;
    }

    public function getCouponcode(){
        $code = Mage::registry('coupon');
        if($code){
            $coupon = $code;
        }
        return $coupon;
    }

    public function getPopupCoupon(){
        $popup_cookie = Mage::getModel('core/cookie')->get('popup_id');
            $model_popup = Mage::getModel('campaign/popup')->load($popup_cookie);
            $campaign_id = $model_popup->getCampaignId();
            $model_campaign = Mage::getModel('campaign/campaign')->load($campaign_id);
            $campaign_coupon = $model_campaign->getCouponCode();
            return $campaign_coupon;

    }
}
