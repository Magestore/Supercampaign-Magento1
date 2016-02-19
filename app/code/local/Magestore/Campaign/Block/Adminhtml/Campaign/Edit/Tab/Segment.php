<?php
/**
 * Magestore
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category    Magestore
 * @package     Magestore_Campaign
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

/**
 * Campaign Edit Form Content Tab Block
 * 
 * @category    Magestore
 * @package     Magestore_Campaign
 * @author      Magestore Developer
 */
class Magestore_Campaign_Block_Adminhtml_Campaign_Edit_Tab_Segment extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare tab form's information
     *
     * @return Magestore_Campaign_Block_Adminhtml_Campaign_Edit_Tab_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('general');
        $form->setFieldNameSuffix('general');
        $this->setForm($form);
        
        if (Mage::getSingleton('adminhtml/session')->getCampaignData()) {
            $data = Mage::getSingleton('adminhtml/session')->getCampaignData();
            Mage::getSingleton('adminhtml/session')->setCampaignData(null);
        } elseif (Mage::registry('campaign_data')) {
            $data = Mage::registry('campaign_data')->getData();
        }


        $viSegmentFieldset = $form->addFieldset('campaign_segment', array(
            'legend'=>Mage::helper('campaign')->__('Visitorsegment information')
        ));
        $viSegmentFieldset->addField('devices', 'multiselect', array(
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

        /*$enable_cookie = $viSegmentFieldset->addField('cookies_enabled', 'select', array(
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
        ));*/

        $cookie_time = $viSegmentFieldset->addField('cookie_time', 'text', array(
            'label'		=> Mage::helper('campaign')->__('Cookie Life Time:'),
            'note'      => 'Set days for cookie to show popup.',
            'class'       => 'validate-number',
            'required'	=> false,
            'name'		=> 'cookie_time',
        ));

        $customer_cookie = $viSegmentFieldset->addField('returning_user', 'select', array(
            'label'		=> Mage::helper('campaign')->__('Return or new customer:'),
            'required'	=> true,
            'name'		=> 'returning_user',
            'note'      => "Allow show popup for return or new customer .",
            'values' => array(
                array(
                    'value' => 'alluser',
                    'label' => Mage::helper('campaign')->__('All User'),
                ),
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

        $viSegmentFieldset->addField('login_user', 'select', array(
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

        $viSegmentFieldset->addField('customer_group_ids', 'multiselect', array(
            'label'		=> Mage::helper('campaign')->__('Customer groups:'),
            'name'		=> 'customer_group_ids',
            'note'      => "Show popup for customer group.",
            'values' => array(
                array(
                    'value' => 'all_group',
                    'label' => Mage::helper('campaign')->__('All Group'),
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

        $viSegmentFieldset->addField('user_ip', 'text', array(
            'label'		=> Mage::helper('campaign')->__('User Ip:'),
            'required'	=> false,
            'note'      => "Show popup for user's ip.",
            'name'		=> 'user_ip',
        ));


        if($data['cookies_enabled'] == ''){$data['cookies_enabled'] = '1';}
        if($data['cookie_time'] == ''){$data['cookie_time'] = '1';}
        if($data['devices'] == ''){$data['devices'] = 'all_device';}
        if($data['login_user'] == ''){$data['login_user'] = 'registed';}
        if($data['customer_group_ids'] == ''){$data['customer_group_ids'] = 'all_group';}

        $form->setValues($data);
        $this->setForm($form);

        // field dependencies
        $this->setChild('form_after', $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
            //->addFieldMap($enable_cookie->getHtmlId(), $enable_cookie->getName())
            ->addFieldMap($customer_cookie->getHtmlId(), $customer_cookie->getName())
            ->addFieldMap($cookie_time->getHtmlId(), $cookie_time->getName())
            /*->addFieldDependence(
                $customer_cookie->getName(),
                $enable_cookie->getName(),
                '1'
            )*/
            /*->addFieldDependence(
                $cookie_time->getName(),
                $enable_cookie->getName(),
                '1'
            )*/
        );

        return parent::_prepareForm();
    }

}