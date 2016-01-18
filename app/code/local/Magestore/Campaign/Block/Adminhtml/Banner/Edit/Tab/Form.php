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
class Magestore_Campaign_Block_Adminhtml_Banner_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare tab form's information
     *
     * @return Magestore_Campaign_Block_Adminhtml_Campaign_Edit_Tab_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix(Magestore_Campaign_Model_Widget_Banner::PREFIX_DATA);
        $form->setFieldNameSuffix(Magestore_Campaign_Model_Widget_Banner::PREFIX_DATA);
        $this->setForm($form);
        $data = array();
        if (Mage::getSingleton('adminhtml/session')->getWidgetBannerData()) {
            $data = Mage::getSingleton('adminhtml/session')->getWidgetBannerData();
            Mage::getSingleton('adminhtml/session')->setWidgetData(null);
        } elseif (Mage::registry('widget_banner_data')) {
            $data = Mage::registry('widget_banner_data')->getData();
        }
        $fieldset = $form->addFieldset('widget_banner_form', array(
            'legend'=>Mage::helper('campaign')->__('Widget Banner information')
        ));

        $fieldset->addField('status', 'select', array(
            'name'		=> 'status',
            'label'		=> Mage::helper('campaign')->__('Status:'),
            'required'  => false,
            'values'    => Mage::getSingleton('campaign/status')->getOptionHash(),
        ));

        $fieldset->addField('type', 'hidden', array(
            'name'		=> 'type',
            'required'  => false,
        ));

        //select template
        $fieldset->addType('template_selector' , 'Magestore_Campaign_Block_Adminhtml_Banner_Edit_Tab_Element_TemplateSelector');
        $fieldset->addField('template_id', 'template_selector', array(
            'name'         => 'template_id',
            'label'        => Mage::helper('campaign')->__('Default Template'),
            'values'       => Mage::getModel('campaign/template')->getSelectOptionGroup(
                Magestore_Campaign_Model_Widget_Banner::TEMPLATE_GROUP, 'default'),
            'callback_func'  => 'loadWidgetBannerTemplate',
            'note'  => 'Click to load default template content',
            'after_element_html' => '<script type="text/javascript">function loadWidgetBannerTemplate(id){
    $("'.Magestore_Campaign_Model_Widget_Banner::PREFIX_DATA.'type").setValue(id);
    loadTemplate("'.$this->getUrl('campaignadmin/adminhtml_loadtemplate/index/', array('isAjax'=>true)).'",
        id, "'.$form->getFieldNameSuffix().'content", "'.Mage::app()->getRequest()->getParam('id').'", "",
        "Loading and reset content for Widget Banner. Are you sure?"
    );
}</script>'
        ));

        $fieldset->addField('content', 'editor', array(
            'name' => 'content',
            'label' => Mage::helper('cms')->__('Content'),
            'title' => Mage::helper('cms')->__('Content'),
            'style' => 'width:597px; height: 270px;',
            'required' => false,
            'config' => Mage::getSingleton('cms/wysiwyg_config')->getConfig(),
            'wysiwyg' => true,
            'after_element_html' =>  '<p class="note" style="width: 100%;box-sizing: border-box;"><span>'.Mage::helper('campaign')->__('How to add countdown block: use "{{block type="campaign/countdown" locate="header" style="medium"}}" insert into header content. Note: locate="header|sidebar|product|popup", style="short|medium|long|text"').'</span></p>',
        ));

        $fieldset->addField('css', 'textarea', array(
            'name'         => 'css',
            'label'        => Mage::helper('campaign')->__('Custom CSS'),
            'style' => 'width:597px; height: 70px;',
        ));


        $fieldset->addField('include', 'text', array(
            'name'         => 'include',
            'label'        => Mage::helper('campaign')->__('Include Urls'),
            'note'  => 'Eg: *; /; */inventory/*',
        ));

        $fieldset->addField('exclude', 'text', array(
            'name'         => 'exclude',
            'label'        => Mage::helper('campaign')->__('Exclude Urls'),
            'note'  => 'Eg: *; /; */checkout/*',
        ));

        $fieldset->addType('widget_grid' , 'Magestore_Campaign_Block_Adminhtml_Banner_Edit_Tab_Element_GridSelector');
        $fieldset->addField('widget_selected_ids', 'widget_grid', array(
            'name'		=> 'widget_selected_ids',
            'label'		=> Mage::helper('campaign')->__('Select Widget'),
            'required'  => false,
        ));

//        $fieldset->addField('link_attached', 'text', array(
//            'name'        => 'link_attached',
//            'label'        => Mage::helper('campaign')->__('Link attached:'),
//        ));


        $form->setValues($data);
        return parent::_prepareForm();
    }
}