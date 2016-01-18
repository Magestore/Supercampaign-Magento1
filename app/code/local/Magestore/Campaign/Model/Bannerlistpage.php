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
class Magestore_Campaign_Model_Bannerlistpage extends Mage_Core_Model_Abstract
{

    public function _construct()
    {
        parent::_construct();
        $this->_init('campaign/bannerlistpage');
    }

    /**
     * install abstract get cms block identifier
     * @return mixed
     */
    public function getCampaign(){
        if(!is_object(parent::getCampaign())){
            if(!is_numeric($this->getCampaignId())){
                throw new Exception('Bannerlistpage model: Campaign is null');
            }
            return Mage::getModel('campaign/campaign')->load($this->getCampaignId());
        }
        return parent::getCampaign();
    }


    public function getStatusbannerlistpage(){
        $result = '0';
        $banner = Mage::getModel('campaign/bannerlistpage')->load($this->getId());

        if($banner->getStatus() && $banner->getInputBanner()!=''){
            $result = '1';
        }
        return $result;
    }

    public function getIdbanner(){
        $banner = Mage::getModel('campaign/bannerlistpage')->load($this->getId());
        $idbanner = $banner->getId();

        return $idbanner;
    }

    public function getBanner(){
        $banner = Mage::getModel('campaign/bannerlistpage')->load($this->getId());
        $bannerlistpage = $banner->getInputBanner();
        return $bannerlistpage;
    }

    /*new update 0.2.0*/
    public function getAvailable(){
        $campaigns = Mage::getModel('campaign/campaign')->getActiveCampaignAll();
        foreach($campaigns as $camp){
            $banner = $camp->getBannerlistpage();
            if($banner->checkUrl()){
                return $banner;
                break;
            }
        }
        return false;
    }

    /**
     * check with current url page allow to display
     * return bool
     */
    public function checkUrl(){
        return Mage::helper('campaign')->checkAccept(
            $this->getInclude(),
            $this->getExclude()
        );
    }
}

