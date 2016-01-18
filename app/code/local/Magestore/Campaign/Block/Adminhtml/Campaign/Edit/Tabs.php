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
 * Campaign Edit Tabs Block
 * 
 * @category    Magestore
 * @package     Magestore_Campaign
 * @author      Magestore Developer
 */
class Magestore_Campaign_Block_Adminhtml_Campaign_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('campaign_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('campaign')->__('Campaign Tabs'));
    }
    
    /**
     * prepare before render block to html
     *
     * @return Magestore_Campaign_Block_Adminhtml_Campaign_Edit_Tabs
     */
    protected function _beforeToHtml()
    {
        $this->addTab('general', array(
            'label'     => Mage::helper('campaign')->__('General'),
            'title'     => Mage::helper('campaign')->__('General Tab'),
            'content'   => $this->getLayout()
                                ->createBlock('campaign/adminhtml_campaign_edit_tab_general')
                                ->toHtml(),
        ));

        $this->addTab('popup', array(
            'label'     => Mage::helper('campaign')->__('Popup'),
            'title'     => Mage::helper('campaign')->__('Popup Tab'),
            'content'   => $this->getLayout()
                ->createBlock('campaign/adminhtml_campaign_edit_tab_popup')
                ->toHtml(),
        ));

        $this->addTab('sidebar', array(
            'label'     => Mage::helper('campaign')->__('Sidebar'),
            'title'     => Mage::helper('campaign')->__('Sidebar Tab'),
            'content'   => $this->getLayout()
                ->createBlock('campaign/adminhtml_campaign_edit_tab_sidebar')
                ->toHtml(),
        ));

        /*$this->addTab('widget_banner', array(
            'label'     => Mage::helper('campaign')->__('Widget Banner'),
            'title'     => Mage::helper('campaign')->__('Widget Banner'),
            'content'   => $this->getLayout()
                ->createBlock('campaign/adminhtml_campaign_edit_tab_widgetBanner')
                ->toHtml(),
        ));*/

        /*$this->addTab('header_text', array(
            'label'     => Mage::helper('campaign')->__('Header Text'),
            'title'     => Mage::helper('campaign')->__('Header Tab'),
            'content'   => $this->getLayout()
                ->createBlock('campaign/adminhtml_campaign_edit_tab_headerText')
                ->toHtml(),
        ));

        $this->addTab('image_home', array(
            'label'     => Mage::helper('campaign')->__('Image Home Page'),
            'title'     => Mage::helper('campaign')->__('Image Home Page Tab'),
            'content'   => $this->getLayout()
                ->createBlock('campaign/adminhtml_campaign_edit_tab_imageHome')
                ->toHtml(),
        ));

        $this->addTab('image_listingpage', array(
            'label'     => Mage::helper('campaign')->__('Image Listing Page'),
            'title'     => Mage::helper('campaign')->__('Image Listing Page Tab'),
            'content'   => $this->getLayout()
                    ->createBlock('campaign/adminhtml_campaign_edit_tab_imageListingpage')
                    ->toHtml(),
        ));

        $this->addTab('image_megamenu', array(
            'label'     => Mage::helper('campaign')->__('Image Mega Menu'),
            'title'     => Mage::helper('campaign')->__('Image Mega Menu Tab'),
            'content'   => $this->getLayout()
                ->createBlock('campaign/adminhtml_campaign_edit_tab_imageMegamenu')
                ->toHtml(),
        ));*/
        $this->addTab('widget_banner', array(
            'label' => $this->__('Banners'),
            'url' => $this->getUrl('*/adminhtml_banner/getGridTab', array('_current' => true,'id' => $this->getRequest()->getParam('id'))),
            'class' => 'ajax'
        ));

        $this->addTab('countdown', 'campaign/adminhtml_campaign_edit_tab_countdown');



        return parent::_beforeToHtml();
    }
}