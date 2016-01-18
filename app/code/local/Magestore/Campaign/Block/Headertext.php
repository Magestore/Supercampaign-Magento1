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
class Magestore_Campaign_Block_Headertext extends Mage_Core_Block_Template
{
    /**
     * prepare block's layout
     *
     * @return Magestore_Campaign_Block_Campaign
     */

    public function _prepareLayout()
    {
        $this->setTemplate('campaign/header_text.phtml');
        return parent::_prepareLayout();
    }


    /**
     * get all header text model of all campaign available
     * @return array Header text model
     */
    public function getHeadertexts(){
        return Mage::getModel('campaign/headertext')->getAvailable();
    }

    /**
     * get html of header_texts as a array
     * @return array
     */
    public function getHtml(){
        $headertexts = $this->getHeadertexts();

        $html = array();
        foreach($headertexts as $object){
            //transfer data to Countdown header block
            if(!Mage::registry('headertext')){
                Mage::register('headertext', $object); //transfer $object to countdownheader block
            }
            if(!Mage::registry('campaign')){
                Mage::register('campaign', $object->getCampaign()); //transfer $object to countdownheader block
            }
            $html[] = $object->toHtml();
        }
        return $html;
    }

    public function isActive(){
        $header_texts = $this->getHeadertexts();
        if(count($header_texts)){
            return true;
        }
        return false;
    }


    public function getCampaign(){
        if(!is_object($this->_campaign)){
            $this->_campaign = Mage::getModel('campaign/campaign')->getActiveCampaign();
        }
        return $this->_campaign;
    }


}
