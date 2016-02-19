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

        $fieldset = $form->addFieldset('popup_form', array('legend'=>Mage::helper('campaign')->__('Placement')));

        $horizontal = $fieldset->addField('horizontal_position', 'select', array(
            'label'		=> Mage::helper('campaign')->__('Horizontal alignment:'),
            'required'	=> true,
            'name'		=> 'horizontal_position',
            'note'      => '',
            'values' => array(
                array(
                    'value' => 'center',
                    'label' => Mage::helper('campaign')->__('Center'),
                ),
                array(
                    'value' => 'left',
                    'label' => Mage::helper('campaign')->__('Left'),
                ),
                array(
                    'value' => 'right',
                    'label' => Mage::helper('campaign')->__('Right'),
                ),
            ),
        ));

         $horizontal_px = $fieldset->addField('horizontal_px', 'text', array(
            'label'		=> Mage::helper('campaign')->__('Distance from horizontal margin'),
            'required'	=> false,
            'name'		=> 'horizontal_px',
            'class'       => 'validate-number',
            'note'      => 'Insert number in pixel.',
        ));


        $fieldset->addField('vertical_position', 'select', array(
            'label'		=> Mage::helper('campaign')->__('Vertical alignment:'),
            'required'	=> true,
            'name'		=> 'vertical_position',
            'note'      => '',
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
            'label'		=> Mage::helper('campaign')->__('Distance from vertical margin'),
            'required'	=> false,
            'class'       => 'validate-number',
            'name'		=> 'vertical_px',
            'note'      => 'Insert number in pixel.',
        ));
        if($data['vertical_px'] == NULL){$data['vertical_px'] = 100;}
        if($data['horizontal_px'] == NULL){$data['horizontal_px'] = 100;}
            //var_dump($data); die('ddd');
        $form->setValues($data);

        $this->setForm($form);
        $this->setChild('form_after', $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
                ->addFieldMap($horizontal->getHtmlId(), $horizontal->getName())
                ->addFieldMap($horizontal_px->getHtmlId(), $horizontal_px->getName())
                ->addFieldDependence(
                    $horizontal_px->getName(),
                    $horizontal->getName(),
                    array('left','right')
                )
        );

        return parent::_prepareForm();
    }
}