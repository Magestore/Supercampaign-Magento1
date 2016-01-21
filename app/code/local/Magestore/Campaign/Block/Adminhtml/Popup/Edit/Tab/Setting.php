<?php

class Magestore_Campaign_Block_Adminhtml_Popup_Edit_Tab_Setting extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm(){
        $form = new Varien_Data_Form();
        $this->setForm($form);

        if (Mage::getSingleton('adminhtml/session')->getPopupData()){
            $data = Mage::getSingleton('adminhtml/session')->getPopupData();
            Mage::getSingleton('adminhtml/session')->setPopupData(null);
        }elseif(Mage::registry('popup_data'))
            $data = Mage::registry('popup_data')->getData();

        $fieldset = $form->addFieldset('popup_form', array('legend'=>Mage::helper('campaign')->__('Setting information')));

        $fieldset->addField('showing_frequency', 'select', array(
            'label'		=> Mage::helper('campaign')->__('Show Frequency:'),
            'required'	=> true,
            'name'		=> 'showing_frequency',
            'note'      => "Show popup when have had customer's action .",
            'values' => array(
                array(
                    'value' => 0,
                    'label' => Mage::helper('campaign')->__('Show until user close it'),
                ),
                array(
                    'value' => 1,
                    'label' => Mage::helper('campaign')->__('Only once'),
                ),
                array(
                    'value' => 2,
                    'label' => Mage::helper('campaign')->__('Every time'),
                ),
            ),
        ));

        $fieldset->addField('cookie_time', 'text', array(
            'label'		=> Mage::helper('campaign')->__('Cookie Life Time:'),
            'note'      => 'Set time for cookie to show popup.',
            'required'	=> false,
            'name'		=> 'cookie_time',
        ));

        $fieldset->addField('total_time', 'text', array(
            'label'		=> Mage::helper('campaign')->__('Max Time Preview:'),
            'required'	=> false,
            'note'      => 'Max time to show popup.',
            'name'		=> 'total_time',
        ));

        $fieldset->addField('priority', 'text', array(
            'label'		=> Mage::helper('campaign')->__('Set Priority:'),
            'class'		=> 'required-entry',
            'note'      => 'Set priority when have many popup.',
            'required'	=> false,
            'name'		=> 'priority',
        ));

        $form->setValues($data);
        return parent::_prepareForm();
    }
}