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
 * Bannerslider Edit Form Content Tab Block
 * 
 * @category 	Magestore
 * @package 	Magestore_Bannerslider
 * @author  	Magestore Developer
 */
class Magestore_Campaign_Block_Adminhtml_Bannerslider_Edit_Tab_Custom extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('customgrid');
        $this->setDefaultSort('banner_id');
        $this->setUseAjax(true);
        if ($this->getRequest()->getParam('id')) {
            $this->setDefaultFilter(array('in_custom' => 1));
        }
    }

//    protected function _addColumnFilterToCollection($column) {
//        if ($column->getId() == 'in_custom') {
//            $bannerIds = $this->_getSelectedBanners();
//
//            if (empty($bannerIds)) {
//                $bannerIds = 0;
//            }
//            if ($column->getFilter()->getValue()) {
//                $this->getCollection()->addFieldToFilter('banner_id', array('in' => $bannerIds));
//            } else {
//                if ($bannerIds) {
//                    $this->getCollection()->addFieldToFilter('banner_id', array('nin' => $bannerIds));
//                }
//            }
//        } else {
//            parent::_addColumnFilterToCollection($column);
//        }
//        return $this;
//    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('campaign/banner')->getCollection();

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('in_custom', array(
            'header_css_class' => 'a-center',
            'type' => 'checkbox',
            'name' => 'in_custom',
            'align' => 'center',
            'index' => 'banner_id',
            'values' => $this->_getSelectedBanners(),
        ));

        $this->addColumn('banner_id', array(
            'header' => Mage::helper('campaign')->__('ID'),
            'width' => '50px',
            'index' => 'banner_id',
            'type' => 'number',
        ));

        $this->addColumn('name', array(
            'header' => Mage::helper('campaign')->__('Name'),
            'index' => 'name'
        ));

        $this->addColumn('bannerslider_id', array(
            'header' => Mage::helper('campaign')->__('Current Slider'),
            'index' => 'bannerslider_id',
            'type' => 'options',
            'options' => Mage::getSingleton('campaign/select')->getOptionHash(),
        ));

//        $this->addColumn('start_time', array(
//            'header' => Mage::helper('campaign')->__('Start Date'),
//            'type' => 'datetime',
//            //'format' => 'yyyy-MM-dd H:m:s',
//            'index' => 'start_time'
//        ));
//
//        $this->addColumn('end_time', array(
//            'header' => Mage::helper('campaign')->__('End Date'),
//            'type' => 'datetime',
//            //'format' => 'yyyy-MM-dd H:m:s',
//            'index' => 'end_time'
//        ));

        $this->addColumn('click_url', array(
            'header' => Mage::helper('campaign')->__('Image Url'),
            'index' => 'click_url'
        ));

        $this->addColumn('image_alt', array(
            'header' => Mage::helper('campaign')->__('Image Alt'),
            'index' => 'image_alt'
        ));


        $this->addColumn('imagename', array(
            'header' => Mage::helper('campaign')->__('Image'),
            'align' => 'center',
            'width' => '200px',
            'index' => 'imagename',
            'renderer' => 'campaign/adminhtml_renderer_imagebanner'
        ));

        $this->addColumn('action_banner', array(
            'header' => Mage::helper('campaign')->__('Action'),
            'renderer' => 'campaign/adminhtml_renderer_edit',
            'index' => 'action_banner',
        ));

        $this->addColumn('order_banner_slider', array(
            'header' => Mage::helper('campaign')->__('Order'),
            'name' => 'order_banner_slider',
            'index' => 'order_banner_slider',
            'width' => 0,
            'editable' => true,
        ));

        return parent::_prepareColumns();
    }

    //return url
    public function getGridUrl() {
        return $this->getData('grid_url') ? $this->getData('grid_url') : $this->getUrl('*/*/customGrid', array('_current' => true, 'id' => $this->getRequest()->getParam('id')));
    }

    public function getRowUrl($row) {
        return '';
    }

    public function getSelectedSliderBanners() {

        $tm_id = $this->getRequest()->getParam('id');
        if (!isset($tm_id)) {
            return array();
        }
        $collection = Mage::getModel('campaign/banner')->getCollection();
        $collection->addFieldToFilter('bannerslider_id', $tm_id);

        $bannerIds = array();
        foreach ($collection as $obj) {
            $bannerIds[$obj->getId()] = array('order_banner_slider' => $obj->getOrderBanner());
        }
        return $bannerIds;
    }

    protected function _getSelectedBanners() {
        $banners = $this->getRequest()->getParam('banner');
        if (!is_array($banners)) {
            $banners = array_keys($this->getSelectedSliderBanners());
        }
        return $banners;
    }

}