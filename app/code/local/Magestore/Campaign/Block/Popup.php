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
class Magestore_Campaign_Block_Popup extends Mage_Core_Block_Template
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
        return $this;
    }


    /**
     * get single popup, first item
     * @return mixed
     */
    public function getAllDataPopupActive(){
        $pops = $this->getAllPopupAvailable();
        foreach($pops as $pop){
            return $pop;
        }
    }

    /**
     * get all popup through all campaigns
     * @return mixed
     */
    public function getAllPopupAvailable(){
        return Mage::getModel('campaign/supercampaign')->getPopups();
    }

    /**
     * get all popup through all campaigns
     * @return mixed
     */
    public function getDevices(){
        return Mage::getModel('campaign/popup')->checkDevices();
    }

    /**
     * show all popup for login user
     * @return mixed
     */
    public function getUserlogin(){
        return Mage::getModel('campaign/popup')->checkUserLogin();
    }

    /**
     * show all popup for customer group user
     * @return mixed
     */
    public function getCustomerGroup(){
        return Mage::getModel('campaign/popup')->checkCustomerGroup();
    }
}
