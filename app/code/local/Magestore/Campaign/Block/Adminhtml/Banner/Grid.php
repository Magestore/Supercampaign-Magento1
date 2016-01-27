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
 * Campaign Grid Block
 * 
 * @category    Magestore
 * @package     Magestore_Campaign
 * @author      Magestore Developer
 */
class Magestore_Campaign_Block_Adminhtml_Banner_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct() {
        parent::__construct();
        $this->setId('bannerGrid');
        $this->setDefaultSort('banner_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {

        $storeId = $this->getRequest()->getParam('store');
        $collection = Mage::getModel('campaign/banner')->getCollection()->setStoreId($storeId);

        $collection->getSelect()->joinLeft(array('table_alias' => $collection->getTable('campaign/bannerslider')), 'main_table.bannerslider_id = table_alias.bannerslider_id', array('bannerslider_title' => 'table_alias.title'));
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('banner_id', array(
            'header' => Mage::helper('campaign')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'filter_index' => 'main_table.banner_id',
            'index' => 'banner_id',
        ));

        $this->addColumn('name', array(
            'header' => Mage::helper('campaign')->__('Name'),
            'align' => 'left',
            'width' => '100px',
            'index' => 'name',
        ));

        $this->addColumn('click_url', array(
            'header' => Mage::helper('campaign')->__('URL'),
            'align' => 'left',
            'index' => 'click_url',
        ));

        $this->addColumn('bannerslider_title', array(
            'header' => Mage::helper('campaign')->__('Slider'),
            'align' => 'left',
            'filter_index' => 'table_alias.title',
            'index' => 'bannerslider_title',
        ));

//        $this->addColumn('start_time', array(
//            'header' => Mage::helper('campaign')->__('Start Date'),
//            'align' => 'left',
//            'type' => 'datetime',
//            'index' => 'start_time',
//        ));
//
//        $this->addColumn('end_time', array(
//            'header' => Mage::helper('campaign')->__('End Date'),
//            'align' => 'left',
//            'type' => 'datetime',
//            'index' => 'end_time',
//        ));


        $this->addColumn('status', array(
            'header' => Mage::helper('campaign')->__('Status'),
            'align' => 'left',
            'width' => '80px',
            'filter_index' => 'main_table.status',
            'index' => 'status',
            'type' => 'options',
            'options' => array(
                0 => 'Enabled',
                1 => 'Disabled',
            ),
        ));

        $this->addColumn('action', array(
            'header' => Mage::helper('campaign')->__('Action'),
            'width' => '50px',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('campaign')->__('Edit'),
                    'url' => array('base' => '*/*/edit'),
                    'field' => 'id'
                )
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
            'is_system' => true,
        ));
        $this->addColumn('imagename', array(
            'header' => Mage::helper('campaign')->__('Image'),
            'align' => 'center',
            'width' => '200px',
            'index' => 'imagename',
            'renderer' => 'campaign/adminhtml_renderer_imagebanner'
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('campaign')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('campaign')->__('XML'));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction() {

        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('banner');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('campaign')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('campaign')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('campaign/status')->getOptionArray();

        array_unshift($statuses, array('label' => '', 'value' => ''));
        $this->getMassactionBlock()->addItem('status', array(
            'label' => Mage::helper('campaign')->__('Change status'),
            'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('campaign')->__('Status'),
                    'values' => array(
                        0 => 'Enabled',
                        1 => 'Disabled',
                    )
                )
            )
        ));
        return $this;
    }

    public function getRowUrl($row) {

        return $this->getUrl('*/*/edit', array('id' => $row->getId(), 'store' => $this->getRequest()->getParam('store')));
    }

}