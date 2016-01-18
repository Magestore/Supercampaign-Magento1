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

class Magestore_Campaign_Block_Banner extends Mage_Core_Block_Template
{

    /**
     * prepare block's layout
     *
     * @return Magestore_Campaign_Block_Campaign
     */

    public function _prepareLayout()
    {
        $this->setTemplate('campaign/banner/listing.phtml');
        return parent::_prepareLayout();
    }

    public function getBannerlistpages(){
        return $this->getBanner();
    }


    public function getNumber_banner(){
        return 0;
    }

    public function getBannermenuactive(){
        $bnactive = $this->getBannerUsing();
        $model_banner = Mage::getModel('campaign/bannerlistpage')->load($bnactive);
        return $model_banner;
    }

    public function getBannerUsing(){
        $bannerlistpages = $this->getBannerlistpages();
        foreach($bannerlistpages as $banner){
            $bnlistpage = $banner->getStatusbannerlistpage();
            $bannermn = $banner->getBanner();
            $idbanner = $banner->getIdbanner();
            if($bnlistpage == '1' && $bannermn!=''){
                break;
            }
        }
        return $idbanner;

    }


    /*new update 0.2.0*/
    public function getBanner(){
        return Mage::getModel('campaign/bannerlistpage')->getAvailable();
    }

}