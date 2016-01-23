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
 * Campaign Edit Form Content Tab Block
 * 
 * @category    Magestore
 * @package     Magestore_Campaign
 * @author      Magestore Developer
 */
class Magestore_Campaign_Block_Adminhtml_Campaign_Edit_Tab_Banner_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $campaign_id = $this->getRequest()->getParam('id');
        $collection = Mage::getModel('campaign/bannerslider')->getCollection();
        $collection->getSelect()
            ->joinLeft(array('campaign'=>$collection->getTable('campaign/campaign')),
                'main_table.campaign_id = campaign.campaign_id', '')
            ->columns(array('campaign_name'=>'campaign.name'))
            ->group('main_table.bannerslider_id');
        $filter = Mage::registry('banner_reloaded_ids');
        if(!isset($filter)){//if reset no filter
            $selected_id = $this->_selectedId();
            if(!empty($selected_id)){
                $collection->addFieldToFilter('bannerslider_id', array('in'=>$selected_id));
            }
        }
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
            'index' => 'bannerslider_id',
            //'name' => 'grid_checkbox',
            'align' => 'center',
            'width'     => '50px',
            'values' => $this->_selectedId(),
        ));

        /*$this->addColumn('widget_banner_id', array(
            'header'    => Mage::helper('campaign')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'widget_banner_id',
        ));*/

        $this->addColumn('title', array(
            'header'    => Mage::helper('campaign')->__('Banner title'),
            'align'     => 'left',
            'width'     => '220px',
            'index'     => 'title',
            //'renderer'  => 'Magestore_Campaign_Block_Adminhtml_Banner_Grid_Renderer_BannerType',
            //'filter' => false,
        ));

        /*$this->addColumn('in_widgets', array(
            'header'    => Mage::helper('campaign')->__('In Widgets'),
            'index'     => 'widget_selected_ids',
            //'width'     => '220px',
            'renderer' => 'Magestore_Campaign_Block_Adminhtml_Banner_Grid_Renderer_InWidgets',
            'filter_condition_callback' => array($this, '_filterInWidgets'),
        ));

        $this->addColumn('in_campaign', array(
            'header'    => Mage::helper('campaign')->__('In Campaign'),
            'index'     => 'campaign_name',
            'width'     => '220px',
            'filter_condition_callback' => array($this, '_filterInCampaign'),
        ));*/

        $this->addColumn('status', array(
            'header'    => Mage::helper('campaign')->__('Status'),
            'align'     => 'left',
            'width'     => '80px',
            'index'     => 'status',
            'filter_index'=>'main_table.status',
            'type'        => 'options',
            'options'     => array(
                1 => 'Enabled',
                0 => 'Disabled',
            ),
        ));

        $this->addColumn('action',
            array(
                'header'    =>    Mage::helper('campaign')->__('Action'),
                'width'        => '100',
                'type'        => 'action',
                'getter'    => 'getId',
                'actions'    => array(
                    array(
                        'caption'    => Mage::helper('campaign')->__('Edit'),
                        'url'        => array('base'=> '*/*/edit'),
                        'onclick' => 'return saveAndContinueEdit();',
                        'field'        => 'id'
                    )),
                'filter'    => false,
                'sortable'    => false,
                'index'        => 'stores',
                'is_system'    => true,
            ));

        $this->addExportType('*/*/exportCsv', Mage::helper('campaign')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('campaign')->__('XML'));

        return parent::_prepareColumns();
    }

    /*protected function _filterInWidgets($collection, $column){
        $field = ( $column->getFilterIndex() ) ? $column->getFilterIndex() : $column->getIndex();
        $cond = $column->getFilter()->getCondition();
        if ($field && isset($cond)) {
            $widgets = Mage::getModel('campaign/widget')->getCollection();
            $widgets->addFieldToFilter('title', $cond);
            $widget_id_cond = array('');
            foreach($widgets as $w){
                $widget_id_cond[] = array('like'=>'%'.$w->getWidgetId().'%');
            }
            $collection->addFieldToFilter('widget_selected_ids', $widget_id_cond);
        }
        return $this;
    }

    protected function _filterInCampaign($collection, $column){
        $field = ( $column->getFilterIndex() ) ? $column->getFilterIndex() : $column->getIndex();
        $cond = $column->getFilter()->getCondition();
        if ($field && isset($cond)) {
            $collection->addFieldToFilter('campaign.name' , $cond);
        }
        return $this;
    }*/

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

    public function getGridUrl() {
        return $this->getUrl('*/adminhtml_campaign/getBannerGridAjax');
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
        $selected = Mage::registry('banner_reloaded_ids');
        if(!$selected){
            $campaign_id = $this->getRequest()->getParam('id');
            $banners = Mage::getModel('campaign/bannerslider')->getCollection();
            if($campaign_id){
                $banners->addFieldToFilter('campaign_id', $campaign_id);
                $selected = array();
                foreach ($banners as $banner) {
                    $selected[] = $banner->getBannersliderId();
                }
            }else{
                $selected = array();
            }
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