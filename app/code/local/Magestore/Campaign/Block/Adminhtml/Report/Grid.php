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
 * Bannerslider Grid Block
 * 
 * @category 	Magestore
 * @package 	Magestore_Bannerslider
 * @author  	Magestore Developer
 */
class Magestore_Campaign_Block_Adminhtml_Report_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('reportGrid');
        $this->setDefaultSort('report_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * prepare collection for block to display
     *
     * @return Magestore_Bannerslider_Block_Adminhtml_Bannerslider_Grid
     */
    protected function _prepareCollection() {
        $filter = Mage::app()->getRequest()->getParam('filter');
        $date = Mage::helper('adminhtml')->prepareFilterString($filter);
        $collection = Mage::getModel('campaign/report')->getCollection();
        if (isset($date['report_from']) && isset($date['report_to'])) {
            $collection->addFieldtoFilter('date_click', array('from' => $date['report_from'],
                'to' => $date['report_to'],
                'date' => true,));
        }
        $collection->getSelect()->joinLeft(array('table_banner' => $collection->getTable('campaign/banner')), 'main_table.banner_id = table_banner.banner_id', array('banner_name' => 'table_banner.name', 'banner_url' => 'table_banner.click_url'));
        $collection->getSelect()->joinLeft(array('table_slider' => $collection->getTable('campaign/bannerslider')), 'main_table.bannerslider_id = table_slider.bannerslider_id', array('slider_title' => 'table_slider.title'));
        $collection->getSelect()
                ->columns('SUM(main_table.clicks) AS banner_click')
                ->columns('SUM(main_table.impmode) AS banner_impress')
                ->group('main_table.bannerslider_id')
                ->group('main_table.banner_id');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare columns for this grid
     *
     * @return Magestore_Bannerslider_Block_Adminhtml_Bannerslider_Grid
     */
    protected function _prepareColumns() {
        $this->addColumn('report_id', array(
            'header' => Mage::helper('campaign')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'report_id',
        ));

        $this->addColumn('banner_name', array(
            'header' => Mage::helper('campaign')->__('Item'),
            'align' => 'left',
            'filter_index' => 'table_banner.name',
            'index' => 'banner_name',
        ));
        $this->addColumn('banner_url', array(
            'header' => Mage::helper('campaign')->__('URL'),
            'align' => 'left',
            'filter_index' => 'table_banner.click_url',
            'index' => 'banner_url',
        ));
        $this->addColumn('slider_title', array(
            'header' => Mage::helper('campaign')->__('Slider'),
            'align' => 'left',
            'filter_index' => 'table_slider.title',
            'index' => 'slider_title',
        ));
        $this->addColumn('banner_click', array(
            'header' => Mage::helper('campaign')->__('Clicks'),
            'align' => 'left',
            'index' => 'banner_click',
            'type' => 'number',
            'filter_index' => 'main_table.clicks',
            'width' => '200px',
        ));

        $this->addColumn('banner_impress', array(
            'header' => Mage::helper('campaign')->__('Impressions'),
            'align' => 'left',
            'index' => 'banner_impress',
            'filter_index' => 'main_table.impmode',
            'type' => 'number',
            'width' => '200px',
        ));
        $this->addColumn('imagename', array(
            'header' => Mage::helper('campaign')->__('Image'),
            'align' => 'center',
            'width' => '70px',
            'index' => 'imagename',
            'renderer' => 'campaign/adminhtml_renderer_imagereport'
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('campaign')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('campaign')->__('XML'));

        return parent::_prepareColumns();
    }

    /**
     * get url for each row in grid
     *
     * @return string
     */
    public function getRowUrl($row) {
        return '';
    }

    public function getCsv() {
        $csv = '';
        $this->_isExport = true;
        $this->_prepareGrid();
        $this->getCollection()->getSelect()->limit();
        $this->getCollection()->setPageSize(0);
        $this->getCollection()->load();
        $this->_afterLoadCollection();

        $data = array();
        $data[] = '"' . Mage::helper('campaign')->__('ID') . '"';
        $data[] = '"' . Mage::helper('campaign')->__('Banner') . '"';
        $data[] = '"' . Mage::helper('campaign')->__('URL') . '"';
        $data[] = '"' . Mage::helper('campaign')->__('Slider') . '"';
        $data[] = '"' . Mage::helper('campaign')->__('Clicks') . '"';
        $data[] = '"' . Mage::helper('campaign')->__('Impression') . '"';
        $data[] = '"' . Mage::helper('campaign')->__('Date') . '"';
        $csv.= implode(',', $data) . "\n";

        foreach ($this->getCollection() as $item) {
            $data = Mage::helper('campaign')->getValueToCsv($item);
            $csv.= $data . "\n";
        }
        return $csv;
    }

    public function getXml() {
        $this->_isExport = true;
        $this->_prepareGrid();
        $this->getCollection()->getSelect()->limit();
        $this->getCollection()->setPageSize(0);
        $this->getCollection()->load();
        $this->_afterLoadCollection();
        $indexes = array();
        foreach ($this->_columns as $column) {
            if (!$column->getIsSystem()) {
                $indexes[] = $column->getIndex();
            }
        }
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml.= '<items>';
        foreach ($this->getCollection() as $item) {
            $xml .= '<item>';
            foreach ($this->_columns as $column) {
                if (!$column->getIsSystem()) {
                    if ($column->getIndex() == 'imagename')
                        continue;
                    $xml .= "<" . $column->getIndex() . "><![CDATA[";
                    $xml .= Mage::helper('campaign')->getValueToXml($column->getIndex(), $item);
                    $xml .= "]]></" . $column->getIndex() . ">";
                }
            }
            $xml .= '</item>';
        }
        $xml.= '</items>';
        return $xml;
    }

}