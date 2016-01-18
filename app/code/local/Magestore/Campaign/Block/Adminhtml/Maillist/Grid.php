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
class Magestore_Campaign_Block_Adminhtml_Maillist_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('maillistGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(false);
    }
    
    /**
     * prepare collection for block to display
     *
     * @return Magestore_Campaign_Block_Adminhtml_Campaign_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('campaign/maillist')->getCollection();

        $collection->getSelect()
            ->joinLeft(array('campaign'=>$collection->getTable('campaign/campaign')),
                'main_table.campaign_id = campaign.campaign_id', '')
            ->columns(array('campaign_name'=>'campaign.name', 'campaign_start_time'=>'campaign.start_time'))
            ->group('main_table.id');
//zend_debug::dump($collection->getSelectSql(true));die;

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    
    /**
     * prepare columns for this grid
     *
     * @return Magestore_Campaign_Block_Adminhtml_Campaign_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'index'     => 'id',
            'header'    => Mage::helper('campaign')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
        ));

        $this->addColumn('name', array(
            'index'     => 'name',
            'header'    => Mage::helper('campaign')->__('Name'),
            'align'     =>'left',
            'width'     => '220px',
        ));

        $this->addColumn('email', array(
            'index'     => 'email',
            'header'    => Mage::helper('campaign')->__('Email'),
            'align'     =>'left',
            'width'     => '220px',
        ));

        $this->addColumn('campaign_name', array(
            'index'     => 'campaign_name',
            'header'    => Mage::helper('campaign')->__('Campaign'),
            'align'     =>'left',
            'width'     => '220px',
            'filter_condition_callback' => array($this, 'prepareCampaignFilterName'),
        ));

        $this->addColumn('coupon_code', array(
            'index'     => 'coupon_code',
            'header'    => Mage::helper('campaign')->__('Coupon Code'),
            'align'     =>'left',
            'width'     => '220px',
        ));

        $this->addColumn('start_time', array(
            'index'     => 'start_time',
            'header'    => Mage::helper('campaign')->__('Enter At'),
            'align'     => 'left',
            'width'     => '100px',
            'type'      => 'datetime',
            'filter_condition_callback' => array($this, 'prepareGridFilterStartTime'), //fix Column 'start_time' in where clause is ambiguous
        ));

        $this->addColumn('expired_time', array(
            'index'     => 'expired_time',
            'header'    => Mage::helper('campaign')->__('Expired Date'),
            'align'     => 'left',
            'width'     => '100px',
            'type'      => 'datetime',
        ));

        $this->addColumn('used_coupon', array(
            'index'     => 'used_coupon',
            'header'    => Mage::helper('campaign')->__('Used Coupon'),
            'align'     => 'left',
            'width'     => '80px',
            'type'        => 'options',
            'options'     => array(
                1 => 'Yes',
                0 => 'No',
            ),
        ));

//        $this->addColumn('action',
//            array(
//                'header'    =>    Mage::helper('campaign')->__('Action'),
//                'width'        => '100',
//                'type'        => 'action',
//                'getter'    => 'getId',
//                'actions'    => array(
//                    array(
//                        'caption'    => Mage::helper('campaign')->__('Edit'),
//                        'url'        => array('base'=> '*/*/edit'),
//                        'field'        => 'id'
//                    )),
//                'filter'    => false,
//                'sortable'    => false,
//                'index'        => 'stores',
//                'is_system'    => true,
//        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('campaign')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('campaign')->__('XML'));

        return parent::_prepareColumns();
    }

    public function prepareCampaignFilterName($collection, $column){
        $field = ( $column->getFilterIndex() ) ? $column->getFilterIndex() : $column->getIndex();
        $cond = $column->getFilter()->getCondition();
        if ($field && isset($cond)) {
            $collection->addFieldToFilter('campaign.name' , $cond);
        }
        return $this;
    }

    public function prepareGridFilterStartTime($collection, $column){
        $field = ( $column->getFilterIndex() ) ? $column->getFilterIndex() : $column->getIndex();
        $cond = $column->getFilter()->getCondition();
        if ($field && isset($cond)) {
            $collection->addFieldToFilter('main_table.start_time' , $cond);
        }
        return $this;
    }
    
    /**
     * prepare mass action for this grid
     *
     * @return Magestore_Campaign_Block_Adminhtml_Campaign_Grid
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('maillist');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'        => Mage::helper('campaign')->__('Delete'),
            'url'        => $this->getUrl('*/*/massDelete'),
            'confirm'    => Mage::helper('campaign')->__('Are you sure?')
        ));

//        $statuses = Mage::getSingleton('campaign/status')->getOptionArray();
//        array_unshift($statuses, array('label'=>'', 'value'=>''));
//        $this->getMassactionBlock()->addItem('status', array(
//            'label'=> Mage::helper('campaign')->__('Change status'),
//            'url'    => $this->getUrl('*/*/massStatus', array('_current'=>true)),
//            'additional' => array(
//                'visibility' => array(
//                    'name'    => 'status',
//                    'type'    => 'select',
//                    'class'    => 'required-entry',
//                    'label'    => Mage::helper('campaign')->__('Status'),
//                    'values'=> $statuses
//                ))
//        ));
        return $this;
    }
    
    /**
     * get url for each row in grid
     *
     * @return string
     */
    public function getRowUrl($row)
    {
        return '';//$this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}