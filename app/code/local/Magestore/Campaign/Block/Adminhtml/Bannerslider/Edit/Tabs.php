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
 * Bannerslider Edit Tabs Block
 * 
 * @category 	Magestore
 * @package 	Magestore_Bannerslider
 * @author  	Magestore Developer
 */
class Magestore_Campaign_Block_Adminhtml_Bannerslider_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId('bannerslider_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('campaign')->__('Banner Item Information'));
    }

    /**
     * prepare before render block to html
     *
     * @return Magestore_Bannerslider_Block_Adminhtml_Bannerslider_Edit_Tabs
     */
    protected function _beforeToHtml() {
        $this->addTab('form_section', array(
            'label' => Mage::helper('campaign')->__('Banner Item Information'),
            'title' => Mage::helper('campaign')->__('Banner Item Information'),
            'content' => $this->getLayout()->createBlock('campaign/adminhtml_bannerslider_edit_tab_form')->toHtml(),
        ));
        if ($this->getRequest()->getParam('active_tab') == 'custom') {
            $this->addTab('banner_section', array(
                'label' => Mage::helper('campaign')->__('Banner of Slider'),
                'title' => Mage::helper('campaign')->__('Banner of Slider'),
                'url' => $this->getUrl('*/*/custom', array('_current' => true, 'id' => $this->getRequest()->getParam('id'))),
                'class' => 'ajax',
                'active' => true,
            ));
        } else {
            $this->addTab('banner_section', array(
                'label' => Mage::helper('campaign')->__('Image(s) of Banner'),
                'title' => Mage::helper('campaign')->__('Image(s) of Banner'),
                'url' => $this->getUrl('*/*/custom', array('_current' => true, 'id' => $this->getRequest()->getParam('id'))),
                'class' => 'ajax',
            ));
        }
        return parent::_beforeToHtml();
    }

}