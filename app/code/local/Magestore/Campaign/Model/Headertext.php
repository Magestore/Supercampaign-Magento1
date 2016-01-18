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
class Magestore_Campaign_Model_Headertext extends Mage_Core_Model_Abstract
{
    const PREFIX = 'headertext_';

    const TEMPLATE_GROUP = 'headertext';
    const TEMPLATE_TYPE_LINK = 'link';
    const TEMPLATE_TYPE_POPUP = 'popup';

    public function _construct()
    {
        parent::_construct();
        $this->_init('campaign/headertext');
    }

    /**
     * get array of header text model
     * @return array
     */
    public function getAvailable(){
        $headertext = array();
        $campaigns = Mage::getModel('campaign/campaign')->getActiveCampaignAll();
        foreach($campaigns as $camp){
            $hdText = $camp->getHeadertext();
            if(Mage::helper('campaign')->checkAccept($hdText->getIncludePage(), $hdText->getExcludePage())){
                if($hdText->getStatus() == 1){
                    $headertext[] = $hdText;
                }
            }
        }
        return $headertext;
    }

    /**
     * get html with processor cms block
     * @return mixed
     */
    public function toHtml(){
        return Mage::helper('campaign')->convertContentToHtml($this->getContent());
    }

    /**
     * install abstract get cms block identifier
     * @return mixed
     */
    /*public function getCmsBlockIdentifier(){
        $cms = Mage::getModel('cms/block')->load($this->getCmsBlock());
        $iden = '';
        if($cms->getBlockId()){
            $iden = $cms->getIdentifier();
        }
        return $iden;
    }*/

    public function applyTemplateContent(){
        if($this->isSaveNewTemplate() || $this->isChangeTemplate() || $this->isResetTemplate() === true){
            $this->setContent($this->getTemplate()->getContent());
        }
    }

    /*public function saveCmsBlock(){
        if($this->isSaveNewTemplate() || $this->isChangeTemplate() || $this->isResetTemplate() === true){
            $cmsId = Mage::helper('campaign')->saveStaticBlock(
                $this->getCreateCmsIdentifier(), $this->getTemplateContent(),
                $this->getCampaign()->getName(), $this->getCampaign()->getStores());
            if($cmsId){
                $this->setCmsBlock($cmsId);
                $cms = Mage::getModel('cms/block')->load($cmsId);
                $this->setCmsIdentifier($cms->getIdentifier());
            }else{
                Mage:getSingleton('adminhtml/session')->addError('Can not create cms template for Headertext');
            }
        }else{
            $this->_updateCmsStore();
        }
        return $this;
    }*/

    public function getCampaign(){
        if(!is_object(parent::getCampaign())){
            if(!is_numeric($this->getCampaignId())){
                throw new Exception('Headertext model: Campaign is null');
            }
            return Mage::getModel('campaign/campaign')->load($this->getCampaignId());
        }
        return parent::getCampaign();
    }

    /**
     * get template model for header text
     * @return mixed
     */
    public function getTemplate(){
        //return Mage::getModel('campaign/headertext_template')->load($this->getTemplateId());
        return Mage::getModel('campaign/template')->load($this->getTemplateId());

    }

    public function getTemplateContent(){
        $content = $this->getTemplate()->getContent();
        return $content;
    }

    /*public function getCreateCmsIdentifier(){
        $campaign = $this->getCampaign();
        if(!is_object($campaign)){
            throw new Exception('Headertext model: Campaign is null');
        }
        return $campaign->getCmsPrefix().'_'.substr(str_replace(' ', '_', strtolower($campaign->getName())), 0, 22).'_headertext_'.$campaign->getId();
    }*/

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
            $headertext = new $this;
            $headertext->load($this->getId());
            if($headertext->getTemplateId() !== $templateId){
                return true;
            }
        }
        return false;
    }

    /*protected function _updateCmsStore(){
        $block = Mage::getModel('cms/block')->load($this->getCmsBlock());
        $block->setStores($this->getCampaign()->getStores()); //set use all store with default
        if($block->getId()){
            $block->save();
        }
        return $this;
    }*/

    protected function _beforeSave(){
        $this->applyTemplateContent();
        //parent::_beforeSave();
    }


    protected function _beforeDelete(){
        //need delete cms/block too
        //Mage::getModel('cms/block')->load($this->getCmsBlock())->delete();
    }

}

