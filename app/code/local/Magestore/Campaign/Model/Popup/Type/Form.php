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
class Magestore_Campaign_Model_Popup_Type_Form extends Magestore_Campaign_Model_Popup_Abstract
{
    const BLOCK_TYPE = 'campaign/popup_type_default'; //declare block that used as your block custom type

    const PREFIX_DATA = 'form_'; //prefix add before each attribute name
    const TYPE_CODE = 'form';

    const PREFIX = 'static_'; //in future will use this, prefix add before each attribute name

    const TEMPLATE_GROUP = 'popup';
    const TEMPLATE_TYPE_STEP_1 = 'form_step1';
    const TEMPLATE_TYPE_STEP_2 = 'form_step2';

    public function _construct()
    {
        parent::_construct();
        $this->setTypeCode(self::TYPE_CODE);
        $this->_init('campaign/popup_type_'.strtolower($this->getTypeCode()));
        $this->_helper = Mage::helper('campaign');
    }

    public function getBlockType(){
        return self::BLOCK_TYPE;
    }

    /**
     * get html with processor cms block
     * @return mixed
     */
    public function toHtml(){
        return Mage::helper('campaign')->convertContentToHtml($this->getContentStepOne());
    }

    /**
     * get html with processor cms block
     * @return mixed
     */
    public function toHtml2(){
        return Mage::helper('campaign')->convertContentToHtml($this->getContentStepTwo());
    }

    /**
     * install abstract get cms block identifier
     * @return mixed
     */
    /*public function getCmsBlockIdentifier($step = 1){

        if($step == 1){
            $iden1 = '';
            $cmsBlock1 = Mage::getModel('cms/block')->load($this->getStaticBlockForm());
            if($cmsBlock1->getBlockId()){
                $iden1 = $cmsBlock1->getIdentifier();
            }
            return $iden1;
        }elseif($step == 1){
            $iden2 = '';
            $cmsBlock2 = Mage::getModel('cms/block')->load($this->getStaticBlockThanks());
            if($cmsBlock2->getBlockId()){
                $iden2 = $cmsBlock2->getIdentifier();
            }
            return $iden2;
        }else{
            return '';
        }
    }*/

    /**
     * load new content function had called from _beforeSave from Abstract model
     * @return $this
     */
    public function applyNewContent(){
        $this->loadNewContent1();
        $this->loadNewContent2();
        return $this;
    }

    /**
     * load new content from template when save or update template id
     * @return $this
     */
    public function loadNewContent1(){
        if($this->isSavedNewTemplateForm() || $this->_isChangeTemplate1() || $this->isResetTemplate1() === true){
            $this->setData('content_step_one', $this->getTemplate1()->getContent());
        }
        return $this;
    }

    /**
     * for content type form step 2
     * @return $this
     */
    public function loadNewContent2(){
        if($this->isSavedNewTemplateThanks() || $this->_isChangeTemplate2() || $this->isResetTemplate2() === true){
            $this->setData('content_step_two', $this->getTemplate2()->getContent());
        }
        return $this;
    }

    /**
     * get template model for type form step 1
     * @return mixed
     */
    public function getTemplate1(){
        $templateId = $this->getTemplateIdOne();
        if(is_numeric($templateId)){
            return Mage::getModel('campaign/template')->load($templateId);
        }
        return new Magestore_Campaign_Model_Template();
    }

    /**
     * get template model for type form step 2
     * @return mixed
     */
    public function getTemplate2(){
        $templateId = $this->getTemplateIdTwo();
        if(is_numeric($templateId)){
            return Mage::getModel('campaign/template')->load($templateId);
        }
        return new Magestore_Campaign_Model_Template();
    }

    /**
     * function global call interface
     * run before save this model
     * @return $this
     */
    /*public function saveStaticBlock($reset = false){
        $title = $this->getCampaign()->getName();
        //save for form
        if($this->_isSaveNewTemplateForm() || $this->_isChangeTemplate1() || $reset){
            $identifier = 'super_campaign_'.substr(str_replace(' ', '_', strtolower($this->getCampaign()->getName())), 0, 22).'_'.$this->getCampaign()->getId().'_form'; //rend identifier
            $content = $this->getTemplateForm()->getContent();
            $id = $this->_helper()->saveStaticBlock($identifier, $content, $title, $this->getCampaign()->getStores());
            $this->setStaticBlockForm($id);
        }else{
            $this->_updateStaticBlockStoreForm();
        }
        //save for thanks page
        if($this->_isSaveNewTemplateThanks() || $this->_isChangeTemplate2() || $reset){
            $identifier = 'super_campaign_'.substr(str_replace(' ', '_', strtolower($this->getCampaign()->getName())), 0, 22).'_'.$this->getCampaign()->getId().'_thanks'; //rend identifier
            $content = $this->getTemplateThanks()->getContent();
            $id = $this->_helper()->saveStaticBlock($identifier, $content, $title, $this->getCampaign()->getStores());
            $this->setStaticBlockThanks($id);
        }else{
            $this->_updateStaticBlockStoreThanks();
        }

        return $this;
    }*/



    /**
     * @return Magestore_Campaign_Model_Popup_Template
     */
    /*protected function getTemplateForm(){
        $templateId = $this->getFormTemplate();
        if(is_numeric($templateId)){
            return Mage::getModel('campaign/popup_template')->load($templateId);
        }
        return new Magestore_Campaign_Model_Popup_Template();
    }*/

    /**
     * @return Magestore_Campaign_Model_Popup_Template
     */
    /*protected function getTemplateThanks(){
        $templateId = $this->getThanksTemplate();
        if(is_numeric($templateId)){
            return Mage::getModel('campaign/popup_template')->load($templateId);
        }
        return new Magestore_Campaign_Model_Popup_Template();
    }*/

    /**
     * check condition able to create new template
     * @return bool
     */
    public function isSavedNewTemplateForm(){
        return $this->_isSaveNewTemplateForm();
    }

    /**
     * check condition able to create new template
     * @return bool
     */
    public function isSavedNewTemplateThanks(){
        return $this->_isSaveNewTemplateThanks();
    }

    /**
     * check condition able to create new template
     * @return bool
     */
    protected function _isSaveNewTemplateForm(){
        $templateId = $this->getTemplateIdOne();
        if(($this->getCampaign()->getId() == '' && is_numeric($templateId) && $this->getContentStepOne() == null) || ($this->getId() == null)){
            return true;
        }
        return false;
    }

    /**
     * check condition able to create new template
     * @return bool
     */
    protected function _isSaveNewTemplateThanks(){
        $templateId = $this->getTemplateIdTwo();
        if((is_numeric($templateId) && $this->getContentStepTwo() == null) || ($this->getId() == null)){
            return true;
        }
        return false;
    }

    protected function _isChangeTemplate1(){
        $templateId = $this->getTemplateIdOne();
        if(is_numeric($templateId) && $this->getId() != null){
            $popup_form = Mage::getModel('campaign/popup_type_form')->load($this->getId());
            if($popup_form->getTemplateIdOne() !== $templateId){
                return true;
            }
        }
        return false;
    }

    protected function _isChangeTemplate2(){
        $templateId = $this->getTemplateIdTwo();
        if(is_numeric($templateId) && $this->getId() != null){
            $popup_form = Mage::getModel('campaign/popup_type_form')->load($this->getId());
            if($popup_form->getTemplateIdTwo() !== $templateId){
                return true;
            }
        }
        return false;
    }

    public function isResetTemplate1(){
        if($this->getIsResetTemplate1()){
            return true;
        }
        return false;
    }

    public function isResetTemplate2(){
        if($this->getIsResetTemplate2()){
            return true;
        }
        return false;
    }

    protected function _helper(){
        if(!is_object($this->_helper)){
            $this->_helper = Mage::helper('campaign');
        }
        return $this->_helper;
    }

    /*protected function _updateEditStaticBlockStore(){
        $this->_updateStaticBlockStoreForm()
            ->_updateStaticBlockStoreThanks();
        return $this;
    }*/

    /*protected function _updateStaticBlockStore($block_id, $stores = array(0)){
        $block = Mage::getModel('cms/block')->load($block_id);
        $block->setStores($stores); //set use all store with default
        if($block->getId()){
            $block->save();
        }
        return $this;
    }

    protected function _updateStaticBlockStoreForm(){
        $this->_updateStaticBlockStore($this->getStaticBlockForm(), $this->getCampaign()->getStores());
        return $this;
    }

    protected function _updateStaticBlockStoreThanks(){
        $this->_updateStaticBlockStore($this->getStaticBlockThanks(), $this->getCampaign()->getStores());
        return $this;
    }*/


    public function getPrefix(){
        return self::PREFIX_DATA;
    }

    /*protected function _beforeSave(){
        $this->loadNewContent();
        //zend_debug::dump($this->getData());die;
    }*/
}
