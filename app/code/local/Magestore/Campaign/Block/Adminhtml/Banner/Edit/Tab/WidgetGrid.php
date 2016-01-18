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
class Magestore_Campaign_Block_Adminhtml_Banner_Edit_Tab_WidgetGrid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('widget_grid');
        $this->setDefaultSort('widget_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        //$this->setDefaultFilter(array('widget_id' => 1));
    }

    /**
     * prepare collection for block to display
     *
     * @return Magestore_Campaign_Block_Adminhtml_Widget_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('campaign/widget')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare columns for this grid
     *
     * @return Magestore_Campaign_Block_Adminhtml_Widget_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('grid_checkbox', array(
            'header_css_class' => 'a-center',
            'type' => 'checkbox',
            'index' => 'widget_id',
            //'name' => 'grid_checkbox',
            'align' => 'center',
            'width'     => '50px',
            'values' => $this->_selectedId(),
        ));

        $this->addColumn('widget_id', array(
            'header'    => Mage::helper('campaign')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'widget_id',
        ));

        $this->addColumn('title', array(
            'header'    => Mage::helper('campaign')->__('Title'),
            'align'     =>'left',
            'index'     => 'title',
            'width'     => '450px',
        ));

        $this->addColumn('type', array(
            'header'    => Mage::helper('campaign')->__('Widget Type'),
            'index'     => 'type',
            'width'     => '100px',
        ));

        $this->addColumn('status', array(
            'header'    => Mage::helper('campaign')->__('Status'),
            'align'     => 'left',
            'width'     => '80px',
            'index'     => 'status',
            'type'        => 'options',
            'options'     => array(
                1 => 'Enabled',
                2 => 'Disabled',
            ),
        ));



        //$this->addExportType('*/*/exportCsv', Mage::helper('campaign')->__('CSV'));
        //$this->addExportType('*/*/exportXml', Mage::helper('campaign')->__('XML'));

        return parent::_prepareColumns();
    }

    /**
     * prepare mass action for this grid
     *
     * @return Magestore_Campaign_Block_Adminhtml_Widget_Grid
     */
    /*protected function _prepareMassaction()
    {
        $this->setMassactionIdField('mass_id');
        $this->getMassactionBlock()->setFormFieldName('mass_ids');
        return $this;
    }*/

    /**
     * Ajax call get grid html
     * @return mixed
     */
    public function getGridUrl() {
        return $this->getUrl('*/adminhtml_banner/ajaxGrid', array(
            '_current' => true,
            'id' => $this->getRequest()->getParam('id')
        ));
    }

    /**
     * get url for each row in grid
     *
     * @return string
     */
    public function getRowUrl($row)
    {
        return '';//$this->getUrl('*/*/detail', array('id' => $row->getId()));
    }

    public function getSerializeData(){
        $selected = Mage::registry('widget_reloaded_ids');
        if(!$selected){
            $data = array();
            if (Mage::getSingleton('adminhtml/session')->getWidgetBannerData()) {
                $data = Mage::getSingleton('adminhtml/session')->getWidgetBannerData();
            } elseif (Mage::registry('widget_banner_data')) {
                $data = Mage::registry('widget_banner_data')->getData();
            }
            $selected = explode('&', $data['widget_selected_ids']);
        }
        return $selected;
    }

    public function _selectedId(){
        $selected = $this->getSerializeData();
        $option = array();
        if(is_array($selected)){
            foreach($selected as $key => $id){
                $option[] = $id;
            }
        }
        return $option;
    }
}