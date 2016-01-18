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
class Magestore_Campaign_Block_Adminhtml_Campaign_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('campaignGrid');
        $this->setDefaultSort('priority');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }
    
    /**
     * prepare collection for block to display
     *
     * @return Magestore_Campaign_Block_Adminhtml_Campaign_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('campaign/campaign')->getCollection();
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
        $this->addColumn('campaign_id', array(
            'header'    => Mage::helper('campaign')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'campaign_id',
        ));

        $this->addColumn('name', array(
            'header'    => Mage::helper('campaign')->__('Name'),
            'align'     =>'left',
            'width'     => '220px',
            'index'     => 'name',
        ));

        $this->addColumn('description', array(
            'header'    => Mage::helper('campaign')->__('Description'),
            'index'     => 'description',
        ));

        $this->addColumn('store', array(
            'header'    => Mage::helper('campaign')->__('Store'),
            'align'     => 'left',
            'width'     => '100px',
            'index'     => 'store',
            'type'        => 'store'
        ));

        $this->addColumn('start_time', array(
            'header'    => Mage::helper('campaign')->__('Start Time'),
            'align'     => 'left',
            'width'     => '100px',
            'index'     => 'start_time',
            'type'      => 'datetime',
        ));

        $this->addColumn('end_time', array(
            'header'    => Mage::helper('campaign')->__('End Time'),
            'align'     => 'left',
            'width'     => '100px',
            'index'     => 'end_time',
            'type'      => 'datetime',
        ));

        $this->addColumn('priority', array(
            'header'    => Mage::helper('campaign')->__('Priority'),
            'align'     => 'left',
            'width'     => '20px',
            'index'     => 'priority'
        ));

        $this->addColumn('status', array(
            'header'    => Mage::helper('campaign')->__('Status'),
            'align'     => 'left',
            'width'     => '80px',
            'index'     => 'status',
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
    
    /**
     * prepare mass action for this grid
     *
     * @return Magestore_Campaign_Block_Adminhtml_Campaign_Grid
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('campaign_id');
        $this->getMassactionBlock()->setFormFieldName('campaign');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'        => Mage::helper('campaign')->__('Delete'),
            'url'        => $this->getUrl('*/*/massDelete'),
            'confirm'    => Mage::helper('campaign')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('campaign/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
            'label'=> Mage::helper('campaign')->__('Change status'),
            'url'    => $this->getUrl('*/*/massStatus', array('_current'=>true)),
            'additional' => array(
                'visibility' => array(
                    'name'    => 'status',
                    'type'    => 'select',
                    'class'    => 'required-entry',
                    'label'    => Mage::helper('campaign')->__('Status'),
                    'values'=> $statuses
                ))
        ));
        return $this;
    }
    
    /**
     * get url for each row in grid
     *
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}