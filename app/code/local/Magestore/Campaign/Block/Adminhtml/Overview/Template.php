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
class Magestore_Campaign_Block_Adminhtml_Overview_Template extends Mage_Core_Block_Template
{
    public function __construct()
    {
        parent::__construct();
    }

    public function setId($id){
        $this->_id = $id;
        return $this;
    }

    public function getId(){
        return $this->_id;
    }

    /**
     * object script name to create
     */
    public function setObject($name){
        $this->_object = $name;
        return $this;
    }

    public function getObject(){
        return $this->_object;
    }

    public function setOptionImages($options){
        $this->_images = $options;
        return $this;
    }

    public function getOptionImages(){
        return $this->_images;
    }

    protected function _isLoadJs(){
        if(Mage::registry('campaign_popup_isLoadJs_overview')){ //sample with popup_overview js to resole conflict
            return true;
        }
        return false;
    }

    protected function _setIsLoadJs($flag = true){
        Mage::register('campaign_popup_isLoadJs_overview', 1); //sample with popup_overview js to resole conflict
        return $this;
    }

    protected function _prepareLayout(){
        $this->setTemplate('campaign/overview.phtml');
        return $this;
    }
}