<?php

class Magestore_Campaign_Block_Adminhtml_Popup_Edit_GridTemplate extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct(){
		parent::__construct();
		$this->setId('popupGrid');
		$this->setDefaultSort('popup_id');
		$this->setDefaultDir('DESC');
		$this->setSaveParametersInSession(true);
	}

	protected function _prepareCollection(){
		$collection = Mage::getModel('campaign/template')->getCollection();
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	protected function _prepareColumns(){
		/*$this->addColumn('popup_id', array(
			'header'	=> Mage::helper('campaign')->__('ID'),
			'align'	 =>'right',
			'width'	 => '50px',
			'index'	 => 'popup_id',
		));*/
		$this->addColumn('preview_image', array(
			'header'	=> Mage::helper('campaign')->__('Image'),
			'align'	 => 'left',
			'width'	 => '300px',
			'index'	 => 'preview_image',
			'alt'	=> 'template_code',
			'renderer'	=> 'Magestore_Campaign_Block_Adminhtml_Renderer_Image',
			'filter'	=> false,
		));

		$this->addColumn('title', array(
			'header'	=> Mage::helper('campaign')->__('Title'),
			'align'	 =>'left',
			'index'	 => 'title',
		));

		$this->addColumn('action', array(
			'header'	=> Mage::helper('campaign')->__('Action'),
			'align'	 => 'left',
			'width'	 => '150px',
			'index'	 => 'template_id',
			'renderer'	=> 'Magestore_Campaign_Block_Adminhtml_Renderer_Action',
			'values'	=> array(
//				array(
//					'caption' => Mage::helper('campaign')->__('Preview'),
//					//'url' => array('*/*/newfromtemplate', array('template_id'=>999)),
//					'name' => 'template_id',
//					'onclick' => "alert('Comming soon!')",
//				),
				array(
					'caption' => Mage::helper('campaign')->__('Apply'),
					//'url' => array('http://www.magestore.com/', array('id'=>'666')),
					'name' => 'template_id',
					//'onclick' => "alert('456')",
					'callback' => "closePopup", //function(val){}
				)
			),
			'filter'	=> false,
			//'after_element_html' => '<script>function applyTemplate(val){closePopup(val);}</script>',
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

	public function getRowUrl($row){
		return '';//$this->getUrl('*/*/edit', array('id' => $row->getId()));
	}
}