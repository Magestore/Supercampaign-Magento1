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
class Magestore_Campaign_Model_Maillist extends Mage_Core_Model_Abstract
{

    public function _construct()
    {
        parent::_construct();
        $this->_init('campaign/maillist');
    }

    /**
     * check unique condition, true if can insert email
     * @param string $email
     * @return bool
     */
    public function checkUnique($email = ''){
        $check_email = $this->getEmail();
        if($email){
            $check_email = $email;
        }
        $collection = $this->getCollection();
        $collection->addFieldToFilter('email', $check_email);
        if($this->getCampaignId()){
            $collection->addFieldToFilter('campaign_id', $this->getCampaignId());
        }
        if($collection->getSize() > 0){
            return false;
        }
        return true;
    }

    protected function _beforeSave(){
        if(!$this->checkUnique()){
            throw new Exception(Mage::helper('campaign')->__('Cannot add a unique email'));
        }
        try {
            $mailchimp = Mage::getSingleton('campaign/mailchimp');
            $campaign = Mage::getModel('campaign/campaign')->load($this->getCampaignId());
            if ($campaign) {
                $this->setCampaignName($campaign->getName());
            }
            $mergeVars = array(
                'FNAME' => $this->getName(),
                'CAMPAIGN' => $this->getCampaignName(),
                'COUPON' => $this->getCouponCode(),
                'DATE_START' => $this->getStartTime(),
                'DATE_EXP' => $this->getExpiredTime(),
                'USED' => $this->getUsedCoupon()?'Yes':'No'
            );
            if ($this->getId()) {
                $mailchimp->updateMember($this->getEmail(), $mergeVars);
            } else {
                $mailchimp->addMember($this->getEmail(), $mergeVars);
            }
        }
        catch(Exception $e){
            //zend_debug::dump($e->getMessage());
        }

        parent::_beforeSave();
    }

    public function getMaillistByCampaign($id){
        $collection = $this->getCollection();
        $collection->addFieldToFilter('campaign_id', $id);
        return $collection->getFirstItem();
    }
}