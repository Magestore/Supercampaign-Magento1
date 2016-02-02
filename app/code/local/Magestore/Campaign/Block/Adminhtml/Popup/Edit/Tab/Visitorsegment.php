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

        $fieldset->addField('devices', 'multiselect', array(
            'label'		=> Mage::helper('campaign')->__('Devices'),
            'name'		=> 'devices',
            'note'      => "Allow show popup for devices.",
            'values' => array(
                array(
                    'value' => 'all_device',
                    'label' => Mage::helper('campaign')->__('All Devices'),
                ),
                array(
                    'value' => 'pc_laptop',
                    'label' => Mage::helper('campaign')->__('PC and Laptop'),
                ),
                array(
                    'value' => 'tablet_mobile',
                    'label' => Mage::helper('campaign')->__('Mobile and Tablet'),
                ),
            ),
        ));

        $enable_cookie = $fieldset->addField('cookies_enabled', 'select', array(
            'label'		=> Mage::helper('campaign')->__('Cookies Enabled:'),
            'required'	=> true,
            'name'		=> 'cookies_enabled',
            'note'      => "Allow enable cookie to config show popup .",
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

        $fieldset->addField('login_user', 'select', array(
            'label'		=> Mage::helper('campaign')->__('User Login:'),
            'name'		=> 'login_user',
            'note'      => "Show popup when user login.",
            'values' => array(
                array(
                    'value' => 'all_user',
                    'label' => Mage::helper('campaign')->__('All User'),
                ),
                array(
                    'value' => 'registed_loged',
                    'label' => Mage::helper('campaign')->__('Loged User'),
                ),
                array(
                    'value' => 'logout_not_register',
                    'label' => Mage::helper('campaign')->__('Unloged User'),
                ),
            ),
        ));

       $customer_cookie = $fieldset->addField('returning_user', 'select', array(
            'label'		=> Mage::helper('campaign')->__('Return or new customer:'),
            'required'	=> true,
            'name'		=> 'returning_user',
            'note'      => "Allow show popup for return or new customer .",
            'values' => array(
                array(
                    'value' => 'return',
                    'label' => Mage::helper('campaign')->__('Return'),
                ),
                array(
                    'value' => 'new',
                    'label' => Mage::helper('campaign')->__('New Customer'),
                ),
            ),
        ));


        $fieldset->addField('customer_group_ids', 'multiselect', array(
            'label'		=> Mage::helper('campaign')->__('Customer groups:'),
            'name'		=> 'customer_group_ids',
            'note'      => "Show popup for customer group.",
            'values' => array(
                array(
                    'value' => 'all_group',
                    'label' => Mage::helper('campaign')->__('All Group'),
                ),
                array(
                    'value' => 'not_loged_in',
                    'label' => Mage::helper('campaign')->__('Not loged in'),
                ),
                array(
                    'value' => 'general',
                    'label' => Mage::helper('campaign')->__('General'),
                ),
                array(
                    'value' => 'wholesale',
                    'label' => Mage::helper('campaign')->__('Wholesale'),
                ),
                array(
                    'value' => 'vip_member',
                    'label' => Mage::helper('campaign')->__('Vip member'),
                ),
                array(
                    'value' => 'private_sale_member',
                    'label' => Mage::helper('campaign')->__('Private sale member'),
                ),
            ),
        ));



        $fieldset->addField('user_ip', 'text', array(
            'label'		=> Mage::helper('campaign')->__('User Ip:'),
            'required'	=> false,
            'note'      => 'Show popup for user id.',
            'name'		=> 'user_ip',
        ));

        if($data['devices'] == ''){$data['devices'] = 'all_device';}
        if($data['login_user'] == ''){$data['login_user'] = 'registed';}
        if($data['customer_group_ids'] == ''){$data['customer_group_ids'] = 'all_group';}

        $form->setValues($data);

        $this->setForm($form);
        $this->setChild('form_after', $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
                ->addFieldMap($enable_cookie->getHtmlId(), $enable_cookie->getName())
                ->addFieldMap($customer_cookie->getHtmlId(), $customer_cookie->getName())
                ->addFieldDependence(
                    $customer_cookie->getName(),
                    $enable_cookie->getName(),
                    '1'
                )
        );


        return parent::_prepareForm();
    }
}