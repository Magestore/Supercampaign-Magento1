<?php

class Magestore_Campaign_Block_Adminhtml_Popup_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct(){
		parent::__construct();
		$this->setId('popup_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle(Mage::helper('campaign')->__('Popup Information'));
	}

	protected function _beforeToHtml(){
		$this->addTab('general_section', array(
			'label'	 => Mage::helper('campaign')->__('Popup Information'),
			'title'	 => Mage::helper('campaign')->__('Popup Information'),
			'content'	 => $this->getLayout()->createBlock('campaign/adminhtml_popup_edit_tab_form')->toHtml(),
		));

        $this->addTab('appear_section', array(
            'label'     => Mage::helper('campaign')->__('Styles and Effects'),
            'title'     => Mage::helper('campaign')->__('Styles and Effects'),
            'content'   => $this->getLayout()->createBlock('campaign/adminhtml_popup_edit_tab_appear')->toHtml(),
        ));

        $this->addTab('position_section', array(
            'label'     => Mage::helper('campaign')->__('Placement'),
            'title'     => Mage::helper('campaign')->__('Placement'),
            'content'   => $this->getLayout()->createBlock('campaign/adminhtml_popup_edit_tab_position')->toHtml(),
        ));

//        $this->addTab('visitorsegment_section', array(
//            'label'     => Mage::helper('campaign')->__('Visitorsegment'),
//            'title'     => Mage::helper('campaign')->__('Visitorsegment'),
//            'content'   => $this->getLayout()->createBlock('campaign/adminhtml_popup_edit_tab_visitorsegment')->toHtml(),
//        ));

        $this->setActiveTab('general_section');
		return parent::_beforeToHtml();
	}
}