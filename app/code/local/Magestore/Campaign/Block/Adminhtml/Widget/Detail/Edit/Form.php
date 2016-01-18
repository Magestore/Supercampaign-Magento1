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
class Magestore_Campaign_Block_Adminhtml_Widget_Detail_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare tab form's information
     *
     * @return Magestore_Campaign_Block_Adminhtml_Widget_Edit_Tab_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->getUrl('*/*/save', array(
                'id'    => $this->getRequest()->getParam('id'),
            )),
            'method'    => 'post',
            'enctype'   => 'multipart/form-data'
        ));

        $form->setUseContainer(true);
        $this->setForm($form);
        
        if (Mage::getSingleton('adminhtml/session')->getWidgetData()) {
            $data = Mage::getSingleton('adminhtml/session')->getWidgetData();
            Mage::getSingleton('adminhtml/session')->setWidgetData(null);
        } elseif (Mage::registry('widget_data')) {
            $data = Mage::registry('widget_data')->getData();
        }
        $fieldset = $form->addFieldset('widget_form', array(
            'legend'=>Mage::helper('campaign')->__('Widget Information')
        ));

        $fieldset->addField('title', 'text', array(
            'name'        => 'title',
            'label'        => Mage::helper('campaign')->__('Title'),
            'class'        => 'required-entry',
            'required'    => true,
        ));

        $type = $fieldset->addField('type', 'select', array(
            'name'         => 'type',
            'label'        => Mage::helper('campaign')->__('Widget type'),
            'class'        => 'required-entry',
            'required'     => true,
            'values'       => array(
                array('label'=>'Static widget', 'value'=> 'static'),
                array('label'=>'Slider', 'value'=> 'slider')),
            'value'        => array('static'),
        ));

        $fieldset->addField('witdh', 'text', array(
            'name'        => 'witdh',
            'label'        => Mage::helper('campaign')->__('Width'),
            //'class'        => 'required-entry',
            'required'    => false,
            'after_element_html' => 'px',
        ));

        $fieldset->addField('height', 'text', array(
            'name'        => 'height',
            'label'        => Mage::helper('campaign')->__('Height'),
            //'class'        => 'required-entry',
            'required'    => false,
            'after_element_html' => 'px',
        ));

        $fieldset->addField('css', 'editor', array(
            'name'        => 'css',
            'label'        => Mage::helper('campaign')->__('Custom css'),
            'title'        => Mage::helper('campaign')->__('Custom css'),
            'style'        => 'width:400px; height:50px;',
            'wysiwyg'    => false,
            //'required'    => true,
        ));

        if(!isset($data['status'])) $data['status'] = 1; //set default value
        $fieldset->addField('status', 'select', array(
            'name'         => 'status',
            'label'        => Mage::helper('campaign')->__('Status'),
            'values'       => Mage::getSingleton('campaign/status')->getOptionHash(),
        ));

        $fieldset->addField('copy_static_block', 'label', array(
            'label'        => Mage::helper('campaign')->__('Copy code for static block:'),
            'after_element_html' => "<strong>{{block type=\"campaign/widget\" id=\"".$this->getRequest()->getParam('id')."\"}}</strong>",
        ));

        $fieldset->addField('copy_template', 'label', array(
            'label'        => Mage::helper('campaign')->__('Copy code for template:'),
            'after_element_html' => "<strong>&lt;?php echo &#36;this-&gt;getLayout()-&gt;createBlock(\"campaign/widget\")-&gt;setId(".$this->getRequest()->getParam('id').")-&gt;toHtml(); ?&gt;</strong>",
        ));

        /*$howto = $form->addFieldset('widget_howto', array(
            'legend'=>Mage::helper('campaign')->__('Copy code')
        ));
        $howto->setNoContainer(true)->setHtmlContent(
            $this->getLayout()->createBlock('campaign/adminhtml_widget_edit_tab_form')
                ->setTemplate('campaign/widget_how_to_use.phtml')
                ->toHtml()
        );*/

        $form->setValues($data);
        return parent::_prepareForm();
    }

}