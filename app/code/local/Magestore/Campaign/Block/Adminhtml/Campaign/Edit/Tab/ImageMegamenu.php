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
class Magestore_Campaign_Block_Adminhtml_Campaign_Edit_Tab_ImageMegamenu extends Mage_Adminhtml_Block_Widget_Form
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
        $data = array();

        if (Mage::getSingleton('adminhtml/session')->getBannermenuData()) {
            $data = Mage::getSingleton('adminhtml/session')->getBannermenuData();
            Mage::getSingleton('adminhtml/session')->setBannermenuData(null);
        } elseif (Mage::registry('bannermenu_data')) {
            $data = Mage::registry('bannermenu_data')->getData();
        }

        $fieldset = $form->addFieldset('campaign_form', array(
            'legend'=>Mage::helper('campaign')->__('Banner menu page information')
        ));

        $fieldset->addField('bannermenu_input_bannermenu', 'image', array(
            'name'        => 'bannermenu_input_bannermenu',
            'label'        => Mage::helper('campaign')->__('Input Banner Menu:'),
            //'class'        => 'required-entry',
            'required'    => false,
            'width'       => '50px'
        ));

//        $fieldset->addField('bannerlistpage_path_bannerlistpage', 'text', array(
//            'name'        => 'bannerlistpage_path_bannerlistpage',
//            'label'        => Mage::helper('campaign')->__('Path folder image:'),
//            'column_css_class'=>'no-display',
//            'header_css_class'=>'no-display',
//            'required'    => false,
//        ));


        $fieldset->addField('bannermenu_link_attached', 'text', array(
            'name'        => 'bannermenu_link_attached',
            'label'        => Mage::helper('campaign')->__('Link attached:'),
            //'class'        => 'required-entry',
            'required'    => false,
        ));

        $fieldset->addField('bannermenu_status', 'select', array(
            'label'		=> Mage::helper('campaign')->__('Status:'),
            'name'		=> 'bannermenu_status',
            //'required'  => false,
            'values'    => Mage::getSingleton('campaign/status')->getOptionHash(),
        ));

        $form->setValues($data);
        return parent::_prepareForm();
    }
}