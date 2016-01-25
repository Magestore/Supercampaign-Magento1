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
        $this->setTemplate('campaign/popup.phtml');
        parent::_prepareLayout();
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


    public function getPopup(){

        return new Varien_Object($this->getData());
    }

    public function getAllDataPopupActive(){
        $pops = $this->getAllPopupAvailable();
        foreach($pops as $pop){
            return $pop;
        }
    }

    public function getAllPopupAvailable(){
        return Mage::getModel('campaign/supercampaign')->getPopups();
    }
}
