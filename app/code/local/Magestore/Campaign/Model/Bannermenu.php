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
class Magestore_Campaign_Model_Bannermenu extends Mage_Core_Model_Abstract
{

    public function _construct()
    {
        parent::_construct();
        $this->_init('campaign/bannermenu');
    }

    /**
     * install abstract get cms block identifier
     * @return mixed
     */

    public function getCampaign(){
        if(!is_object(parent::getCampaign())){
            if(!is_numeric($this->getCampaignId())){
                throw new Exception('Bannermenu model: Campaign is null');
            }
            return Mage::getModel('campaign/campaign')->load($this->getCampaignId());
        }
        return parent::getCampaign();
    }


    public function getAvailable(){
        $bannermenu = array();
        $campaigns = Mage::getModel('campaign/campaign')->getActiveCampaignAll();
        foreach($campaigns as $camp){
            $bannermenu[] = $camp->getBannermenu();
        }
        //zend_debug::dump($bannermenu); die('ccc');
        return $bannermenu;
    }

    public function getStatusbannermenu(){
        $result = '0';
        $banner = Mage::getModel('campaign/bannermenu')->load($this->getId());

        if($banner->getStatus() && $banner->getInputBannermenu()!=''){
            $result = '1';
        }

        //zend_debug::dump($result); die('xong');
        return $result;
    }

    public function getIdbanner(){
        $banner = Mage::getModel('campaign/bannermenu')->load($this->getId());
        $idbanner = $banner->getId();
        //zend_debug::dump($idbanner); die('het roi');
        return $idbanner;
    }

    public function getBanner(){
        $banner = Mage::getModel('campaign/bannermenu')->load($this->getId());
        $bannermenu = $banner->getInputBannermenu();
        //zend_debug::dump($bannermenu); die('het roi');
        return $bannermenu;
    }
}

