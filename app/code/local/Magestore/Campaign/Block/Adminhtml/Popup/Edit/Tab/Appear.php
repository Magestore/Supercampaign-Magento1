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

        $corner_style = $fieldset->addField('corner_style', 'select', array(
            'label'		=> Mage::helper('campaign')->__('Popup Corners Style:'),
            'required'	=> true,
            'name'		=> 'corner_style',
            'note'      => 'Type of corner for popup.',
            'values' => array(
                array(
                    'value' => 'rounded',
                    'label' => Mage::helper('campaign')->__('Rounded'),
                ),
                array(
                    'value' => 'shapr',
                    'label' => Mage::helper('campaign')->__('Shapr'),
                ),
                array(
                    'value' => 'circle',
                    'label' => Mage::helper('campaign')->__('Circle'),
                ),
            ),
        ));

        $border_px = $fieldset->addField('border_radius', 'text', array(
            'label'		=> Mage::helper('campaign')->__('Border Radius In Px:'),
            'required'	=> false,
            'class'       => 'validate-number',
            'name'		=> 'border_radius',
        ));

        $fieldset->addField('border_color', 'text', array(
            'label'		=> Mage::helper('campaign')->__('Border Color:'),
            'required'	=> false,
            'name'		=> 'border_color',
            'class'     =>  'color',
        ));


        $fieldset->addField('border_size', 'text', array(
            'label'		=> Mage::helper('campaign')->__('Border Size In Px:'),
            'required'	=> false,
            'name'		=> 'border_size',
        ));

        $fieldset->addField('overlay_color', 'select', array(
            'label'		=> Mage::helper('campaign')->__('Overlay Color:'),
            'name'		=> 'overlay_color',
            'note'      => 'Overlay color of popup.',
            'values' => array(
                array(
                    'value' => 'white',
                    'label' => Mage::helper('campaign')->__('White'),
                ),
                array(
                    'value' => 'dark',
                    'label' => Mage::helper('campaign')->__('Dark'),
                ),
                array(
                    'value' => 'no_bg_fix_popup',
                    'label' => Mage::helper('campaign')->__('No background, Popup fixed positioned'),
                ),
                array(
                    'value' => 'no_bg_absoulute_popup',
                    'label' => Mage::helper('campaign')->__('No background, Popup absolute positioned'),
                ),
            ),
        ));

        $fieldset->addField('popup_background', 'text', array(
            'label'		=> Mage::helper('campaign')->__('Popup Content Background Color:'),
            'required'	=> false,
            'note'      => 'Background of popup.',
            'name'		=> 'popup_background',
            'class'     =>  'color',
        ));

        $fieldset->addField('padding', 'text', array(
            'label'		=> Mage::helper('campaign')->__('Padding Size:'),
            'required'	=> false,
            'class'       => 'validate-number',
            'name'		=> 'padding',
        ));

        $fieldset->addField('close_style', 'select', array(
            'label'		=> Mage::helper('campaign')->__('Close Icon Style:'),
            'required'	=> true,
            'name'		=> 'close_style',
            'note'      => 'Type of effect close popup.',
            'values' => array(
                array(
                    'value' => 'circle',
                    'label' => Mage::helper('campaign')->__('Circle'),
                ),
                array(
                    'value' => 'simple',
                    'label' => Mage::helper('campaign')->__('Simple'),
                ),
                array(
                    'value' => 'none',
                    'label' => Mage::helper('campaign')->__('None'),
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
                    'label' => Mage::helper('campaign')->__('Top'),
                ),
                array(
                    'value' => 1,
                    'label' => Mage::helper('campaign')->__('Bottom'),
                ),
                array(
                    'value' => 2,
                    'label' => Mage::helper('campaign')->__('Left'),
                ),
                array(
                    'value' => 3,
                    'label' => Mage::helper('campaign')->__('Right'),
                ),
                array(
                    'value' => 4,
                    'label' => Mage::helper('campaign')->__('Top Left'),
                ),
                array(
                    'value' => 5,
                    'label' => Mage::helper('campaign')->__('Top Right'),
                ),
                array(
                    'value' => 6,
                    'label' => Mage::helper('campaign')->__('Bottom Left'),
                ),
                array(
                    'value' => 7,
                    'label' => Mage::helper('campaign')->__('Bottom Right'),
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

        $this->setForm($form);
        $this->setChild('form_after', $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
                ->addFieldMap($corner_style->getHtmlId(), $corner_style->getName())
                ->addFieldMap($border_px->getHtmlId(), $border_px->getName())
                ->addFieldDependence(
                    $border_px->getName(),
                    $corner_style->getName(),
                    'rounded'
                )
        );

        return parent::_prepareForm();
    }
}