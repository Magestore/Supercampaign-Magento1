<?php

class Magestore_Campaign_Block_Adminhtml_Popup_Edit_Tab_Position extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm(){
        $form = new Varien_Data_Form();
        $this->setForm($form);

        if (Mage::getSingleton('adminhtml/session')->getPopupData()){
            $data = Mage::getSingleton('adminhtml/session')->getPopupData();
            Mage::getSingleton('adminhtml/session')->setPopupData(null);
        }elseif(Mage::registry('popup_data'))
            $data = Mage::registry('popup_data')->getData();

        $fieldset = $form->addFieldset('popup_form', array('legend'=>Mage::helper('campaign')->__('Position Popup')));

        $horizontal = $fieldset->addField('horizontal_position', 'select', array(
            'label'		=> Mage::helper('campaign')->__('Horizontal position:'),
            'required'	=> true,
            'name'		=> 'horizontal_position',
            'note'      => 'Horizontal position popup.',
            'values' => array(
                array(
                    'value' => 'left',
                    'label' => Mage::helper('campaign')->__('Left'),
                ),
                array(
                    'value' => 'center',
                    'label' => Mage::helper('campaign')->__('Center'),
                ),
                array(
                    'value' => 'right',
                    'label' => Mage::helper('campaign')->__('Right'),
                ),
            ),
        ));

        $horizontal_px = $fieldset->addField('horizontal_px', 'text', array(
            'label'		=> Mage::helper('campaign')->__('How many horizontal px:'),
            'required'	=> false,
            'name'		=> 'horizontal_px',
            'note'      => 'Fill px number horizontal.',
        ));


        $fieldset->addField('vertical_position', 'select', array(
            'label'		=> Mage::helper('campaign')->__('Vertical position:'),
            'required'	=> true,
            'name'		=> 'vertical_position',
            'note'      => 'Vertical position popup.',
            'values' => array(
                array(
                    'value' => 'top',
                    'label' => Mage::helper('campaign')->__('Top'),
                ),
                array(
                    'value' => 'bottom',
                    'label' => Mage::helper('campaign')->__('Bottom'),
                ),
            ),
        ));

        $fieldset->addField('vertical_px', 'text', array(
            'label'		=> Mage::helper('campaign')->__('How many vertical px:'),
            'required'	=> false,
            'name'		=> 'vertical_px',
            'note'      => 'Fill px number vertical.',
        ));

        $form->setValues($data);

        $this->setForm($form);
        $this->setChild('form_after', $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
                ->addFieldMap($horizontal->getHtmlId(), $horizontal->getName())
                ->addFieldMap($horizontal_px->getHtmlId(), $horizontal_px->getName())
                ->addFieldDependence(
                    $horizontal_px->getName(),
                    $horizontal->getName(),
                    'center'
                )
        );

        return parent::_prepareForm();
    }
}