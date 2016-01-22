<?php

class Magestore_Campaign_Block_Adminhtml_Popup extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct(){
		$this->_controller = 'adminhtml_popup';
		$this->_blockGroup = 'campaign';
		$this->_headerText = Mage::helper('campaign')->__('Popup Manager');
		$this->_addButtonLabel = Mage::helper('campaign')->__('Add Popup');
		parent::__construct();
	}
}