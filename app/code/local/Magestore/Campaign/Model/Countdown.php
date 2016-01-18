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
class Magestore_Campaign_Model_Countdown extends Mage_Core_Model_Abstract
{

    public function _construct()
    {
        parent::_construct();
        $this->_init('campaign/countdown');
    }

    /**
     * install abstract get cms block identifier
     * @return mixed
     */

    public function getCampaign(){
        if(!is_object(parent::getCampaign())){
            if(!is_numeric($this->getCampaignId())){
                throw new Exception('Countdown model: Campaign is null');
            }
            return Mage::getModel('campaign/campaign')->load($this->getCampaignId());
        }
        return parent::getCampaign();
    }

    public function getAvailable(){
        $countdown = array();
        $campaigns = Mage::getModel('campaign/campaign')->getActiveCampaignAll();
        foreach($campaigns as $camp){
            $countdown[] = $camp->getCountdown();
        }
        return $countdown;
    }


    public function getStatuscountdown(){
        $result = '0';
        $countdown = Mage::getModel('campaign/countdown')->load($this->getId());

        if($countdown->getStatus() && $countdown->getProductName()!=''){
            $result = '1';
        }

        //zend_debug::dump($result); die('xong');
        return $result;
    }

    public function getIdCountdown(){
        $cd = Mage::getModel('campaign/countdown')->load($this->getId());
        $idcountdown = $cd->getId();
        //zend_debug::dump($idbanner); die('het roi');
        return $idcountdown;
    }

    public function getProductcd(){
        $cd = Mage::getModel('campaign/countdown')->load($this->getId());
        $productcd = $cd->getProductName();
        //zend_debug::dump($bannermenu); die('het roi');
        return $productcd;
    }

    /**
     * get model option as array
     *
     * @return array
     */
    public static function getLocateOptionArray(){
        return array(
            1 => 'header',
            2 => 'product',
            3 => 'sidebar',
            4 => 'popup'
        );
    }

}

