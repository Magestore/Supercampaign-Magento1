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
            'label'     => Mage::helper('campaign')->__('Select Popups'),
            'title'     => Mage::helper('campaign')->__('Select Popups'),
            'url' => $this->getUrl('*/adminhtml_campaign/getPopupGridTab', array('_current' => true,'id' => $this->getRequest()->getParam('id'))),
            'class' => 'ajax'
        ));

        $this->addTab('banner', array(
            'label'     => $this->__('Select Banners'),
            'title'     => $this->__('Select Banners'),
            'url' => $this->getUrl('*/adminhtml_campaign/getBannerGridTab', array('_current' => true,'id' => $this->getRequest()->getParam('id'))),
            'class' => 'ajax'
        ));

        //$this->addTab('countdown', 'campaign/adminhtml_campaign_edit_tab_countdown');



        return parent::_beforeToHtml();
    }
}