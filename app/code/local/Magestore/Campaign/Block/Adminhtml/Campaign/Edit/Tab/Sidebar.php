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
class Magestore_Campaign_Block_Adminhtml_Campaign_Edit_Tab_Sidebar extends Mage_Adminhtml_Block_Widget_Form
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
            $data = Mage::getSingleton('adminhtml/session')->getSidebarData();
            Mage::getSingleton('adminhtml/session')->setSidebarData(null);
        } elseif (Mage::registry('sidebar_data')) {
            $data = Mage::registry('sidebar_data')->getData();
        }
        $fieldset = $form->addFieldset('sidebar_form', array(
            'legend'=>Mage::helper('campaign')->__('Sidebar Information')
        ));

        $fieldset->addField('sidebar_status', 'select', array(
            'name'		=> 'sidebar_status',
            'label'		=> Mage::helper('campaign')->__('Status:'),
            'required'  => false,
            'values'    => Mage::getSingleton('campaign/status')->getOptionHash(),
        ));


        //zend_debug::dump(Mage::getConfig()->getNode('adminhtml/Magestore_Campaign/templates/popup')->asArray());die;
        //zend_debug::dump(Mage::getModel('campaign/template')->getTemplateOption('popup_form_step1'));die;

        //select template
        $fieldset->addType('template_selector' , 'Magestore_Campaign_Block_Adminhtml_Campaign_Edit_Tab_Element_TemplateSelector');
        $template = $fieldset->addField('sidebar_template_id', 'template_selector', array(
            'name'         => 'sidebar_template_id',
            'label'        => Mage::helper('campaign')->__('Default Template'),
            'values'       => Mage::getModel('campaign/template')->getTemplateOption('sidebar'),
            'callback_func'  => 'loadSidebarTemplate',
            'note'  => 'Click to load default template content',
            'after_element_html' => '<script type="text/javascript">function loadSidebarTemplate(id){
    $("sidebar_template_id").setValue(id);
    loadTemplate("'.$this->getUrl('campaignadmin/adminhtml_loadtemplate/index/', array('isAjax'=>true)).'",
        id, "sidebar_content", "'.Mage::app()->getRequest()->getParam('id').'", "",
        "Loading and reset content. Are you sure?"
    );
}</script>'
        ));


//        //select template
//        $template = $fieldset->addField('sidebar_template_id', 'select', array(
//            'name'         => 'sidebar_template_id',
//            'label'        => Mage::helper('campaign')->__('Template'),
//            'class'        => 'required-entry',
//            'required'     => true,
//            'values'       => Mage::getModel('campaign/template')->getOptions(Magestore_Campaign_Model_Sidebar::TEMPLATE_GROUP),
//            'value'        => array(1),
//            'onchange'  => 'resetSidebarTemplate()'
//        ));
//        //set hiden current template saved
//        $fieldset->addField('sidebar_cms_block', 'hidden', array(
//            'name'      => 'sidebar_cms_block',
//            'value'     => (isset($data['sidebar_cms_block'])) ? $data['sidebar_cms_block']:'',
//            'title'     => (isset($data['sidebar_cms_identifier'])) ? $data['sidebar_cms_identifier']:'',
//        ));
//        //create template overview image
//        $overview = $this->getLayout()->createBlock('campaign/adminhtml_overview_template');
//        $overview->setId('sidebar_template_id')
//            ->setObject('sidebar_overview')
//            ->setOptionImages(Mage::getModel('campaign/template')->getOverviews(
//                Magestore_Campaign_Model_Sidebar::TEMPLATE_GROUP));
//        //link to reset or edit
//        //$cmsBlockId = (isset($data['sidebar_template_id'])) ? $data['sidebar_template_id']:'';
//        $campaign_id = Mage::app()->getRequest()->getParam('id');
//        //if($cmsBlockId != ''){
//            $resetLink = '<div>
//                <a href="javascript: resetSidebarTemplate()"><span style="color: #f73e00;">Reset / Reload Content</span></a>
//                </div>
//                <script>
//                function resetSidebarTemplate(){
//                    resetTemplate(\''.$this->getUrl('campaignadmin/adminhtml_resettemplate/index/', array('isAjax'=>true)).'\',
//                    \'sidebar_template_id\',
//                    \'sidebar_content\',
//                    \''.$campaign_id.'\',
//                    \'\',
//                    \''.Mage::helper('campaign')->__('Your template type has changed. Do you want to reload default content of it?').'\');
//                }
//                </script>
//                ';
//        //}else{
//        //    $resetLink = '';
//        //}
//        $fieldset->addField('sidebar_overview', 'label', array(
//            'label'        => Mage::helper('campaign')->__('Overview'),
//            'value'        => Mage::helper('campaign')->__('Overview'),
//            'after_element_html'    => $overview->toHtml().'<br/>'.$resetLink,
//        ));

        //if($cmsBlockId != '') {
            $fieldset->addField('sidebar_content', 'editor', array(
                'name' => 'sidebar_content',
                'label' => Mage::helper('cms')->__('Content'),
                'title' => Mage::helper('cms')->__('Content'),
                'style' => 'width:597px; height: 270px;',
                'required' => false,
                'config' => Mage::getSingleton('cms/wysiwyg_config')->getConfig(),
                'wysiwyg' => true,
                'after_element_html' => '<script>
                    function hideEditorContent(){
                        $(\'sidebar_content\').up().up().remove();
                    }
                    function updateEditorContent(content){
                        // Sets the content of a specific editor (my_editor in this example)
                        if(typeof(tinyMCE.get(\'sidebar_content\')) !== \'undefined\'){
                            tinyMCE.get(\'sidebar_content\').setContent(content);
                        }else{
                            $(\'sidebar_content\').setValue(content);
                        }
                    }
</script>',
            ));
        //}

        $urlPage = $fieldset->addField('sidebar_url', 'editor', array(
            'name'        => 'sidebar_url',
            'label'        => Mage::helper('campaign')->__('Url (\'landing page\')'),
            //'required'    => true,
            'style'        => 'width:274px; height:20px;',
        ));

        $fieldset->addField('sidebar_include_page', 'text', array(
            'name'         => 'sidebar_include_page',
            'label'        => Mage::helper('campaign')->__('Include pages'),
            'note'  => 'Eg: *; /; */inventory/*',
        ));

        $fieldset->addField('sidebar_exclude_page', 'text', array(
            'name'         => 'sidebar_exclude_page',
            'label'        => Mage::helper('campaign')->__('Exclude pages'),
            'note'  => 'Eg: *; /; */checkout/*',
        ));

        // field dependencies
        $options = Mage::getModel('campaign/template')->getOptions(
            Magestore_Campaign_Model_Sidebar::TEMPLATE_GROUP,
            Magestore_Campaign_Model_Sidebar::TEMPLATE_TYPE_LINK);
        $blockCond = $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
            ->addFieldMap($template->getHtmlId(), $template->getName())
            ->addFieldMap($urlPage->getHtmlId(), $urlPage->getName());
        $this->setChild('form_after', $blockCond);
        foreach($options as $opt){
            $blockCond->addFieldDependence($urlPage->getName(), $template->getName(),
                $opt['value']);
        }


        $form->setValues($data);
        return parent::_prepareForm();
    }
}