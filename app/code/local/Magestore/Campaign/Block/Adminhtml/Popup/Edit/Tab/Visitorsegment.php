<?php

class Magestore_Campaign_Block_Adminhtml_Popup_Edit_Tab_Visitorsegment extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm(){
        $form = new Varien_Data_Form();
        $this->setForm($form);

        if (Mage::getSingleton('adminhtml/session')->getPopupData()){
            $data = Mage::getSingleton('adminhtml/session')->getPopupData();
            Mage::getSingleton('adminhtml/session')->setPopupData(null);
        }elseif(Mage::registry('popup_data'))
            $data = Mage::registry('popup_data')->getData();

        $fieldset = $form->addFieldset('popup_form', array('legend'=>Mage::helper('campaign')->__('Visitorsegment information')));

        $fieldset->addField('country_id', 'text', array(
            'label'		=> Mage::helper('campaign')->__('Country:'),
            'required'	=> false,
            'note'      => 'Show popup for country.',
            'name'		=> 'country_id',
        ));

        $fieldset->addField('devices', 'multiselect', array(
            'label'		=> Mage::helper('campaign')->__('Devices'),
            'required'	=> true,
            'name'		=> 'devices',
            'note'      => "Allow show popup for devices.",
            'values' => array(
                array(
                    'value' => 0,
                    'label' => Mage::helper('campaign')->__('All Devices'),
                ),
                array(
                    'value' => 1,
                    'label' => Mage::helper('campaign')->__('PC'),
                ),
                array(
                    'value' => 2,
                    'label' => Mage::helper('campaign')->__('Laptop'),
                ),
                array(
                    'value' => 3,
                    'label' => Mage::helper('campaign')->__('Tablet'),
                ),
                array(
                    'value' => 4,
                    'label' => Mage::helper('campaign')->__('Mobiphone'),
                ),
            ),
        ));

        $fieldset->addField('cookies_enabled', 'select', array(
            'label'		=> Mage::helper('campaign')->__('Cookies Enabled:'),
            'required'	=> true,
            'name'		=> 'cookies_enabled',
            'note'      => "Allow enable cookie to config show popup .",
            'values' => array(
                array(
                    'value' => 0,
                    'label' => Mage::helper('campaign')->__('Yes'),
                ),
                array(
                    'value' => 1,
                    'label' => Mage::helper('campaign')->__('No'),
                ),
            ),
        ));

        $fieldset->addField('user_login', 'multiselect', array(
            'label'		=> Mage::helper('campaign')->__('User Login:'),
            'required'	=> true,
            'name'		=> 'user_login',
            'note'      => "Show popup when user login.",
            'values' => array(
                array(
                    'value' => 0,
                    'label' => Mage::helper('campaign')->__('Mutil Registed'),
                ),
                array(
                    'value' => 1,
                    'label' => Mage::helper('campaign')->__('Login'),
                ),
                array(
                    'value' => 2,
                    'label' => Mage::helper('campaign')->__('Unloged'),
                ),
                array(
                    'value' => 3,
                    'label' => Mage::helper('campaign')->__('Unregistered'),
                ),
            ),
        ));

        $fieldset->addField('if_returning', 'select', array(
            'label'		=> Mage::helper('campaign')->__('Return or new customer:'),
            'required'	=> true,
            'name'		=> 'if_returning',
            'note'      => "Allow show popup for return or new customer .",
            'values' => array(
                array(
                    'value' => 0,
                    'label' => Mage::helper('campaign')->__('Return'),
                ),
                array(
                    'value' => 1,
                    'label' => Mage::helper('campaign')->__('New Customer'),
                ),
            ),
        ));

        $fieldset->addField('customer_group_id', 'multiselect', array(
            'label' => Mage::helper('campaign')->__('Customer groups'),
            'title' => Mage::helper('campaign')->__('Customer groups'),
            'name' => 'customer_group_id',
            'required' => true,
            'values' => Mage::getResourceModel('customer/group_collection')
                    ->toOptionArray()
        ));

        $fieldset->addField('cart_subtotal_min', 'text', array(
            'label'		=> Mage::helper('campaign')->__('Cart subtotal less than:'),
            'required'	=> false,
            'note'      => "Show popup when customer's cart less than.",
            'name'		=> 'cart_subtotal_min',
        ));

        $fieldset->addField('user_ip', 'text', array(
            'label'		=> Mage::helper('campaign')->__('User Ip:'),
            'required'	=> false,
            'note'      => 'Show popup for user id.',
            'name'		=> 'user_ip',
        ));

        $form->setValues($data);
        return parent::_prepareForm();
    }
}