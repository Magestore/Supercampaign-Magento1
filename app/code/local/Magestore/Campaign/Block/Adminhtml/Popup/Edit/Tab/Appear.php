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

        $fieldset->addField('corner_style', 'select', array(
            'label'		=> Mage::helper('campaign')->__('Popup Corners Style:'),
            'required'	=> true,
            'name'		=> 'corner_style',
            'note'      => 'Type of corner for popup.',
            'values' => array(
                array(
                    'value' => 0,
                    'label' => Mage::helper('campaign')->__('Rounded'),
                ),
                array(
                    'value' => 1,
                    'label' => Mage::helper('campaign')->__('Shapr'),
                ),
                array(
                    'value' => 2,
                    'label' => Mage::helper('campaign')->__('Circle'),
                ),
            ),
        ));

        $fieldset->addField('border_radius', 'text', array(
            'label'		=> Mage::helper('campaign')->__('Border Radius In Px:'),
            'required'	=> false,
            'name'		=> 'border_radius',
        ));

        $fieldset->addField('border_color', 'text', array(
            'label'		=> Mage::helper('campaign')->__('Border Color:'),
            'required'	=> false,
            'name'		=> 'border_color',
        ));


        $fieldset->addField('border_size', 'text', array(
            'label'		=> Mage::helper('campaign')->__('Border Size In Px:'),
            'required'	=> false,
            'name'		=> 'border_size',
        ));

        $fieldset->addField('background_color', 'select', array(
            'label'		=> Mage::helper('campaign')->__('Overlay Background:'),
            'required'	=> true,
            'name'		=> 'background_color',
            'note'      => 'Overlay background when show popup.',
            'values' => array(
                array(
                    'value' => 0,
                    'label' => Mage::helper('campaign')->__('White'),
                ),
                array(
                    'value' => 1,
                    'label' => Mage::helper('campaign')->__('Black'),
                ),
            ),
        ));

        $fieldset->addField('popup_background', 'text', array(
            'label'		=> Mage::helper('campaign')->__('Popup Content Background Color:'),
            'required'	=> false,
            'note'      => 'Background of popup.',
            'name'		=> 'popup_background',
        ));

        $fieldset->addField('padding', 'text', array(
            'label'		=> Mage::helper('campaign')->__('Padding Size:'),
            'required'	=> false,
            'name'		=> 'padding',
        ));

        $fieldset->addField('close_style', 'select', array(
            'label'		=> Mage::helper('campaign')->__('Close Icon Style:'),
            'required'	=> true,
            'name'		=> 'close_style',
            'note'      => 'Type of effect close popup.',
            'values' => array(
                array(
                    'value' => 0,
                    'label' => Mage::helper('campaign')->__('Circle'),
                ),
                array(
                    'value' => 1,
                    'label' => Mage::helper('campaign')->__('Simple'),
                ),
                array(
                    'value' => 2,
                    'label' => Mage::helper('campaign')->__('None'),
                ),
            ),
        ));

        $fieldset->addField('popup_shadow', 'select', array(
            'label'		=> Mage::helper('campaign')->__('Popup Box Shadow:'),
            'required'	=> true,
            'name'		=> 'popup_shadow',
            'values' => array(
                array(
                    'value' => 0,
                    'label' => Mage::helper('campaign')->__('No'),
                ),
                array(
                    'value' => 1,
                    'label' => Mage::helper('campaign')->__('Yes'),
                ),
            ),
        ));

        $fieldset->addField('appear_effect', 'select', array(
            'label'		=> Mage::helper('campaign')->__('Appear Effect:'),
            'required'	=> true,
            'name'		=> 'appear_effect',
            'note'      => 'Effect show popup on page.',
            'values' => array(
                array(
                    'value' => 0,
                    'label' => Mage::helper('campaign')->__('Fade In'),
                ),
                array(
                    'value' => 1,
                    'label' => Mage::helper('campaign')->__('Fade Out'),
                ),
                array(
                    'value' => 2,
                    'label' => Mage::helper('campaign')->__('Around'),
                ),
                array(
                    'value' => 3,
                    'label' => Mage::helper('campaign')->__('Slide Up'),
                ),
                array(
                    'value' => 4,
                    'label' => Mage::helper('campaign')->__('Slide Down'),
                ),
                array(
                    'value' => 5,
                    'label' => Mage::helper('campaign')->__('Rotate Zoom'),
                ),
            ),
        ));


        $fieldset->addField('close_effect', 'select', array(
            'label'		=> Mage::helper('campaign')->__('Popup Close Effect:'),
            'required'	=> true,
            'name'		=> 'close_effect',
            'note'      => 'Effect when close popup on page.',
            'values' => array(
                array(
                    'value' => 0,
                    'label' => Mage::helper('campaign')->__('Fade Out'),
                ),
                array(
                    'value' => 1,
                    'label' => Mage::helper('campaign')->__('Disapear'),
                ),
            ),
        ));

        $fieldset->addField('custom_css', 'editor', array(
            'label'		=> Mage::helper('campaign')->__('Custom css style:'),
            'required'	=> false,
            'name'		=> 'custom_css',
            'note'      => 'Custom css for the popup.',
        ));


        $form->setValues($data);
        return parent::_prepareForm();
    }
}