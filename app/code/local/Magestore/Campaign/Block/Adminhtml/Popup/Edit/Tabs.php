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
		$this->addTab('form_section', array(
			'label'	 => Mage::helper('campaign')->__('Popup Information'),
			'title'	 => Mage::helper('campaign')->__('Popup Information'),
			'content'	 => $this->getLayout()->createBlock('campaign/adminhtml_popup_edit_tab_form')->toHtml(),
		));

        $this->addTab('appear_section', array(
            'label'     => Mage::helper('campaign')->__('Appear Information'),
            'title'     => Mage::helper('campaign')->__('Appear Information'),
            'content'   => $this->getLayout()->createBlock('campaign/adminhtml_popup_edit_tab_appear')->toHtml(),
        ));

        $this->addTab('setting_section', array(
            'label'     => Mage::helper('campaign')->__('Setting Information'),
            'title'     => Mage::helper('campaign')->__('Setting Information'),
            'content'   => $this->getLayout()->createBlock('campaign/adminhtml_popup_edit_tab_appear')->toHtml(),
        ));

        $this->addTab('visitorsegment_section', array(
            'label'     => Mage::helper('campaign')->__('Visitorsegment Information'),
            'title'     => Mage::helper('campaign')->__('Visitorsegment Information'),
            'content'   => $this->getLayout()->createBlock('campaign/adminhtml_popup_edit_tab_appear')->toHtml(),
        ));
        $this->setActiveTab('form_section');
		return parent::_beforeToHtml();
	}
}