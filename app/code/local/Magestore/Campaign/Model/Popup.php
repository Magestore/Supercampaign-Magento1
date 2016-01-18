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
class Magestore_Campaign_Model_Popup extends Magestore_Campaign_Model_Popup_Abstract
{
    const PREFIX_DATA = 'popup_';

    public $_modelType; //model type of popup

    const PREFIX = 'popup_'; //in future will use this, prefix add before each attribute name

    const TEMPLATE_GROUP = 'popup';
    const TEMPLATE_TYPE_FORM = 'form';
    const TEMPLATE_TYPE_STATIC = 'static';
    const TEMPLATE_TYPE_GAME = 'game';

    public function _construct()
    {
        parent::_construct();
        $this->_init('campaign/popup');
    }

    public function getBlockType(){
        return $this->getModelType()->getBlockType();
    }

    /**
     * load and get popup type
     * @param $id
     * @return Magestore_Campaign_Model_Popup_Abstract | $this
     */
    public function load($id, $field = null){
        parent::load($id, $field);
        return $this;
    }

    /**
     * need refresh model popup type to this object
     * @param string $type popup type code
     * @return $this
     * @throws Exception
     */
    public function reloadModelType($type){
        $this->setPopupType($type);
        $this->_loadModelType();
        return $this;
    }

    /**
     * @param $id campaign
     * @return Magestore_Campaign_Model_Popup_Abstract
     */
    public function getPopupByCampaign($id){
        parent::load($id, 'campaign_id');
        return $this->load($this->getId());
    }

    /**
     * @return Magestore_Campaign_Model_Campaign
     */
    public function loadCampaign(){
        $cpID = $this->getCampaignId();
        $campaign = Mage::getModel('campaign/campaign')->load($cpID);
        $this->setCampaign($campaign);
        return $this->getCampaign();
    }

    public function save(){
        if(!is_object($this->_modelType)){
            $this->_loadModelType();
        }
        parent::save();
        //save popup model type
        $dt = array();
        foreach ($this->_data as $key => $value) {
            if(!is_array($value) && !is_object($value)){
                $dt[$key] = $value;
            }
        }
        //save popup type data
        $this->_modelType->addPrefixData($dt);
        $this->_modelType
            ->setCampaign($this->loadCampaign())
            ->setPopupId($this->getId())
            ->save();
        return $this;
    }

    /**
     * install abstract get cms block identifier
     * @return mixed
     */
    /*public function getCmsBlockIdentifier(){
        return $this->getModelType()->getCmsBlockIdentifier();
    }*/


    /**
     * get popup has accepted by includes - excludes and is active
     * @return array
     */
    public function getAvailable(){
        $campaigns = Mage::getModel('campaign/campaign')->getActiveCampaignAll();
        foreach($campaigns as $camp){
            $pop = $camp->getPopup();
            if($pop->getStatus() == 1 && Mage::helper('campaign')
                    ->checkAccept($pop->getIncludePage(),
                        $pop->getExcludePage().';checkout/cart/;checkout/onepage/;onestepcheckout/index/index')){
                return $pop;
            }
        }
        return false;
    }

    /**
     * get all data by data mixed with popup model type
     * @return mixed
     */
    public function getAllData(){
        if(is_object($this->getModelType())){
            $data1 = $this->getModelType()->getDataPrefix();
            //zend_debug::dump($data1);die;
            $dt = array();
            $data2 = $this->getData();
            foreach ($data2 as $key => $value) {
                if(!is_array($value) && !is_object($value)){
                    $dt[$key] = $value;
                }
            }
            $data2 = $dt;
            $data = $data1 + $data2;
            return $data;
        }
        return $this->getData();
    }

    /**
     * Alias function for getPopupType()
     * @return mixed
     */
    function getType(){
        return $this->getPopupType();
    }

    public function getModelType(){
        if(!$this->_modelType || (is_object($this->_modelType) && $this->_modelType->getId() == null)){
            $this->_loadModelType();
        }
        return $this->_modelType;
    }

    /**
     * to call get Model Type Magestore_Campaign_Model_Popup_Abstract
     * get model Magestore_Campaign_Model_Popup_Type*
     * @return $this
     * @throws Exception
     */
    protected function _loadModelType(){
        if(is_string($this->getPopupType()) && $this->getPopupType()){
            $this->_modelType = Mage::getModel('campaign/popup_type_'.strtolower($this->getPopupType()));
            if(!is_object($this->_modelType)){
                Mage::throwException('Popup type: campaign/popup_type_'.$this->getPopupType().' not found!');
            }
            $this->_modelType->load($this->getId(), 'popup_id');
            $this->setModelType($this->_modelType);
        }else{
            $this->_modelType = $this;
            //throw new Exception(Mage::helper('campaign')->__('Magestore_Campaign_Model_Popup is null popup_type'));
        }

        return $this;
    }

    protected function _getTypeModel(){
        if(!is_object($this->_modelType)){
            $this->_loadModelType();
        }
        if(!is_object($this->_modelType)){
            throw new Exception(Mage::helper('campaign')->__('Magestore_Campaign_Model_Popup::_modelType is null object'));
        }
        return $this->_modelType;
    }

    protected function _afterLoad(){
        //load popup type model
        if($this->getPopupType() == ''){
            return $this;
        }
        $this->_loadModelType();
    }

    /*public function saveStaticBlock(){
        return $this;
    }*/

    public function loadNewContent(){
        return $this;
    }



    /**
     * TODO: need test
     */
    protected function _beforeDelete(){
        try{
            $this->_getTypeModel()->delete();
        }catch (Exception $e){
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('campaign')->__('Magestore_Campaign_Model_Popup::_beforeDelete can\'t delete model type and cms/block; %s', $e->getMessage()));
        }
    }


}