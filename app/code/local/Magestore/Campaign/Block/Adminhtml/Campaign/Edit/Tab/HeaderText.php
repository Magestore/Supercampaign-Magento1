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
class Magestore_Campaign_Block_Adminhtml_Campaign_Edit_Tab_HeaderText extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare tab form's information
     *
     * @return Magestore_Campaign_Block_Adminhtml_Campaign_Edit_Tab_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix(Magestore_Campaign_Model_Headertext::PREFIX);
        $form->setFieldNameSuffix(Magestore_Campaign_Model_Headertext::PREFIX);
        $this->setForm($form);
        $data = array();
        if (Mage::getSingleton('adminhtml/session')->getHeadertextData()) {
            $data = Mage::getSingleton('adminhtml/session')->getHeadertextData();
            Mage::getSingleton('adminhtml/session')->setHeadertextData(null);
        } elseif (Mage::registry('headertext_data')) {
            $data = Mage::registry('headertext_data')->getData();
        }
        $fieldset = $form->addFieldset('campaign_form', array(
            'legend'=>Mage::helper('campaign')->__('Header Text information')
        ));

        $fieldset->addField('status', 'select', array(
            'name'		=> 'status',
            'label'		=> Mage::helper('campaign')->__('Status:'),
            'required'  => false,
            'values'    => Mage::getSingleton('campaign/status')->getOptionHash(),
        ));

        //select template
        $fieldset->addField('template_id', 'select', array(
            'name'         => 'template_id',
            'label'        => Mage::helper('campaign')->__('Template'),
            'class'        => 'required-entry',
            'required'     => true,
            'values'       => Mage::getModel('campaign/template')->getOptions(
                Magestore_Campaign_Model_Headertext::TEMPLATE_GROUP),
            'value'        => array(1),
            'onchange'  => 'resetHeadertextTemplate()'
        ));

        //link to reset or edit
        //$template_id = (isset($data['template_id'])) ? $data['template_id']:'';
        $campaign_id = Mage::app()->getRequest()->getParam('id');
        //if($template_id != ''){
            $resetLink = '<div>
                <a href="javascript:resetHeadertextTemplate()"><span style="color: #f73e00;">Reset / Reload Content</span></a>
                </div>
                <script>
                function resetHeadertextTemplate(){
                    resetTemplate(\''.$this->getUrl('campaignadmin/adminhtml_resettemplate/index/', array('isAjax'=>true)).'\',
                    \''.$form->getHtmlIdPrefix().'template_id\',
                    \''.$form->getHtmlIdPrefix().'content\',
                    \''.$campaign_id.'\',
                    \'\',
                    \''.Mage::helper('campaign')->__('Your template type has changed. Do you want to reload default content of it?').'\');
                }
                </script>
                ';
        //}else{
        //    $resetLink = '';
        //}
        //create template overview image
        $overview = $this->getLayout()->createBlock('campaign/adminhtml_overview_template');
        $overview->setId($form->getHtmlIdPrefix().'template_id')
            ->setObject($form->getHtmlIdPrefix().'overview')
            ->setOptionImages(Mage::getModel('campaign/template')->getOverviews(
                Magestore_Campaign_Model_Headertext::TEMPLATE_GROUP));
        $fieldset->addField('overview', 'label', array(
            'label'        => Mage::helper('campaign')->__('Overview'),
            'value'        => Mage::helper('campaign')->__('Overview'),
            'after_element_html'    => $overview->toHtml().'<br/>'.$resetLink,
        ));

        //if($template_id != '') {
            $fieldset->addField('content', 'editor', array(
                'name' => 'content',
                'label' => Mage::helper('cms')->__('Content'),
                'title' => Mage::helper('cms')->__('Content'),
                'style' => 'width:597px; height: 270px;',
                'required' => false,
                'config' => Mage::getSingleton('cms/wysiwyg_config')->getConfig(),
                'wysiwyg' => true,
                'note'   => Mage::helper('campaign')->__('How to add countdown block: use "{{block type="campaign/countdown" locate="header" style="medium"}}" insert into header content. Note: locate="header|sidebar|product|popup", style="short|medium|long|text"'),
            ));
        //}

//        $fieldset->addField('is_countdown', 'select', array(
//            'name'		=> 'is_countdown',
//            'label'		=> Mage::helper('campaign')->__('Is show Countdown:'),
//            'values'    => array(array('label'=>'No', 'value'=>'2'), array('label'=>'Yes', 'value'=>'1')),
//            //'required'  => false,
//        ));

        $fieldset->addField('include_page', 'text', array(
            'name'         => 'include_page',
            'label'        => Mage::helper('campaign')->__('Include pages'),
            'note'  => 'Eg: *; /; */inventory/*',
        ));

        $fieldset->addField('exclude_page', 'text', array(
            'name'         => 'exclude_page',
            'label'        => Mage::helper('campaign')->__('Exclude pages'),
            'note'  => 'Eg: *; /; */checkout/*',
        ));

//        $fieldset->addField('link_attached', 'text', array(
//            'name'        => 'link_attached',
//            'label'        => Mage::helper('campaign')->__('Link attached:'),
//        ));


        $form->setValues($data);
        return parent::_prepareForm();
    }
}