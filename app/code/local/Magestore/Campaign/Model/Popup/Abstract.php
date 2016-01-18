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
abstract class Magestore_Campaign_Model_Popup_Abstract extends Mage_Core_Model_Abstract
implements Magestore_Campaign_Model_Popup_Interface
{
    const BLOCK_TYPE = 'campaign/popup_type_default'; //declare block that used as your block custom type
    const PREFIX_DATA = 'default';

    public function _construct()
    {
        parent::_construct();
    }

    abstract function getBlockType();


    /**
     * set type name for model type
     * @param $name
     * @return $this
     */
    public function setTypeCode($name){
        $this->_typeCode = $name;
        return $this;
    }

    public function getTypeCode(){
        if($this->_typeCode){
            return $this->_typeCode;
        }
        return '';
    }


    /**
     * get Identifier column of cms static block by static block id
     * @return mixed
     */
    //abstract function getCmsBlockIdentifier();


    public function setModelType($model){
        $this->_modelType = $model;
        return $this;
    }

    public function getModelType(){
        return $this->_modelType;
    }

    /**
     * fix alway get object of Magestore_Campaign_Model_Campaign
     * @return Magestore_Campaign_Model_Campaign
     */
    public function getCampaign(){
        $camp = parent::getCampaign();
        if(!is_object($camp)){
            $camp = new Magestore_Campaign_Model_Campaign();
        }
        return $camp;
    }


    /**
     * method getData for add prefix to attribute name
     * @param string $key
     * @param string | null $index
     * @return string
     */
    public function getDataPrefix($key='', $index=null){
        $data = array();
        if (''===$key) {
            $_data = parent::getData();
            foreach($_data as $k => $v){
                $data[$this->getPrefix().$k] = $v;
            }
            return $data;
        }
        $value = $this->getPrefix().parent::getData($key, $index);
        return $value;
    }

    /**
     * method for remove prefix if added
     * @param array $arr
     * @return $this
     */
    public function addDataPrefix(array $arr){
        foreach($arr as $index => $value) {
            if($this->getPrefix() != ''){
                if(strpos($index, $this->getPrefix()) === 0){
                    $index = substr($index, strlen($this->getPrefix())); //remove prefix string
                }
            }
            parent::setData($index, $value);
        }
        return $this;
    }

    public function addPrefixData(array $arr){
        foreach($arr as $index => $value) {
            if($this->getPrefix() != ''){
                if(strpos($index, $this->getPrefix()) === 0){
                    $index = substr($index, strlen($this->getPrefix())); //remove prefix string
                    parent::setData($index, $value);
                }
            }
        }
        return $this;
    }


    public function setDataPrefix($key, $value=null){
        if(is_array($key)) {
            foreach($key as $k => $v){
                if($this->getPrefix() != '') {
                    if (strpos($k, $this->getPrefix()) === 0) {
                        $k = substr($k, strlen($this->getPrefix())); //remove prefix string
                        $key[$k] = $v;
                    }
                }
            }
            parent::setData($key);
        } else {
            if($this->getPrefix() != '') {
                if (strpos($key, $this->getPrefix()) === 0) {
                    $key = substr($key, strlen($this->getPrefix())); //remove prefix string
                }
            }
            parent::setData($key, $value);
        }
        return $this;
    }

    /**
     * to change this return value rewrite it from child object
     * @return string
     */
    public function getPrefix(){
        return self::PREFIX_DATA;
    }

    /**
     * method to reset or load content from template
     * @return $this
     */
    public function applyNewContent(){
        return $this;
    }

    public function isResetTemplate(){
        if($this->getIsResetTemplate()){
            return true;
        }
        return false;
    }

    protected function _beforeSave(){
        //$this->saveStaticBlock();
        $this->applyNewContent();
        /*if($this instanceof Magestore_Campaign_Model_Popup_Type_Form){
            zend_debug::dump($this->getData());die;
        }*/
        parent::_beforeSave();
    }

    /**
     * get html with processor cms block
     * @return mixed
     */
    public function toHtml2(){
        return '';
    }
}