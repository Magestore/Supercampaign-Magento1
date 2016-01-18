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
 * Campaign Edit Block
 * 
 * @category     Magestore
 * @package     Magestore_Campaign
 * @author      Magestore Developer
 */
class Magestore_Campaign_Block_Adminhtml_Widget_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        
        $this->_objectId = 'id';
        $this->_blockGroup = 'campaign';
        $this->_controller = 'adminhtml_widget';
        
        $this->_updateButton('save', 'label', Mage::helper('campaign')->__('Save and exit'));
        $this->_updateButton('delete', 'label', Mage::helper('campaign')->__('Delete Widget'));
        
        $this->_addButton('saveandcontinue', array(
            'label'        => Mage::helper('adminhtml')->__('Save and continute edit'),
            'onclick'    => 'saveAndContinueEdit()',
            'class'        => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('campaign_content') == null)
                    tinyMCE.execCommand('mceAddControl', false, 'campaign_content');
                else
                    tinyMCE.execCommand('mceRemoveControl', false, 'campaign_content');
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/detail/');
            }

            var resetTemplate = function(url, select_template_id, editor_element_id, campaign_id, call_back_func, message){
                var _confirm = true;
                if(typeof(message) !== 'undefined' && message !== ''){
                    _confirm = confirm(message);
                }
                if(_confirm){
                    var template_id = $(select_template_id).getValue();
                    new Ajax.Request(url, {
                        parameters: {
                            campaign_id: campaign_id,
                            template_id: template_id,
                            isAjax:      true
                        },
                        onComplete: function(response) {
                            res = response.responseText.evalJSON();
                            if(res.status == 'success'){
                                updateContentEditor(editor_element_id, res.content);
                                if(typeof(call_back_func) == 'function'){
                                    call_back_func('success', res.content);
                                }
                            }
                        }
                    });
                }else{
                    if(typeof(call_back_func) == 'function'){
                        call_back_func('not_confirm', '');
                    }
                }
            }

            /* to update text of content Mce Editor */
            var updateContentEditor = function(editor_element_id, content){
                /* Sets the content of a specific editor (my_editor in this example) */
                if(typeof(tinyMCE.get(editor_element_id)) !== 'undefined'){
                    if(typeof(content) == 'undefined' || content == ''){
                        window['wysiwyg'+editor_element_id].turnOff();
                        window['wysiwyg'+editor_element_id].turnOn();
                        tinyMCE.get(editor_element_id).setContent($(editor_element_id).getValue());
                    }else{
                        tinyMCE.get(editor_element_id).setContent(content);
                    }
                }else{
                    $(editor_element_id).setValue(content);
                }
            }
        ";
    }
    
    /**
     * get text to show in header when edit an item
     *
     * @return string
     */
    public function getHeaderText()
    {
        if (Mage::registry('widget_data')
            && Mage::registry('widget_data')->getId()
        ) {
            return Mage::helper('campaign')->__("Edit widget '%s'",
                $this->htmlEscape(Mage::registry('widget_data')->getTitle())
            );
        }
        return Mage::helper('campaign')->__('Add new Widget');
    }
}