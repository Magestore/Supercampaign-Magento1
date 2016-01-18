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
class Magestore_Campaign_Model_Sidebar extends Mage_Core_Model_Abstract
{
    const PREFIX = 'sidebar_';

    const TEMPLATE_GROUP = 'sidebar';
    const TEMPLATE_TYPE_LINK = 'link';
    const TEMPLATE_TYPE_POPUP = 'popup';

    public function _construct()
    {
        parent::_construct();
        $this->_init('campaign/sidebar');
    }

    /**
     * get array of header text model
     * @return this
     */
    public function getAvailable(){
        $campaigns = Mage::getModel('campaign/campaign')->getActiveCampaignAll();
        foreach($campaigns as $camp){
            $sidebar = $camp->getSidebar();
            if(Mage::helper('campaign')->checkAccept(
                $sidebar->getIncludePage(),
                $sidebar->getExcludePage().';checkout/cart/;checkout/onepage/;onestepcheckout/index/index')
            ){
                if($sidebar->getStatus() == 1){
                    return $sidebar;
                }
            }
        }
        return false;
    }

    /**
     * get html with processor cms block
     * @return mixed
     */
    public function toHtml(){
        return Mage::helper('campaign')->convertContentToHtml($this->getContent());
    }

    public function loadNewContent(){
        if($this->isSaveNewTemplate() || $this->isChangeTemplate() || $this->isResetTemplate() === true){
            $this->setContent($this->getTemplate()->getContent());
        }
        return $this;
    }

    public function getTemplate(){
        $template = Mage::getModel('campaign/template')->load($this->getTemplateId());
        return $template;
    }

    public function isResetTemplate(){
        if($this->getIsResetTemplate()){
            return true;
        }
        return false;
    }

    public function isSaveNewTemplate(){
        $templateId = $this->getTemplateId();
        if((is_numeric($templateId) && $this->getContent() == null) || ($this->getId() == null)){
            return true;
        }
        return false;
    }

    public function isChangeTemplate(){
        $templateId = $this->getTemplateId();
        if(is_numeric($templateId) && $this->getId() != null){
            $sidebar = new $this;
            $sidebar->load($this->getId());
            if($sidebar->getTemplateId() !== $templateId){
                return true;
            }
        }
        return false;
    }


    protected function _beforeSave(){
        if($this->getTemplate()->getType() == self::TEMPLATE_TYPE_LINK){
            $this->setType(self::TEMPLATE_TYPE_LINK);
        }
        if($this->getTemplate()->getType() == self::TEMPLATE_TYPE_POPUP){
            $this->setType(self::TEMPLATE_TYPE_POPUP);
        }
        $this->loadNewContent();
    }


}