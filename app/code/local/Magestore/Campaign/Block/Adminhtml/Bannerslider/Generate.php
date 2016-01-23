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
 * @package     Magestore_ProductWidget
 * @copyright   Copyright (c) 2015 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

/**
 * Productwidget Adminhtml Block
 * 
 * @category    Magestore
 * @package     Magestore_ProductWidget
 * @author      Magestore Developer
 */
class Magestore_Campaign_Block_Adminhtml_Bannerslider_Generate extends Mage_Adminhtml_Block_Template
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function _prepareLayout(){
    	parent::_prepareLayout();
    	$this->setTemplate('campaign/generate.phtml');
    }

//    public function getWidget(){
//        if(!$this->getData('widget')){
//            $this->setData('widget',Mage::getModel('productwidget/widget')->load($this->getWidgetId()));
//        }
//        return $this->getData('widget');
//    }
//
//    public function getJsUrl(){
//    	$url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_JS);
//    	return Mage::helper('productwidget')->removeProtocol($url);
//    }
//
//    public function getEncodedId(){
//        return Mage::helper('productwidget')->encode($this->getWidgetId());
//    }

    


}