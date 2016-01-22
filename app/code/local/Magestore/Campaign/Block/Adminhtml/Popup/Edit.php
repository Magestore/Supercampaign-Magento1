<?php

class Magestore_Campaign_Block_Adminhtml_Popup_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
	public function __construct(){
		parent::__construct();
		
		$this->_objectId = 'id';
		$this->_blockGroup = 'campaign';
		$this->_controller = 'adminhtml_campaign';
		
		$this->_updateButton('save', 'label', Mage::helper('campaign')->__('Save Popup'));
		$this->_updateButton('delete', 'label', Mage::helper('campaign')->__('Delete Popup'));
		
		$this->_addButton('saveandcontinue', array(
			'label'		=> Mage::helper('adminhtml')->__('Save And Continue Edit'),
			'onclick'	=> 'saveAndContinueEdit()',
			'class'		=> 'save',
		), -100);

		$this->_formScripts[] = "
			function toggleEditor() {
				if (tinyMCE.getInstanceById('popup_content') == null)
					tinyMCE.execCommand('mceAddControl', false, 'popup_content');
				else
					tinyMCE.execCommand('mceRemoveControl', false, 'popup_content');
			}

			function saveAndContinueEdit(){
				editForm.submit($('edit_form').action+'back/edit/');
			}
		";
	}

	public function getHeaderText(){
		if(Mage::registry('popup_data') && Mage::registry('popup_data')->getId())
			return Mage::helper('campaign')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('popup_data')->getTitle()));
		return Mage::helper('campaign')->__('Add Item');
	}
}