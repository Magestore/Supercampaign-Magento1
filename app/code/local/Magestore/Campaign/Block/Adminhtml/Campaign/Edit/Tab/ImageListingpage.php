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
class Magestore_Campaign_Block_Adminhtml_Campaign_Edit_Tab_ImageListingpage extends Mage_Adminhtml_Block_Widget_Form
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

        if (Mage::getSingleton('adminhtml/session')->getBannerlistpageData()) {
            $data = Mage::getSingleton('adminhtml/session')->getBannerlistpageData();
            Mage::getSingleton('adminhtml/session')->setBannerlistpageData(null);
        } elseif (Mage::registry('bannerlistpage_data')) {
            $data = Mage::registry('bannerlistpage_data')->getData();
        }

        $fieldset = $form->addFieldset('campaign_form', array(
            'legend'=>Mage::helper('campaign')->__('Banner listing page information')
        ));

        $fieldset->addField('bannerlistpage_input_banner', 'image', array(
            'name'        => 'bannerlistpage_input_banner',
            'label'        => Mage::helper('campaign')->__('Input Banner Listing Page:'),
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


        $fieldset->addField('bannerlistpage_link_attached', 'text', array(
            'name'        => 'bannerlistpage_link_attached',
            'label'        => Mage::helper('campaign')->__('Link attached:'),
            //'class'        => 'required-entry',
            'required'    => false,
        ));

        $fieldset->addField('bannerlistpage_include', 'text', array(
            'name'        => 'bannerlistpage_include',
            'label'       => Mage::helper('campaign')->__('Include url:'),
            'required'    => false,
        ));

        $fieldset->addField('bannerlistpage_exclude', 'text', array(
            'name'        => 'bannerlistpage_exclude',
            'label'       => Mage::helper('campaign')->__('Exclude url:'),
            'required'    => false,
            'note'  => 'How to use Include and Exclude: `;` - is separator between two url<br/>
                <strong>`*`</strong> exclude all pages<br/>
                <strong>`/`</strong> is exclude home page<br/>
                <strong>`.../abc/...`</strong> for special url page<br/>
                Eg:<strong> *; /; checkout/</strong>',
        ));

        $fieldset->addField('bannerlistpage_status', 'select', array(
            'label'		=> Mage::helper('campaign')->__('Status:'),
            'name'		=> 'bannerlistpage_status',
            //'required'  => false,
            'values'    => Mage::getSingleton('campaign/status')->getOptionHash(),
        ));

        $howto = $form->addFieldset('campaign_howto', array(
            'legend'=>Mage::helper('campaign')->__('How to use')
        ));
        $howto->setNoContainer(true)->setHtmlContent(
            $this->getLayout()->createBlock('campaign/banner')
                ->setTemplate('campaign/banner_how_to_use.phtml')
                ->toHtml()
        );

        $form->setValues($data);
        return parent::_prepareForm();
    }
}