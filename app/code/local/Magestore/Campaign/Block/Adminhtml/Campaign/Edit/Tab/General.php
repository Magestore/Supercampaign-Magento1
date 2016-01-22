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


        //convert timezone
        $time_zone      = $this->__('Time Zone (UTC): %s', Mage::getStoreConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_TIMEZONE));

        if (!Mage::app()->isSingleStoreMode()) {
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
        }

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
            'style'     => 'width:274px;',
            'note'     => $time_zone,
        ));

         $fieldset->addField('use_coupon', 'select', array(
            'name'         => 'use_coupon',
            'label'        => Mage::helper('campaign')->__('Use Coupon Code:'),
            //'class'        => 'required-entry',
            'required'     => true,
            'values'       => array(array('label'=>'Yes', 'value'=> 1), array('label'=>'No', 'value'=> 2)),
            'value'        => array(1),
        ));

        $fieldset->addField('coupon_code_type', 'select', array(
            'name'         => 'coupon_code_type',
            'label'        => Mage::helper('campaign')->__('Choose Coupon Code type:'),
            'class'        => 'required-entry',
            'required'     => true,
            'values'       => array(array('label'=>'Manual', 'value'=> 1), array('label'=>'Promotion', 'value'=> 2)),
            'value'        => array(1),
        ));

        $fieldset->addField('promo_rule_id', 'text', array(
            'name'        => 'promo_rule_id',
            'label'        => Mage::helper('campaign')->__('Select Promotion Shopping Cart Rule:'),
            'required'    => true,
        ));

        $fieldset->addField('coupon_code', 'text', array(
            'name'        => 'coupon_code',
            'label'        => Mage::helper('campaign')->__('Coupon Code:'),
            'required'    => false,
        ));

        if(!isset($data['status'])) $data['status'] = 1; //set default value
        $fieldset->addField('status', 'select', array(
            'name'         => 'status',
            'label'        => Mage::helper('campaign')->__('Status:'),
            'values'       => Mage::getSingleton('campaign/status')->getOptionHash(),
        ));

        $fieldset->addField('countdown_type', 'select', array(
            'name'         => 'countdown_type',
            'label'        => Mage::helper('campaign')->__('Countdown Type:'),
            'class'        => 'required-entry',
            'required'     => true,
            'values'       => array(array('label'=>'Short', 'value'=> 1), array('label'=>'Medium', 'value'=> 2),array('label'=>'Long', 'value'=> 3)),
            'value'        => array(1),
        ));

        $fieldset->addField('countdown_products', 'text', array(
            'name'        => 'countdown_products',
            'label'        => Mage::helper('campaign')->__('Countdown With Products:'),
            'required'    => false,
        ));

        $fieldset->addField('countdown_onoff', 'select', array(
            'name'         => 'countdown_onoff',
            'label'        => Mage::helper('campaign')->__('Countdown On/Off:'),
            'class'        => 'required-entry',
            'required'     => true,
            'values'       => array(array('label'=>'On', 'value'=> 1), array('label'=>'Off', 'value'=> 2)),
            'value'        => array(1),
        ));

        $form->setValues($data);
        return parent::_prepareForm();
    }

    private function _convertToTimezone($date, $locale = null, $format = null){
        $datetime = new Zend_Date($date);
        if($locale == null){
            $locale = Mage::getStoreConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_LOCALE);
        }
        if($format == null){
            $format = 'y-MM-dd HH:mm:00';
        }
        $datetime->setLocale($locale)
            ->setTimezone(Mage::getStoreConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_TIMEZONE));
        return $datetime->get($format);
    }
}