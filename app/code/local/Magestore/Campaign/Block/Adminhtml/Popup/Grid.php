<?php

class Magestore_Campaign_Block_Adminhtml_Popup_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct(){
		parent::__construct();
		$this->setId('popupGrid');
		$this->setDefaultSort('popup_id');
		$this->setDefaultDir('DESC');
		$this->setSaveParametersInSession(true);
	}

	protected function _prepareCollection(){
		$collection = Mage::getModel('campaign/popup')->getCollection();
		$collection->getSelect()
			->joinLeft(array('campaign'=>$collection->getTable('campaign/campaign')),
				'main_table.campaign_id = campaign.campaign_id', '')
			->columns(array('campaign_name'=>'campaign.name'))
			->order('main_table.priority DESC')
			->order('main_table.popup_id DESC')
			->group('main_table.popup_id');
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	protected function _prepareColumns(){
		$this->addColumn('popup_id', array(
			'header'	=> Mage::helper('campaign')->__('ID'),
			'align'	 =>'right',
			'width'	 => '50px',
			'index'	 => 'popup_id',
		));

		$this->addColumn('title', array(
			'header'	=> Mage::helper('campaign')->__('Title'),
			'align'	 =>'left',
			'index'	 => 'title',
		));

        $this->addColumn('campaign_name', array(
            'header'	=> Mage::helper('campaign')->__('Added to Campaign'),
            'align'	 =>'left',
            'index'	 => 'campaign_name',
			'filter_condition_callback' => array($this, '_filterInCampaign'),
        ));

		/*$this->addColumn('popup_type', array(
			'header'	=> Mage::helper('campaign')->__('Popup Type'),
            'align'	 =>'left',
			'index'	 => 'popup_type',
            'type'		=> 'options',
            'options'	 => array(
                'static' => 'Static',
                'video' => 'Video',
                'sticker' => 'Sticker',
                'subscribe' => 'Subscribe',
                'register' => 'Register',
            ),
		));*/

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store', array(
                'header'        => Mage::helper('campaign')->__('Store View'),
                'index'         => 'store',
                'type'          => 'store',
                'store_all'     => true,
                'store_view'    => true,
                'sortable'      => true,
				'filter_index'	=> 'main_table.store',
				'filter_condition_callback' => array($this, '_filterStore'),
            ));
        }

        $this->addColumn('show_on_page', array(
            'header'	=> Mage::helper('campaign')->__('Show On Page'),
            'align'	 =>'left',
            'index'	 => 'show_on_page',
            'type'		=> 'options',
            'options'	 => array(
				Magestore_Campaign_Model_Popup::SHOW_ON_ALL_PAGE => Mage::helper('campaign')->__('All pages'),
				Magestore_Campaign_Model_Popup::SHOW_ON_HOME_PAGE => Mage::helper('campaign')->__('Home Page'),
				Magestore_Campaign_Model_Popup::SHOW_ON_PRODUCT_PAGE => Mage::helper('campaign')->__('Product Detail Page'),
				Magestore_Campaign_Model_Popup::SHOW_ON_CATEGORY_PAGE => Mage::helper('campaign')->__('Category'),
				Magestore_Campaign_Model_Popup::SHOW_ON_CART_PAGE => Mage::helper('campaign')->__('Cart Page'),
				Magestore_Campaign_Model_Popup::SHOW_ON_CHECKOUT_PAGE => Mage::helper('campaign')->__('Checkout Page'),
				Magestore_Campaign_Model_Popup::SHOW_ON_URLS_PAGE => Mage::helper('campaign')->__('Special URLs'),
            ),
        ));

        $this->addColumn('show_when', array(
            'header'	=> Mage::helper('campaign')->__('Show when'),
            'align'	 =>'left',
            'index'	 => 'show_when',
            'type'		=> 'options',
            'options'	 => array(
                'after_load_page' => Mage::helper('campaign')->__('After loading page'),
                'after_seconds' => Mage::helper('campaign')->__('After Seconds'),
            ),
        ));

        $this->addColumn('priority', array(
            'header'	=> Mage::helper('campaign')->__('Priority'),
            'align'	 =>'left',
            'index'	 => 'priority',
        ));

		$this->addColumn('status', array(
			'header'	=> Mage::helper('campaign')->__('Status'),
			'align'	 => 'left',
			'width'	 => '80px',
			'index'	 => 'status',
			'filter_index' => 'main_table.status',
			'type'		=> 'options',
			'options'	 => Mage::getSingleton('campaign/status')->getOptionArray(),
		));

		$this->addColumn('action',
			array(
				'header'	=>	Mage::helper('campaign')->__('Action'),
				'width'		=> '100',
				'type'		=> 'action',
				'getter'	=> 'getId',
				'actions'	=> array(
					array(
						'caption'	=> Mage::helper('campaign')->__('Edit'),
						'url'		=> array('base'=> '*/*/edit'),
						'field'		=> 'id'
					)),
				'filter'	=> false,
				'sortable'	=> false,
				'index'		=> 'stores',
				'is_system'	=> true,
		));

		$this->addExportType('*/*/exportCsv', Mage::helper('campaign')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('campaign')->__('XML'));

		return parent::_prepareColumns();
	}

	protected function _filterInCampaign($collection, $column){
		$field = ( $column->getFilterIndex() ) ? $column->getFilterIndex() : $column->getIndex();
		$cond = $column->getFilter()->getCondition();
		if ($field && isset($cond)) {
			$collection->addFieldToFilter('campaign.name' , $cond);
		}
		return $this;
	}

	protected function _filterStore($collection, $column){
		$field = ( $column->getFilterIndex() ) ? $column->getFilterIndex() : $column->getIndex();
		$cond = $column->getFilter()->getCondition();
		if($column->getFilter()->getValue() == '0' && $field){

		}else
		if($field && isset($cond)){
			$collection->addFieldToFilter('main_table.store', array('finset'=>$column->getFilter()->getValue()));
		}
		return $this;
	}

	protected function _prepareMassaction(){
		$this->setMassactionIdField('popup_id');
		$this->getMassactionBlock()->setFormFieldName('popup');

		$this->getMassactionBlock()->addItem('delete', array(
			'label'		=> Mage::helper('campaign')->__('Delete'),
			'url'		=> $this->getUrl('*/*/massDelete'),
			'confirm'	=> Mage::helper('campaign')->__('Are you sure?')
		));

		$statuses = Mage::getSingleton('campaign/status')->getOptionHash();
		array_unshift($statuses, array('label'=>'', 'value'=>''));
		$this->getMassactionBlock()->addItem('status', array(
			'label'=> Mage::helper('campaign')->__('Change status'),
			'url'	=> $this->getUrl('*/*/massStatus', array('_current'=>true)),
			'additional' => array(
				'visibility' => array(
					'name'	=> 'status',
					'type'	=> 'select',
					'class'	=> 'required-entry',
					'label'	=> Mage::helper('campaign')->__('Status'),
					'values'=> $statuses
				))
		));
		return $this;
	}

	public function getRowUrl($row){
		return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}
}