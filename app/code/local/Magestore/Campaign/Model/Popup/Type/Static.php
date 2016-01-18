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
class Magestore_Campaign_Model_Popup_Type_Static extends Magestore_Campaign_Model_Popup_Abstract
{
    const BLOCK_TYPE = 'campaign/popup_type_default';

    const PREFIX_DATA = 'static_'; //prefix add before each attribute name
    const TYPE_CODE = 'static';

    const PREFIX = 'static_'; //in future will use this, prefix add before each attribute name

    const TEMPLATE_GROUP = 'popup';
    const TEMPLATE_TYPE = 'static';

    public function _construct()
    {
        parent::_construct();
        $this->setTypeCode(self::TYPE_CODE);
        $this->_init('campaign/popup_type_'.strtolower($this->getTypeCode()));
    }

    public function getBlockType(){
        return self::BLOCK_TYPE;
    }

    /**
     * get html with processor cms block
     * @return mixed
     */
    public function toHtml(){
        return Mage::helper('campaign')->convertContentToHtml($this->getContent());
    }

    /**
     * load new content from template when save or update template id
     * @return $this
     */
    public function applyNewContent(){
        if($this->isSavedNewTemplate() || $this->isChangeTemplate() || $this->isResetTemplate() === true){
            $this->setContent($this->getTemplate()->getContent());
        }
        return $this;
    }

    /**
     * @return Magestore_Campaign_Model_Popup_Template
     */
    protected function getTemplate(){
        $templateId = $this->getTemplateId();
        if(is_numeric($templateId)){
            return Mage::getModel('campaign/template')->load($templateId);
        }
        return new Magestore_Campaign_Model_Popup_Template();
    }

    public function isChangeTemplate(){
        $templateId = $this->getTemplateId();
        if(is_numeric($templateId) && $this->getId() != null){
            $popup_form = Mage::getModel('campaign/popup_type_static')->load($this->getId());
            if($popup_form->getTemplateId() !== $templateId){
                return true;
            }
        }
        return false;
    }

    /**
     * check condition able to create new template
     * @return bool
     */
    public function isSavedNewTemplate(){
        return $this->_isSaveNewTemplate();
    }

    /**
     * check condition able to create new template
     * is new if not have static_block id in own table or this is create new one
     * @return bool
     */
    protected function _isSaveNewTemplate(){
        $templateId = $this->getTemplateId();
        if(($this->getCampaign()->getId() == '' && is_numeric($templateId) && $this->getContent() == null) || ($this->getId() == null)){
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

    public function getPrefix(){
        return self::PREFIX_DATA;
    }


}