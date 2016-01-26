<?php

class Magestore_Campaign_Block_Adminhtml_Campaign_Edit_Tab_Popup_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct(){
		parent::__construct();
		$this->setId('popupGrid');
		$this->setDefaultSort('popup_id');
		$this->setDefaultDir('ASC');
		$this->setSaveParametersInSession(true);
		$this->setUseAjax(true);
	}

	protected function _prepareCollection(){
		$currentCampaign = Mage::getSingleton('adminhtml/session')->getCurrentCampaign();
		$collection = Mage::getModel('campaign/popup')->getCollection();
		$collection->getSelect()
			->joinLeft(array('campaign'=>$collection->getTable('campaign/campaign')),
				'main_table.campaign_id = campaign.campaign_id', '')
			->columns(array('campaign_name'=>'IF(main_table.campaign_id = "'.$currentCampaign->getId().'", "Current", campaign.name)'))
			->group('main_table.popup_id');
		$filter = Mage::registry('popup_reloaded_ids');
		if(!isset($filter)){//if reset no filter
			$selected_id = $this->_selectedId();
			if(!empty($selected_id)){
				$collection->addFieldToFilter('popup_id', array('in'=>$selected_id));
			}
		}
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	protected function _prepareColumns(){
		$this->addColumn('grid_checkbox', array(
			'header_css_class' => 'a-center',
			'type' => 'checkbox',
			'index' => 'popup_id',
			//'name' => 'grid_checkbox',
			'align' => 'center',
			'width'     => '50px',
			'values' => $this->_selectedId(),
		));

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
			'filter' => false,
        ));

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

        $this->addColumn('page_id', array(
            'header'	=> Mage::helper('campaign')->__('Show On Page'),
            'align'	 =>'left',
            'index'	 => 'page_id',
            'type'		=> 'options',
            'options'	 => array(
                0 => 'All Page',
                1 => 'Home Page',
                2 => 'Product Page',
                3 => 'Category Page',
                4 => 'Checkout Page',
                5 => 'Cart Page',
                6 => 'Other Page',
                7 => 'Specified Url',
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
			'type'		=> 'options',
			'options'	 => Magestore_Campaign_Model_Status::getOptionArray(),
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
						'url'        => array('base'=> '*/adminhtml_popup/edit'),
						//'onclick' => 'return saveAndContinueEdit();',
						'field'        => 'id'
					)),
				'filter'    => false,
				'sortable'    => false,
				'index'        => 'stores',
				'is_system'    => true,
			));

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

	public function getGridUrl() {
		return $this->getUrl('*/adminhtml_campaign/getPopupGridAjax');
	}

	public function getRowUrl($row){
		return '';//$this->getUrl('*/*/edit', array('id' => $row->getId()));
	}

	public function getSerializeData(){
		$selected = Mage::registry('popup_reloaded_ids');
		if(!$selected){
			$campaign_id = $this->getRequest()->getParam('id');
			$collection = Mage::getModel('campaign/popup')->getCollection();
			if($campaign_id){
				$collection->addFieldToFilter('campaign_id', $campaign_id);
				$selected = array();
				foreach ($collection as $item) {
					$selected[] = $item->getPopupId();
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