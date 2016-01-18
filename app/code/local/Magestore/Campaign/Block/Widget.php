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
 * Campaign Adminhtml Block
 * 
 * @category    Magestore
 * @package     Magestore_Campaign
 * @author      Magestore Developer
 */
class Magestore_Campaign_Block_Widget extends Mage_Core_Block_Template
{
    public function _construct(){
        if($this->getId()){
            $this->_widget = Mage::getModel('campaign/widget')->load($this->getId());
        }
        return parent::_construct();
    }

    public function _prepareLayout()
    {
        if($this->getType()){
            if($this->getType() == 'text'){
                $this->setTemplate('campaign/widget/text.phtml');
            }
            elseif($this->getType() == 'slider'){
                $this->setTemplate('campaign/widget/slider.phtml');
            }
            elseif($this->getType() == 'static'){
                $this->seTemplate('campaign/widget/static.phtml');
            }
            return $this;
        }
        $this->setTemplate('campaign/widget.phtml');
        return $this;
    }

    public function setType($type = ''){
        $this->_type = $type;
        return $this;
    }

    public function getType(){
        if($this->_widget){
            $this->_type = $this->_widget->getType();
        }
        $this->_type = '';
        return $this->_type;
    }

    public function getBannerHtml(){
        if($this->_bannerHtml){
            return $this->_bannerHtml;
        }else{
            if($this->getId()){
                $this->_bannerHtml =  Mage::getModel('campaign/widget')->getBanner()->getHtml();
                return $this->_bannerHtml;
            }
        }
        return '<!--Campaign: no widget banner-->';
    }

    public function isEnabled(){
        if($this->_widget && $this->_widget->getStatus() == '1'){
            return true;
        }
        return false;
    }
}