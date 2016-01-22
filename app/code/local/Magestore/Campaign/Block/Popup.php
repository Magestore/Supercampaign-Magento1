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
        if($this->getPopup()){
            $this->setBlockType($this->getPopup()->getBlockType());
        }
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
        $this->setTemplate('campaign/popup.phtml');
        return $this;
    }

    /*protected function _toHtml(){

    }*/

    public function getPopHtml(){
        $block = $this->getBlockType();
        if($block){
            $blockObject = $this->getLayout()->createBlock($block);
            if($blockObject){
                return $blockObject->toHtml();
            }
        }
        return '';
    }

    /**
     * get Popup model by search available
     * @return object | bool
     */
   /* public function getPopup()
    {
        if (!is_object($this->_popup)) {
            if(Mage::registry('campaign_popup')){
                $this->_popup = Mage::registry('campaign_popup');
            }else{
                $pop = $this->getAvailable();
                if(is_object($pop)){
                    $this->_popup = $pop;
                    if($this->_popup->getId() == null){
                        return false;
                    }
                }else{
                    return false;
                }
            }
            if(!Mage::registry('campaign_popup')){
                Mage::register('campaign_popup', $this->_popup);
            }
        }
        return $this->_popup;
    }*/

    /**
     * get popup has accepted by includes - excludes and is active
     * @return array
     */
    public function getAvailable(){
        return Mage::getModel('campaign/popup')->getAvailable();
    }


    public function getData(){
        return array(

            'devices' => 'desktop,mobile, tablet, all'
        );
    }

    public function getPopup(){

        return new Varien_Object($this->getData());
    }
}
