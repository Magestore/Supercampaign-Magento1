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
 * @category 	Magestore
 * @package 	Magestore_Bannerslider
 * @copyright 	Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license 	http://www.magestore.com/license-agreement.html
 */

/**
 * Bannerslider Block
 * 
 * @category 	Magestore
 * @package 	Magestore_Bannerslider
 * @author  	Magestore Developer
 */
class Magestore_Campaign_Block_Bannerslider extends Mage_Core_Block_Template {

    protected function _toHtml() {         
        if(!Mage::getStoreConfig('campaign/general/enable')){
            return '';
        }
        $collection = null;
        $banners = array();        
        $collection = Mage::getModel('campaign/bannerslider')->getCollection()
                ->addFieldToFilter('status', 0)
                ->addFieldToFilter('position', array(
                    'in' => array($this->getBlockPosition(), $this->getCateBlockPosition(), $this->getPopupPosition(), $this->getBlocknotePosition()),
                ));
        foreach ($collection as $item) {
            $block = $this->getLayout()->createBlock('campaign/default')
                            ->setTemplate('bannercampaign/bannerslider.phtml')->setSliderData($item);
            $banners[] = $block->renderView();
        }
        return implode('', $banners);
    }

    public function getIsHomePage() {
        return $this->getUrl('') == $this->getUrl('*/*/*', array('_current' => true, '_use_rewrite' => true));
    }
    

}