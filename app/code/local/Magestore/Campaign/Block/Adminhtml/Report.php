<?php
class Magestore_Campaign_Block_Adminhtml_Report extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_report';
    $this->_blockGroup = 'campaign';
    $this->_headerText = Mage::helper('campaign')->__('Report');
    parent::__construct();
	 $this->removeButton('add');
  }
}

