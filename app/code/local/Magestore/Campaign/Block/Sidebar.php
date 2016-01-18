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
class Magestore_Campaign_Block_Sidebar extends Mage_Core_Block_Template
{
    /**
     * prepare block's layout
     *
     * @return Magestore_Campaign_Block_Campaign
     */
    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    public function getHtml(){
        $sidebar = $this->getSidebar();
        if($sidebar){
            return $sidebar->toHtml();
        }
        return '';
    }

    public function getCampaign(){
        if(!is_object($this->_campaign)){
            $this->_campaign = Mage::getModel('campaign/campaign')->getActiveCampaign();
        }
        return $this->_campaign;
    }

    public function getSidebar(){
        if(!is_object($this->_sidebar)){
            $this->_sidebar = $this->getSidebarActive();
        }
        return $this->_sidebar;
    }

    /**
     * get sidebar is active of most priority campaign
     * @return mixed sidebar | boolean
     */
    public function getSidebarActive(){
        return Mage::getModel('campaign/sidebar')->getAvailable();
    }


    public function isActive(){
        if($this->getSidebar()){
            return true;
        }
        return false;
    }

    public function getLink(){
        return $this->getSidebar()->getUrl();
    }

    public function getSidebarType(){
        if($this->getSidebar()){
            return $this->getSidebar()->getTemplate()->getType();
        }
        return '';
    }

}