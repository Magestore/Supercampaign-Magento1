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
class Magestore_Campaign_Block_Adminhtml_Campaign_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form
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

        $fieldset = $form->addFieldset('campaign_form', array(
            'legend'=>Mage::helper('campaign')->__('General Information')
        ));

        $fieldset->addField('name', 'text', array(
            'name'        => 'name',
            'label'        => Mage::helper('campaign')->__('Name:'),
            'class'        => 'required-entry',
            'required'    => true,
        ));

        if(!isset($data['status'])) $data['status'] = 1; //set default value
        $fieldset->addField('status', 'select', array(
            'name'         => 'status',
            'label'        => Mage::helper('campaign')->__('Status:'),
            'values'       => Mage::getSingleton('campaign/status')->getOptionHash(),
        ));


        //convert timezone
        $time_zone      = $this->__('Time Zone (UTC): %s', Mage::getStoreConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_TIMEZONE));

        /*if (!Mage::app()->isSingleStoreMode()) {
            $field = $fieldset->addField('store', 'multiselect', array(
                'name'      => 'stores[]',
                'label'     => $this->__('Store View:'),
                'title'     => $this->__('Store View:'),
                'required'  => true,
                'style'        => 'height:180px;',
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            ));
            $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
            $field->setRenderer($renderer);
        }
        else {
            $fieldset->addField('store', 'hidden', array(
                'name'      => 'stores[]',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
            $data['store'] = Mage::app()->getStore(true)->getId();
        }*/

        $fieldset->addField('priority', 'text', array(
            'name'        => 'priority',
            'label'       => Mage::helper('campaign')->__('Priority'),
            'value'       => '0',
            'required'    => false,
            'class'       => 'validate-number',
            'note'     => $this->__('Highest number has highest priority'),
        ));

        $fieldset->addField('description', 'editor', array(
            'name'        => 'description',
            'label'        => Mage::helper('campaign')->__('Description:'),
            'title'        => Mage::helper('campaign')->__('Description:'),
            'style'        => 'width:400px; height:50px;',
            'wysiwyg'    => false,
        ));

        $fieldset->addField('start_time', 'date', array(
            'name'      => 'start_time',
            'label'     => Mage::helper('campaign')->__('Start time'),
            'image'     => $this->getSkinUrl('images/grid-cal.gif'),
            'format'    => 'y-MM-dd HH:mm:00',
            'time'      => true,
            'required'    => true,
            //'class'     => 'validate-date',
            'style'     => 'width:274px;',
            'note'     => $time_zone,
        ));


        $fieldset->addField('end_time', 'date', array(
            'name'      => 'end_time',
            'label'     => Mage::helper('campaign')->__('End time'),
            'image'     => $this->getSkinUrl('images/grid-cal.gif'),
            'format'    => 'y-MM-dd HH:mm:00',
            'time'      => true,
            'required'    => true,
            //'class'     => 'validate-date',
            'style'     => 'width:274px;',
            'note'     => $time_zone,
        ));

        if(!isset($data['use_coupon'])) $data['use_coupon'] = 2;
        $use_coupon = $fieldset->addField('use_coupon', 'select', array(
            'name'         => 'use_coupon',
            'label'        => Mage::helper('campaign')->__('Use Coupon Code:'),
            //'class'        => 'required-entry',
            'required'     => true,
            'values'       => array(array('label'=>'Yes', 'value'=> 1), array('label'=>'No', 'value'=> 2)),
        ));

        $coupon_code_type = $fieldset->addField('coupon_code_type', 'select', array(
            'name'         => 'coupon_code_type',
            'label'        => Mage::helper('campaign')->__('Choose Coupon Code type:'),
            'class'        => 'required-entry',
            'required'     => true,
            'values'       => array(
                array('label'=>'Static', 'value'=> Magestore_Campaign_Model_Giftcode::GIFT_CODE_TYPE_STATIC),
                array('label'=>'Promotion', 'value'=> Magestore_Campaign_Model_Giftcode::GIFT_CODE_TYPE_PROMOTION)
            ),
            'value'        => array(Magestore_Campaign_Model_Giftcode::GIFT_CODE_TYPE_STATIC),
        ));

        $promo_quote = $fieldset->addField('promo_rule_id', 'select', array(
            'name'        => 'promo_rule_id',
            'label'        => Mage::helper('campaign')->__('Select Promotion Shopping Cart Rule:'),
            'required'    => true,
            'values'       => Mage::getModel('campaign/giftcode')->getShoppingCartPriceRuleSelectOption(),
            'note'   => Mage::helper('campaign')->__('If you can not find your Promotion please %s for status must enabled and date expire is later current date.', '<a href="'
                .$this->getUrl('adminhtml/promo_quote/index/')
                .'" alt="check Promotion" target="_blank">check it here</a>'),
        ));

        $coupon_code = $fieldset->addField('coupon_code', 'text', array(
            'name'         => 'coupon_code',
            'label'        => Mage::helper('campaign')->__('Coupon Code'),
            //'class'        => 'required-entry',
            'note'   => Mage::helper('campaign')->__('Paste your text code here.'),
            'required'     => true,
        ));

//        $fieldset->addField('countdown_type', 'select', array(
//            'name'         => 'countdown_type',
//            'label'        => Mage::helper('campaign')->__('Countdown Type:'),
//            'class'        => 'required-entry',
//            'required'     => true,
//            'values'       => array(array('label'=>'Short', 'value'=> 1), array('label'=>'Medium', 'value'=> 2),array('label'=>'Long', 'value'=> 3)),
//            'value'        => array(1),
//        ));
//
//        $countdown_products = $fieldset->addField('countdown_products', 'text', array(
//            'label' => Mage::helper('campaign')->__('Countdown with products'),
//            'name' => 'countdown_products',
//            'class' => 'rule-param',
//            'after_element_html' => '<a id="product_link" href="javascript:void(0)" onclick="toggleMainProducts()"><img src="' . $this->getSkinUrl('images/rule_chooser_trigger.gif') . '" alt="" class="v-middle rule-chooser-trigger" title="Select Products"></a><input type="hidden" value="'.$productIds.'" id="product_all_ids"/><div id="main_products_select" style="display:none;width:640px"></div>
//                <script type="text/javascript">
//                    function toggleMainProducts(){
//                        if($("main_products_select").style.display == "none"){
//                            var url = "' . $this->getUrl('campaignadmin/adminhtml_campaign/chooserMainProducts') . '";
//                            var params = $("generalcountdown_products").value.split(", ");
//                            var parameters = {"form_key": FORM_KEY,"selected[]":params };
//                            var request = new Ajax.Request(url,
//                            {
//                                evalScripts: true,
//                                parameters: parameters,
//                                onComplete:function(transport){
//                                    $("main_products_select").update(transport.responseText);
//                                    $("main_products_select").style.display = "block";
//                                }
//                            });
//                        }else{
//                            $("main_products_select").style.display = "none";
//                        }
//                    };
//
//
//                </script>'
//        ));


//        $fieldset->addField('countdown_onoff', 'select', array(
//            'name'         => 'countdown_onoff',
//            'label'        => Mage::helper('campaign')->__('Countdown On/Off:'),
//            'class'        => 'required-entry',
//            'required'     => true,
//            'values'       => array(array('label'=>'On', 'value'=> 1), array('label'=>'Off', 'value'=> 0)),
//            'value'        => array(0),
//        ));



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

        $enable_cookie = $viSegmentFieldset->addField('cookies_enabled', 'select', array(
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

        if($data['devices'] == ''){$data['devices'] = 'all_device';}
        if($data['login_user'] == ''){$data['login_user'] = 'registed';}
        if($data['customer_group_ids'] == ''){$data['customer_group_ids'] = 'all_group';}

        $form->setValues($data);
        $this->setForm($form);

        // field dependencies
        $this->setChild('form_after', $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
            ->addFieldMap($use_coupon->getHtmlId(), $use_coupon->getName())
            ->addFieldMap($coupon_code_type->getHtmlId(), $coupon_code_type->getName())
            ->addFieldMap($coupon_code->getHtmlId(), $coupon_code->getName())
            ->addFieldMap($promo_quote->getHtmlId(), $promo_quote->getName())
            ->addFieldDependence(
                $coupon_code_type->getName(),
                $use_coupon->getName(),
                1)
            ->addFieldDependence(
                $coupon_code->getName(),
                $coupon_code_type->getName(),
                Magestore_Campaign_Model_Giftcode::GIFT_CODE_TYPE_STATIC)
            ->addFieldDependence(
                $coupon_code->getName(),
                $use_coupon->getName(),
                1)
            ->addFieldDependence(
                $promo_quote->getName(),
                $coupon_code_type->getName(),
                Magestore_Campaign_Model_Giftcode::GIFT_CODE_TYPE_PROMOTION)
            ->addFieldDependence(
                $promo_quote->getName(),
                $use_coupon->getName(),
                1)
            ->addFieldMap($enable_cookie->getHtmlId(), $enable_cookie->getName())
            ->addFieldMap($customer_cookie->getHtmlId(), $customer_cookie->getName())
            ->addFieldMap($cookie_time->getHtmlId(), $cookie_time->getName())
            ->addFieldDependence(
                $customer_cookie->getName(),
                $enable_cookie->getName(),
                '1'
            )->addFieldDependence(
                $cookie_time->getName(),
                $enable_cookie->getName(),
                '1'
            )
        );

        return parent::_prepareForm();
    }

}