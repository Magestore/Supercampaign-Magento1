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
class Magestore_Campaign_Block_Adminhtml_Widget_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('widget_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('campaign')->__('Widget Tabs'));
    }
    
    /**
     * prepare before render block to html
     *
     * @return Magestore_Campaign_Block_Adminhtml_Widget_Edit_Tabs
     */
    protected function _beforeToHtml()
    {
        $this->addTab('general', array(
            'label'     => Mage::helper('campaign')->__('Widget'),
            'title'     => Mage::helper('campaign')->__('Widget infomation'),
            'content'   => $this->getLayout()
                ->createBlock('campaign/adminhtml_widget_edit_tab_form')
                ->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}