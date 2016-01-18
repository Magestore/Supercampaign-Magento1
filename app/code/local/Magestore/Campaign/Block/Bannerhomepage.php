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
class Magestore_Campaign_Block_Bannerhomepage extends Mage_Core_Block_Template
{
    /**
     * prepare block's layout
     *
     * @return Magestore_Campaign_Block_Campaign
     */
    public function _prepareLayout()
    {
        $this->setTemplate('campaign/banner/home.phtml');
        return parent::_prepareLayout();
    }

    public function getBannerhomepages(){
        $md_banner = Mage::getModel('campaign/bannerhomepage')->getAvailable();
        return $md_banner;

    }


    public function getNumber_banner(){
        $countbanner = 0;
        $bnhomepage = $this->getBannerhomepages();
        foreach($bnhomepage as $banner){
            if($banner->getInputBannerhomepage() != ''){
            $countbanner = $countbanner+1;
            }
        }

        return $countbanner;
    }

//    public function getBannerhomepageactive(){
//        $bnactive = $this->getBannerUsing();
//        //zend_debug::dump($bnactive); die('duoc roi');
//        $model_banner = Mage::getModel('campaign/bannerhomepage')->getCollection();
//        //zend_debug::dump($model_banner); die('vao day roi');
//        return $model_banner;
//    }
//
//    public function getBannerUsing(){
//        $bannermenus = $this->getBannerhomepages();
//        //zend_debug::dump($bannermenus); die('gggg');
//        foreach($bannermenus as $banner){
//            $bnhomepage = $banner->getStatusbannerhomepage();
//            $bannermn = $banner->getBanner();
//            $idbanner = $banner->getIdbanner();
//        }
//
//        zend_debug::dump($idbanner); die('het roi');
//        return $idbanner;
//
//    }

}