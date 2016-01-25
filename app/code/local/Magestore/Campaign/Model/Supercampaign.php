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
class Magestore_Campaign_Model_Supercampaign extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('campaign/supercampaign');
    }

    public function getCampaigns(){
        $collection = Mage::getModel('campaign/campaign')->getCollection();
        $current_date = Mage::getModel('core/date')->gmtDate();
        $collection->addFieldToFilter('status', '1')
            ->addFieldToFilter('start_time', array('from'=>$current_date))
            ->addFieldToFilter('end_time', array('to'=>$current_date))
            ->getSelect()
            ->order('priority DESC')
            ->order('end_time ASC')
        ;
        return $collection;
    }
    
    public function getPopups(){
        $popups = array();
        $campaigns = $this->getCampaigns();
        foreach ($campaigns as $campaign) {
            $popups[] = $campaign->getPopupsAvailable();
        }
        return $popups;
    }
}
