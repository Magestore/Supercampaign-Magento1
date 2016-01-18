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
class Magestore_Campaign_Block_Adminhtml_Campaign_Edit_Tab_Popup extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Load Wysiwyg on demand and Prepare layout
     */
//    protected function _prepareLayout()
//    {
//        parent::_prepareLayout();
//        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
//            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
//        }
//    }

    /**
     * prepare tab form's information
     *
     * @return Magestore_Campaign_Block_Adminhtml_Campaign_Edit_Tab_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        
        if (Mage::getSingleton('adminhtml/session')->getPopupData()) {
            $data = Mage::getSingleton('adminhtml/session')->getPopupData();
            Mage::getSingleton('adminhtml/session')->setPopupData(null);
        } elseif (Mage::registry('popup_data')) {
            $data = Mage::registry('popup_data')->getData();
        }
        $popupObject = new Varien_Object($data);
        $campaign_id = Mage::app()->getRequest()->getParam('id');

        //General config Popup
        $fieldset_popup = $form->addFieldset('popup_general', array(
            'legend'    =>  Mage::helper('campaign')->__('Popup Information')
        ));

        $fieldset_popup->addField('popup_status', 'select', array(
            'name'         => 'popup_status',
            'label'        => Mage::helper('campaign')->__('Status'),
            //'class'        => 'required-entry',
            'required'     => true,
            'values'       => Mage::getSingleton('campaign/status')->getOptionHash(),
            'value'        => array(2),
        ));
        //checkout show/hide fieldset
        if($popupObject->getData('popup_popup_type') != Magestore_Campaign_Model_Popup::TEMPLATE_TYPE_STATIC){
            $staticShowHide = 'hide';
        }else{
            $staticShowHide = 'show';
        }
        if($popupObject->getData('popup_popup_type') != Magestore_Campaign_Model_Popup::TEMPLATE_TYPE_FORM){
            $popFormShowHide = 'hide';
        }else{
            $popFormShowHide = 'show';
        }
        if($popupObject->getData('popup_popup_type') != Magestore_Campaign_Model_Popup_Type_Game_Halloween::TYPE_CODE){
            $popGameShowHide = 'hide';
        }else{
            $popGameShowHide = 'show';
        }

        //set default popup type add new
        if($popupObject->getData('popup_popup_type') == ''){
            $popFormShowHide = 'show';
        }

        $popup_type = $fieldset_popup->addField('popup_popup_type', 'select', array(
            'name'         => 'popup_popup_type',
            'label'        => Mage::helper('campaign')->__('Popup type'),
            //'disabled'     => false,
            //'readonly'     => true,
            'required'     => true,
            'values'        => array('static'=>'Static', 'form'=>'Form', 'game_halloween'=>'Game - Halloween'),
            //'placeholder'  => 'form',
            'onchange'  => 'change_popup_type(this.value)',
            'after_element_html' => '
                <script type="text/javascript">
                //show hide field
                var html_static_popup, html_static_popup_title, html_form_step_1, html_form_step_1_title, html_form_step_2, html_form_step_2_title;
                var popup_static_hide = function(){
                    if($(\'static_popup\')){
                        $(\'static_popup\').previous().hide();
                        $(\'static_popup\').hide();
                        var childs = $(\'static_popup\').select(\'select\', \'input\', \'textarea\');
                        childs.each(function(e){
                            $(e).disable();
                        });
                    }

                    //if($(\'static_popup\') !== null && (html_static_popup == null || html_static_popup == undefined)){
                    //    html_static_popup = $(\'static_popup\');
                    //    html_static_popup_title = $(\'static_popup\').previous();//.show();
                    //}
                    //$(\'static_popup\').previous().hide();
                    //$(\'static_popup\').remove();
                }
                var popup_static_show = function(){
                    if($(\'static_popup\')){
                        $(\'static_popup\').previous().show();
                        $(\'static_popup\').show();
                        var childs = $(\'static_popup\').select(\'select\', \'input\', \'textarea\');
                        childs.each(function(e){
                            $(e).enable();
                        });
                    }

                    //if($(\'static_popup\') == null && (html_static_popup !== null || html_static_popup !== undefined)){
                    //    html_static_popup_title.show();
                    //    html_static_popup_title.insert({after: html_static_popup});
                    //}
                    //updateContentEditor("popup_static_content"); /*update content editor textarea*/
                }
                var popup_form_hide = function(){
                    if($(\'form_step_1\')){
                        $(\'form_step_1\').previous().hide();
                        $(\'form_step_1\').hide();
                        var childs = $(\'form_step_1\').select(\'select\', \'input\', \'textarea\');
                        childs.each(function(e){
                            $(e).disable();
                        });
                    }
                    if($(\'form_step_2\')){
                        $(\'form_step_2\').previous().hide();
                        $(\'form_step_2\').hide();
                        var childs = $(\'form_step_2\').select(\'select\', \'input\', \'textarea\');
                        childs.each(function(e){
                            $(e).disable();
                        });
                    }

                    //if($(\'form_step_1\') !== null && (html_form_step_1 == null || html_form_step_1 == undefined)){
                    //    html_form_step_1 = $(\'form_step_1\');
                    //    html_form_step_1_title = $(\'form_step_1\').previous();//.show();
                    //    $(\'form_step_1\').previous().hide();
                    //    $(\'form_step_1\').remove();
                    //}
                    //if($(\'form_step_2\') !== null && (html_form_step_2 == null || html_form_step_2 == undefined)){
                    //    html_form_step_2 = $(\'form_step_2\');
                    //    html_form_step_2_title = $(\'form_step_2\').previous();//.show();
                    //    $(\'form_step_2\').previous().hide();
                    //    $(\'form_step_2\').remove();
                    //}


                }
                var popup_form_show = function(){
                    if($(\'form_step_1\')){
                        $(\'form_step_1\').previous().show();
                        $(\'form_step_1\').show();
                        var childs = $(\'form_step_1\').select(\'select\', \'input\', \'textarea\');
                        childs.each(function(e){
                            $(e).enable();
                        });
                    }
                    if($(\'form_step_2\')){
                        $(\'form_step_2\').previous().show();
                        $(\'form_step_2\').show();
                        var childs = $(\'form_step_2\').select(\'select\', \'input\', \'textarea\');
                        childs.each(function(e){
                            $(e).enable();
                        });
                    }

                    //if($(\'form_step_1\') == null && (html_form_step_1 !== null || html_form_step_1 !== undefined)){
                    //    html_form_step_1_title.show();
                    //    html_form_step_1_title.insert({after: html_form_step_1});
                    //}
                    //if($(\'form_step_2\') == null && (html_form_step_2 !== null || html_form_step_2 !== undefined)){
                    //    html_form_step_2_title.show();
                    //    html_form_step_2_title.insert({after: html_form_step_2});
                    //}
                    //updateContentEditor("popup_form_content_step_one"); /*update content editor textarea*/
                    //updateContentEditor("popup_form_content_step_two"); /*update content editor textarea*/
                }
                function popup_game_show(){
                    if($(\'halloween_game\')){
                        $(\'halloween_game\').previous().show();
                        $(\'halloween_game\').show();
                        var childs = $(\'halloween_game\').select(\'select\', \'input\', \'textarea\');
                        childs.each(function(e){
                            $(e).enable();
                        });
                    }
                }
                function popup_game_hide(){
                    if($(\'halloween_game\')){
                        $(\'halloween_game\').previous().hide();
                        $(\'halloween_game\').hide();
                        var childs = $(\'halloween_game\').select(\'select\', \'input\', \'textarea\');
                        childs.each(function(e){
                            $(e).disable();
                        });
                    }
                }
                var change_popup_type = function(value){
                    //show_hide_thanks_toggle(value);
                    if(value == \'static\'){
                        popup_static_show();
                        popup_form_hide();
                        popup_game_hide();
                        //reloadTemplateContent_Static();
                    }else if(value == \'form\'){
                        popup_static_hide();
                        popup_form_show();
                        popup_game_hide();
                        //reloadTemplateContent_Form();
                    }else if(value == \'game_halloween\'){
                        popup_static_hide();
                        popup_form_hide();
                        popup_game_show();
                        //reloadTemplateContent_Form();
                    }
                    //run callback function
                    if(typeof(afterChangePopupType) == "function"){
                        afterChangePopupType(value);
                    }
                }

                //reload content when change popup type
                function afterChangePopupType(type){
                    alert("'.Mage::helper('campaign')->__('Your Popup type has changed. You need to reload for default content?').'");
                    return;
                    if(confirm("'.Mage::helper('campaign')->__('Your Popup type has changed. Do you want to reload default content?').'")){
                        if(type == \'static\'){
                            reloadTemplateContent_Static();
                        }else if(type == \'form\'){
                            reloadTemplateContent_Form();
                        }else if(type == \'game\'){
                            resetTemplate(
                            \''.$this->getUrl('campaignadmin/adminhtml_loadtemplate/index/', array('isAjax'=>true)).'\',
                            \'popup_game_halloween_template_step_1\',
                            \'popup_game_halloween_content_step_1\',
                            \''.$campaign_id.'\',
                            \'\',
                            \'\');
                            resetTemplate(
                            \''.$this->getUrl('campaignadmin/adminhtml_loadtemplate/index/', array('isAjax'=>true)).'\',
                            \'popup_game_halloween_template_step_2\',
                            \'popup_game_halloween_content_step_2\',
                            \''.$campaign_id.'\',
                            \'\',
                            \'\');
                            resetTemplate(
                            \''.$this->getUrl('campaignadmin/adminhtml_loadtemplate/index/', array('isAjax'=>true)).'\',
                            \'popup_game_halloween_template_step_3\',
                            \'popup_game_halloween_content_step_3\',
                            \''.$campaign_id.'\',
                            \'\',
                            \'\');
                        }
                    }
                }

                var lockForm = false;
                function reloadTemplateContent_Form(){
                    lockForm = true;
                    resetContentFormOne();
                    var resetFormTwoInterval = setInterval(function(){
                        if(!lockForm){
                            resetContentFormTwo();
                            clearInterval(resetFormTwoInterval);
                        }
                    }, 100);
                }

                function resetContentFormOne(){
                    resetTemplate(\''.$this->getUrl('campaignadmin/adminhtml_loadtemplate/index/', array('isAjax'=>true)).'\',
                    \'popup_form_template_id_one\',
                    \'popup_form_content_step_one\',
                    \''.$campaign_id.'\',
                    afterResetContentFormOne,
                    \'\');
                }

                function resetContentFormTwo(){
                    resetTemplate(\''.$this->getUrl('campaignadmin/adminhtml_loadtemplate/index/', array('isAjax'=>true)).'\',
                    \'popup_form_template_id_two\',
                    \'popup_form_content_step_two\',
                    \''.$campaign_id.'\',
                    afterResetContentFormTwo,
                    \'\');
                }

                function afterResetContentFormOne(status, content){
                    lockForm = false;
                }

                function afterResetContentFormTwo(status, content){
                }

                var reloadTemplateContent_Static = function(){
                    resetPopupStaticTemplate();
                }

                //check show popup type layout
                window.onload = function(){
                    //check show/hile static
                    popup_static_'.$staticShowHide.'();
                    //check show/hile form
                    popup_form_'.$popFormShowHide.'();
                    //check show/hile game field set
                    popup_game_'.$popGameShowHide.'();
                }
</script>
            ',
        ));

        if(!isset($data['popup_is_show_coupon'])) $data['popup_is_show_coupon'] = 1;
        $is_show_coupon = $fieldset_popup->addField('popup_is_show_coupon', 'select', array(
            'name'         => 'popup_is_show_coupon',
            'label'        => Mage::helper('campaign')->__('Is show Coupon'),
            //'class'        => 'required-entry',
            'required'     => true,
            'values'       => array(array('label'=>'Yes', 'value'=> 1), array('label'=>'No', 'value'=> 0)),
            'value'        => array(1),
        ));

        if(!isset($data['popup_is_show_first_time'])) $data['popup_is_show_first_time'] = 1;
        $fieldset_popup->addField('popup_is_show_first_time', 'select', array(
            'name'         => 'popup_is_show_first_time',
            'label'        => Mage::helper('campaign')->__('Show popup'),
            //'class'        => 'required-entry',
            'required'     => false,
            'values'       => array(
                array('label'=>'First time of customer visit within 24h', 'value'=> 1),
                array('label'=>'Every time (Always popup)', 'value'=> 0)),
            'value'        => array(1),
        ));

        $fieldset_popup->addField('popup_include_page', 'text', array(
            'name'         => 'popup_include_page',
            'label'        => Mage::helper('campaign')->__('Include pages'),
            'note'  => 'use separator with ;<br/>
                <strong>*</strong> for accept all pages<br/>
                <strong>/</strong> for home page<br/>
                <strong>../abc/..</strong> for special url page<br/>
                <strong>Eg:</strong> *; /; ../checkout/..',
        ));

        $fieldset_popup->addField('popup_exclude_page', 'text', array(
            'name'         => 'popup_exclude_page',
            'label'        => Mage::helper('campaign')->__('Exclude pages'),
            'note'  => 'use separator with ;<br/>
                <strong>*</strong> for not accept all pages<br/>
                <strong>/</strong> for not accept home page<br/>
                <strong>../abc/..</strong> for not accept special url page<br/>
                <strong>Eg:</strong> *; /; ../checkout/..',
        ));

        /*----------------------------------------------*/
        /*--------Popup Type Static---------------------*/
        $static_field = $form->addFieldset('static_popup', array(
            'legend'    =>  Mage::helper('campaign')->__('Static Popup'),
            'class' => 'static_popup_field',
        ));
        //select template
//        $static_field->addField('popup_static_template_id', 'select', array(
//            'name'         => 'popup_static_template_id',
//            'label'        => Mage::helper('campaign')->__('Template'),
//            //'class'        => 'required-entry',
//            //'required'     => true,
//            'values'       => Mage::getModel('campaign/template')->getOptions(
//                Magestore_Campaign_Model_Popup_Type_Static::TEMPLATE_GROUP,
//                Magestore_Campaign_Model_Popup_Type_Static::TEMPLATE_TYPE),
//            'value'        => array(1),
//            'onchange'  => 'resetPopupStaticTemplate()'
//        ));
//        //create template overview image
//        $overview = $this->getLayout()->createBlock('campaign/adminhtml_popup_overview_template');
//        $overview->setId('popup_static_template_id')
//            ->setObject('static_overview')
//            ->setOptionImages(Mage::getModel('campaign/template')->getOverviews(
//                Magestore_Campaign_Model_Popup_Type_Static::TEMPLATE_GROUP,
//                Magestore_Campaign_Model_Popup_Type_Static::TEMPLATE_TYPE));
//        //link to reset or edit
//        //$templateId = (isset($data['popup_static_template_id'])) ? $data['popup_static_template_id']:'';
//        //if($templateId != ''){
//            $resetLink = '<div>
//                <a href="javascript:resetPopupStaticTemplate()"><span style="color: #f73e00;">Reset or reload Content</span></a>
//                </div>
//                <script>
//                function resetPopupStaticTemplate(){
//                    resetTemplate(\''.$this->getUrl('campaignadmin/adminhtml_resettemplate/index/', array('isAjax'=>true)).'\',
//                    \'popup_static_template_id\',
//                    \'popup_static_content\',
//                    \''.$campaign_id.'\');
//                }
//                </script>
//                ';
//        //}else{ $resetLink = ''; }
//        $static_field->addField('popup_static_template_overview', 'label', array(
//            'label'        => Mage::helper('campaign')->__('Overview'),
//            'value'        => Mage::helper('campaign')->__('Overview'),
//            'after_element_html'    => $overview->toHtml().'<br/>'.$resetLink,
//        ));

        //select template
        $static_field->addType('template_selector' , 'Magestore_Campaign_Block_Adminhtml_Campaign_Edit_Tab_Element_TemplateSelector');
        $static_field->addField('popup_static_template_id', 'template_selector', array(
            'name'         => 'popup_static_template_id',
            'label'        => Mage::helper('campaign')->__('Default Template'),
            'values'       => Mage::getModel('campaign/template')->getTemplateOption('popup_static'),
            'callback_func'  => 'loadPopupStaticTemplate',
            'note'  => 'Click to load default template content',
            'after_element_html' => '<script type="text/javascript">function loadPopupStaticTemplate(id){
    $("popup_static_template_id").setValue(id);
    loadTemplate("'.$this->getUrl('campaignadmin/adminhtml_loadtemplate/index/', array('isAjax'=>true)).'",
        id, "popup_static_content", "'.Mage::app()->getRequest()->getParam('id').'", "",
        "Loading and reset content. Are you sure?"
    );
}</script>'
        ));

        $configEditor = Mage::getSingleton('cms/wysiwyg_config')->getConfig();
        $configEditor->setData('extended_valid_elements', 'input[placeholder|accept|alt|checked|disabled|maxlength|name|readonly|size|src|type|value]');
        $static_field->addField('popup_static_content', 'editor', array(
            'name'      => 'popup_static_content',
            'label'     => Mage::helper('cms')->__('Content'),
            'title'     => Mage::helper('cms')->__('Content'),
            'style'     => 'width:597px; height: 270px;',
            'required'  => false,
            'config'    => $configEditor,
            'wysiwyg'    => true,
        ));

        /*---------GA Code tracking--------*/
        $static_field->addField('popup_static_ga_code_button', 'editor', array(
            'name'        => 'popup_static_ga_code_button',
            'label'        => Mage::helper('campaign')->__('GA Code Button'),
            'title'        => Mage::helper('campaign')->__('GA Code Button'),
            'style'        => 'width:400px; height:50px;',
            'wysiwyg'    => false,
            //'required'    => true,
        ));
        $static_field->addField('popup_static_ga_code_close', 'editor', array(
            'name'        => 'popup_static_ga_code_close',
            'label'        => Mage::helper('campaign')->__('GA Code Close'),
            'title'        => Mage::helper('campaign')->__('GA Code Close'),
            'style'        => 'width:400px; height:50px;',
            'wysiwyg'    => false,
            //'required'    => true,
            'after_element_html' => '<script type="text/javascript">
            //$(\'static_popup\').previous().'.$staticShowHide.'();
            //$(\'static_popup\').'.$staticShowHide.'();
</script>',
        ));

        // field dependencies
        $this->setChild('form_after', $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
            ->addFieldMap($popup_type->getHtmlId(), $popup_type->getName())
            ->addFieldMap($is_show_coupon->getHtmlId(), $is_show_coupon->getName())
            ->addFieldDependence(
                $is_show_coupon->getName(),
                $popup_type->getName(),
                'form')
        );

        /*----------------End Popup type Static---------------*/


        /*----------------------------------------------------*/
        /*--------Step 1: Popup Type Form---------------------*/
        $fieldset_form = $form->addFieldset('form_step_1', array(
            'legend'    =>  Mage::helper('campaign')->__('Step 1')
        ));
        /*$fieldset_form->addField('popup_mailchimp_list', 'select', array(
            'name'         => 'popup_mailchimp_list',
            'label'        => Mage::helper('campaign')->__('Mailchimp list'),
            'values'       => Mage::getSingleton('campaign/mailchimp')->getLists(),
        ));*/

//        $template1 = Mage::getModel('campaign/template')->getSelectOptionsGroup('popup_game','step1');
//        $fieldset_form->addField('popup_form_template_id_one', 'select', array(
//            'name'         => 'popup_form_template_id_one',
//            'label'        => Mage::helper('campaign')->__('Template'),
//            //'class'        => 'required-entry',
//            //'required'     => true,
//            'values'       => $template1,
//            'value'        => (isset($template1[0]))?$template1[0]:array(),
//            //'onchange'  => 'overview_form.show(this.value)'
//        ));

        //create overview image
//        $overview = $this->getLayout()->createBlock('campaign/adminhtml_popup_overview_template');
//        $overview->setId('popup_form_template_id_one')
//            ->setObject('overview_form')
//            ->setOptionImages(Mage::getModel('campaign/template')->getOverviews(
//                Magestore_Campaign_Model_Popup_Type_Form::TEMPLATE_GROUP,
//                Magestore_Campaign_Model_Popup_Type_Form::TEMPLATE_TYPE_STEP_1));
//        //$templateIdForm = (isset($data['popup_form_template_id_one'])) ? $data['popup_form_template_id_one']:'';
//        //if($templateIdForm != ''){
//            $resetLink = '<div><a href="javascript:resetContentFormOne()">
//                <span style="color: #f73e00;">Reset or reload Content</span></a>
//                </div>
//                <script>
//                    function resetContentFormOne(){
//                        resetTemplate(\''.$this->getUrl('campaignadmin/adminhtml_resettemplate/index/', array('isAjax'=>true)).'\',
//                        \'popup_form_template_id_one\',
//                        \'popup_form_content_step_one\',
//                        \''.$campaign_id.'\',
//                        afterResetContentFormOne,
//                        \'\');
//                    }
//                </script>
//                ';
//        //}else{
//        //    $resetLink = '';
//        //}
//
//        $fieldset_form->addField('show_overview_template_form', 'label', array(
//            'label'        => Mage::helper('campaign')->__('Overview'),
//            'value'        => Mage::helper('campaign')->__('Overview'),
//            'after_element_html'    => $overview->toHtml().'<br/>'.$resetLink,
//        ));

        //select template
        $fieldset_form->addType('template_selector' , 'Magestore_Campaign_Block_Adminhtml_Campaign_Edit_Tab_Element_TemplateSelector');
        $fieldset_form->addField('popup_form_template_id_one', 'template_selector', array(
            'name'         => 'popup_form_template_id_one',
            'label'        => Mage::helper('campaign')->__('Default Template'),
            'values'       => Mage::getModel('campaign/template')->getTemplateOption('popup_form_step1'),
            'callback_func'  => 'loadPopupFormTemplate',
            'note'  => 'Click to load default template content',
            'after_element_html' => '<script type="text/javascript">function loadPopupFormTemplate(id){
    $("popup_form_template_id_one").setValue(id);
    loadTemplate("'.$this->getUrl('campaignadmin/adminhtml_loadtemplate/index/', array('isAjax'=>true)).'",
        id, "popup_form_content_step_one", "'.Mage::app()->getRequest()->getParam('id').'", "",
        "Loading and reset content. Are you sure?"
    );
}</script>'
        ));

        //if($templateIdForm != ''){
        $configEditor = Mage::getSingleton('cms/wysiwyg_config')->getConfig();
        $configEditor->setData('extended_valid_elements', 'input[placeholder|accept|alt|checked|disabled|maxlength|name|readonly|size|src|type|value]');
        $fieldset_form->addField('popup_form_content_step_one', 'editor', array(
            'name'      => 'popup_form_content_step_one',
            'label'     => Mage::helper('cms')->__('Content'),
            'title'     => Mage::helper('cms')->__('Content'),
            'style'     => 'width:597px; height: 270px;',
            'required'  => false,
            'config'    => $configEditor,
            'wysiwyg'    => true,
        ));
        //}


        /*--------Step 2: Popup Type Form---------------------*/
        $fieldset_thanks = $form->addFieldset('form_step_2', array(
            'legend'    =>  Mage::helper('campaign')->__('Step 2')
        ));



//        $fieldset_thanks->addField('popup_form_template_id_two', 'select', array(
//            'name'         => 'popup_form_template_id_two',
//            'label'        => Mage::helper('campaign')->__('Template'),
//            //'class'        => 'required-entry',
//            'required'     => true,
//            'values'       => Mage::getModel('campaign/template')->getOptions(
//                Magestore_Campaign_Model_Popup_Type_Form::TEMPLATE_GROUP,
//                Magestore_Campaign_Model_Popup_Type_Form::TEMPLATE_TYPE_STEP_2),
//            'value'        => array(1),
//        ));
//
//        //create overview template success popup
//        $overview_thanks = $this->getLayout()->createBlock('campaign/adminhtml_popup_overview_template');
//        $overview_thanks->setId('popup_form_template_id_two')
//            ->setObject('overview_pop_success')
//            ->setOptionImages(Mage::getModel('campaign/template')->getOverviews(
//                Magestore_Campaign_Model_Popup_Type_Form::TEMPLATE_GROUP,
//                Magestore_Campaign_Model_Popup_Type_Form::TEMPLATE_TYPE_STEP_2));
//        //$templateIdThanks = (isset($data['popup_form_template_id_two'])) ? $data['popup_form_template_id_two']:'';
//        //if($templateIdThanks != ''){
//            $resetLink = '<div>
//                <a href="javascript:resetContentFormTwo()"><span style="color: #f73e00;">Reset or reload Content</span></a>
//                </div>
//                <script>
//                function resetContentFormTwo(){
//                    resetTemplate(\''.$this->getUrl('campaignadmin/adminhtml_resettemplate/index/', array('isAjax'=>true)).'\',
//                    \'popup_form_template_id_two\',
//                    \'popup_form_content_step_two\',
//                    \''.$campaign_id.'\',
//                    afterResetContentFormTwo,
//                    \'\');
//                }
//                </script>
//                ';
//        //}else{
//        //    $resetLink = '';
//        //}
//        $fieldset_thanks->addField('show_overview_template_success', 'label', array(
//            'label'        => Mage::helper('campaign')->__('Overview'),
//            'value'        => Mage::helper('campaign')->__('Overview'),
//            'after_element_html'    => $overview_thanks->toHtml().'<br/>'.$resetLink,
//        ));

        //select template
        $fieldset_thanks->addType('template_selector' , 'Magestore_Campaign_Block_Adminhtml_Campaign_Edit_Tab_Element_TemplateSelector');
        $fieldset_thanks->addField('popup_form_template_id_two', 'template_selector', array(
            'name'         => 'popup_form_template_id_two',
            'label'        => Mage::helper('campaign')->__('Default Template'),
            'values'       => Mage::getModel('campaign/template')->getTemplateOption('popup_form_step2'),
            'callback_func'  => 'loadPopupFormStep2Template',
            'note'  => 'Click to load default template content',
            'after_element_html' => '<script type="text/javascript">function loadPopupFormStep2Template(id){
    $("popup_form_template_id_two").setValue(id);
    loadTemplate("'.$this->getUrl('campaignadmin/adminhtml_loadtemplate/index/', array('isAjax'=>true)).'",
        id, "popup_form_content_step_two", "'.Mage::app()->getRequest()->getParam('id').'", "",
        "Loading and reset content. Are you sure?"
    );
}</script>'
        ));

        $fieldset_thanks->addField('popup_form_content_step_two', 'editor', array(
            'name'      => 'popup_form_content_step_two',
            'label'     => Mage::helper('cms')->__('Content'),
            'title'     => Mage::helper('cms')->__('Content'),
            'style'     => 'width:597px; height: 270px;',
            'required'  => false,
            'config'    => Mage::getSingleton('cms/wysiwyg_config')->getConfig(),
            'wysiwyg'    => true,
        ));




        /*-------------------------------------------*/
        /*--------Game Halloween---------------------*/
        $halloween = $form->addFieldset('halloween_game', array(
            'legend'    =>  Mage::helper('campaign')->__('Halloween Game'),
            'class' => 'halloween_game',
        ));


//        //select template for step 1
//        $halloween->addField('popup_game_halloween_template_step_1', 'select', array(
//            'name'         => 'popup_game_halloween_template_step_1',
//            'label'        => Mage::helper('campaign')->__('Template Step 1'),
//            'values'       => Mage::getModel('campaign/template')->getSelectOptionGroup('popup_game', 'step1'),
//            'value'        => array(1),
//            'onchange'  => 'resetTemplate(
//                \''.$this->getUrl('campaignadmin/adminhtml_resettemplate/index/', array('isAjax'=>true)).'\',
//                \'popup_game_halloween_template_step_1\',
//                \'popup_game_halloween_content_step_1\',
//                \''.$campaign_id.'\',
//                \'\',
//                \'Loading and reset content for Popup Game Halloween Step 1. Are you sure?\')'
//        ));
//        //create template overview image
//        $overview = $this->getLayout()->createBlock('campaign/adminhtml_popup_overview_template');
//        $overview->setId('popup_game_halloween_template_step_1')
//            ->setObject('game_halloween_overview')
//            ->setOptionImages(Mage::getModel('campaign/template')->getOverviews(
//                Magestore_Campaign_Model_Popup_Type_Game_Halloween::TEMPLATE_GROUP,
//                Magestore_Campaign_Model_Popup_Type_Game_Halloween::TEMPLATE_TYPE_STEP_1));
//        //link to reset or edit
//        $resetLink = '<div>
//                <a href="javascript:reloadTemplate(\''.$this->getUrl('campaignadmin/adminhtml_resettemplate/index/', array('isAjax'=>true)).'\',
//                this.value,
//                \'popup_game_halloween_content_step_1\',
//                \''.$campaign_id.'\',
//                \'\',
//                \'Loading and reset content for Popup Game Halloween Step 1. Are you sure?\')">
//                    <span style="color: #f73e00;">Reset or reload Content</span></a>
//                </div>';
//        $halloween->addField('game_halloween_step_1_overview', 'label', array(
//            'label'        => Mage::helper('campaign')->__('Overview'),
//            'value'        => Mage::helper('campaign')->__('Overview'),
//            'after_element_html'    => $overview->toHtml().'<br/>'.$resetLink,
//        ));

        //select template
        $halloween->addType('template_selector' , 'Magestore_Campaign_Block_Adminhtml_Campaign_Edit_Tab_Element_TemplateSelector');
        $halloween->addField('popup_game_halloween_template_step_1', 'template_selector', array(
            'name'         => 'popup_game_halloween_template_step_1',
            'label'        => Mage::helper('campaign')->__('Default Template'),
            'values'       => Mage::getModel('campaign/template')->getTemplateOption('popup_game_step1'),
            'callback_func'  => 'loadPopupGameStep1Template',
            'note'  => 'Click to load default template content',
            'after_element_html' => '<script type="text/javascript">function loadPopupGameStep1Template(id){
    $("popup_game_halloween_template_step_1").setValue(id);
    loadTemplate("'.$this->getUrl('campaignadmin/adminhtml_loadtemplate/index/', array('isAjax'=>true)).'",
        id, "popup_game_halloween_content_step_1", "'.Mage::app()->getRequest()->getParam('id').'", "",
        "Loading and reset content step 1. Are you sure?"
    );
}</script>'
        ));

        $configEditor = Mage::getSingleton('cms/wysiwyg_config')->getConfig();
        $configEditor->setData('extended_valid_elements', 'input[placeholder|accept|alt|checked|disabled|maxlength|name|readonly|size|src|type|value]');
        $halloween->addField('popup_game_halloween_content_step_1', 'editor', array(
            'name'      => 'popup_game_halloween_content_step_1',
            'label'     => Mage::helper('cms')->__('Content Step 1'),
            'title'     => Mage::helper('cms')->__('Content Step 1'),
            'style'     => 'width:597px; height: 270px;',
            'required'  => false,
            'config'    => $configEditor,
            'wysiwyg'    => true,
        ));

//        //select template for step 2
//        $halloween->addField('popup_game_halloween_template_step_2', 'select', array(
//            'name'         => 'popup_game_halloween_template_step_2',
//            'label'        => Mage::helper('campaign')->__('Template Step 2'),
//            'values'       => Mage::getModel('campaign/template')->getSelectOptionGroup('popup_game', 'step2'),
//            'value'        => array(1),
//            'onchange'  => 'loadTemplate(
//                \''.$this->getUrl('campaignadmin/adminhtml_resettemplate/index/', array('isAjax'=>true)).'\',
//                this.value,
//                \'popup_game_halloween_content_step_2\',
//                \''.$campaign_id.'\',
//                \'\',
//                \'Loading and reset content for Popup Game Halloween Step 2. Are you sure?\')'
//        ));
//        //create template overview image
//        $overview = $this->getLayout()->createBlock('campaign/adminhtml_popup_overview_template');
//        $overview->setId('popup_game_halloween_template_step_2')
//            ->setObject('game_halloween_step_2_overview')
//            ->setOptionImages(Mage::getModel('campaign/template')->getOverviews(
//                Magestore_Campaign_Model_Popup_Type_Game_Halloween::TEMPLATE_GROUP,
//                Magestore_Campaign_Model_Popup_Type_Game_Halloween::TEMPLATE_TYPE_STEP_2));
//        //link to reset or edit
//        $resetLink = '<div>
//                <a href="javascript:resetTemplate(\''.$this->getUrl('campaignadmin/adminhtml_resettemplate/index/', array('isAjax'=>true)).'\',
//                \'popup_game_halloween_template_step_2\',
//                \'popup_game_halloween_content_step_2\',
//                \''.$campaign_id.'\',
//                \'\',
//                \'Loading and reset content for Popup Game Halloween Step 2. Are you sure?\')">
//                    <span style="color: #f73e00;">Reset or reload Content</span></a>
//                </div>';
//        $halloween->addField('game_halloween_step_2_overview', 'label', array(
//            'label'        => Mage::helper('campaign')->__('Overview'),
//            'value'        => Mage::helper('campaign')->__('Overview'),
//            'after_element_html'    => $overview->toHtml().'<br/>'.$resetLink,
//        ));

        $halloween->addField('break_line', 'label', array(
            'label'     => $this->__(''),
            'title'     => $this->__(''),
            'after_element_html'  => '<div class="break-line-bottom-2"></div>'
        ));

        //select template
        $halloween->addType('template_selector' , 'Magestore_Campaign_Block_Adminhtml_Campaign_Edit_Tab_Element_TemplateSelector');
        $halloween->addField('popup_game_halloween_template_step_2', 'template_selector', array(
            'name'         => 'popup_game_halloween_template_step_2',
            'label'        => Mage::helper('campaign')->__('Default Template'),
            'values'       => Mage::getModel('campaign/template')->getTemplateOption('popup_game_step2'),
            'callback_func'  => 'loadPopupGameStep2Template',
            'note'  => 'Click to load default template content',
            'after_element_html' => '<script type="text/javascript">function loadPopupGameStep2Template(id){
    $("popup_game_halloween_template_step_2").setValue(id);
    loadTemplate("'.$this->getUrl('campaignadmin/adminhtml_loadtemplate/index/', array('isAjax'=>true)).'",
        id, "popup_game_halloween_content_step_2", "'.Mage::app()->getRequest()->getParam('id').'", "",
        "Loading and reset content step 2. Are you sure?"
    );
}</script>'
        ));

        $configEditor = Mage::getSingleton('cms/wysiwyg_config')->getConfig();
        $configEditor->setData('extended_valid_elements', 'input[placeholder|accept|alt|checked|disabled|maxlength|name|readonly|size|src|type|value]');
        $halloween->addField('popup_game_halloween_content_step_2', 'editor', array(
            'name'      => 'popup_game_halloween_content_step_2',
            'label'     => Mage::helper('cms')->__('Content Step 2'),
            'title'     => Mage::helper('cms')->__('Content Step 2'),
            'style'     => 'width:597px; height: 270px;',
            'required'  => false,
            'config'    => $configEditor,
            'wysiwyg'    => true,
        ));

//        //select template for step 3
//        $halloween->addField('popup_game_halloween_template_step_3', 'select', array(
//            'name'         => 'popup_game_halloween_template_step_3',
//            'label'        => Mage::helper('campaign')->__('Template Step 3'),
//            'values'       => Mage::getModel('campaign/template')->getSelectOptionGroup('popup_game', 'step3'),
//            'value'        => array(1),
//            'onchange'  => 'resetTemplate(
//                \''.$this->getUrl('campaignadmin/adminhtml_resettemplate/index/', array('isAjax'=>true)).'\',
//                \'popup_game_halloween_template_step_3\',
//                \'popup_game_halloween_content_step_3\',
//                \''.$campaign_id.'\',
//                \'\',
//                \'Loading and reset content for Popup Game Halloween Step 3. Are you sure?\')'
//        ));
//        //create template overview image
//        $overview = $this->getLayout()->createBlock('campaign/adminhtml_popup_overview_template');
//        $overview->setId('popup_game_halloween_template_step_3')
//            ->setObject('game_halloween_step_2_overview')
//            ->setOptionImages(Mage::getModel('campaign/template')->getOverviews(
//                Magestore_Campaign_Model_Popup_Type_Game_Halloween::TEMPLATE_GROUP,
//                Magestore_Campaign_Model_Popup_Type_Game_Halloween::TEMPLATE_TYPE_STEP_3));
//        //link to reset or edit
//        $resetLink = '<div>
//                <a href="javascript:resetTemplate(\''.$this->getUrl('campaignadmin/adminhtml_resettemplate/index/', array('isAjax'=>true)).'\',
//                \'popup_game_halloween_template_step_3\',
//                \'popup_game_halloween_content_step_3\',
//                \''.$campaign_id.'\',
//                \'\',
//                \'Loading and reset content for Popup Game Halloween Step 3. Are you sure?\')">
//                    <span style="color: #f73e00;">Reset or reload Content</span></a>
//                </div>';
//        $halloween->addField('game_halloween_step_3_overview', 'label', array(
//            'label'        => Mage::helper('campaign')->__('Overview'),
//            'value'        => Mage::helper('campaign')->__('Overview'),
//            'after_element_html'    => $overview->toHtml().'<br/>'.$resetLink,
//        ));

        $halloween->addField('break_line_3', 'label', array(
            'label'     => $this->__(''),
            'title'     => $this->__(''),
            'after_element_html'  => '<div class="break-line-bottom-2"></div>'
        ));

        //select template
        $halloween->addType('template_selector' , 'Magestore_Campaign_Block_Adminhtml_Campaign_Edit_Tab_Element_TemplateSelector');
        $halloween->addField('popup_game_halloween_template_step_3', 'template_selector', array(
            'name'         => 'popup_game_halloween_template_step_3',
            'label'        => Mage::helper('campaign')->__('Default Template'),
            'values'       => Mage::getModel('campaign/template')->getTemplateOption('popup_game_step3'),
            'callback_func'  => 'loadPopupGameStep3Template',
            'note'  => 'Click to load default template content',
            'after_element_html' => '<script type="text/javascript">function loadPopupGameStep3Template(id){
    $("popup_game_halloween_template_step_3").setValue(id);
    loadTemplate("'.$this->getUrl('campaignadmin/adminhtml_loadtemplate/index/', array('isAjax'=>true)).'",
        id, "popup_game_halloween_content_step_3", "'.Mage::app()->getRequest()->getParam('id').'", "",
        "Loading and reset content step 2. Are you sure?"
    );
}</script>'
        ));

        $configEditor = Mage::getSingleton('cms/wysiwyg_config')->getConfig();
        $configEditor->setData('extended_valid_elements', 'input[placeholder|accept|alt|checked|disabled|maxlength|name|readonly|size|src|type|value]');
        $halloween->addField('popup_game_halloween_content_step_3', 'editor', array(
            'name'      => 'popup_game_halloween_content_step_3',
            'label'     => Mage::helper('cms')->__('Content Step 3'),
            'title'     => Mage::helper('cms')->__('Content Step 3'),
            'style'     => 'width:597px; height: 270px;',
            'required'  => false,
            'config'    => $configEditor,
            'wysiwyg'    => true,
        ));

        $form->setValues($data);
        return parent::_prepareForm();
    }
}