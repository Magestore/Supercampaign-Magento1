<?php

class Magestore_Campaign_Block_Adminhtml_Popup_Edit_Tab_Appear extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm(){
        $form = new Varien_Data_Form();
        $this->setForm($form);

        if (Mage::getSingleton('adminhtml/session')->getPopupData()){
            $data = Mage::getSingleton('adminhtml/session')->getPopupData();
            Mage::getSingleton('adminhtml/session')->setPopupData(null);
        }elseif(Mage::registry('popup_data'))
            $data = Mage::registry('popup_data')->getData();

        $fieldset = $form->addFieldset('popup_form', array('legend'=>Mage::helper('campaign')->__('Appear information')));

        $fieldset->addField('title', 'text', array(
            'label'		=> Mage::helper('campaign')->__('Title'),
            'class'		=> 'required-entry',
            'required'	=> true,
            'name'		=> 'title',
        ));

        $fieldset->addField('filename', 'file', array(
            'label'		=> Mage::helper('campaign')->__('File'),
            'required'	=> false,
            'name'		=> 'filename',
        ));

        $fieldset->addField('status', 'select', array(
            'label'		=> Mage::helper('campaign')->__('Status'),
            'name'		=> 'status',
            'values'	=> Mage::getSingleton('campaign/status')->getOptionHash(),
        ));

        $fieldset->addField('content', 'editor', array(
            'name'		=> 'content',
            'label'		=> Mage::helper('campaign')->__('Content'),
            'title'		=> Mage::helper('campaign')->__('Content'),
            'style'		=> 'width:700px; height:500px;',
            'wysiwyg'	=> false,
            'required'	=> true,
        ));

        $form->setValues($data);
        return parent::_prepareForm();
    }
}