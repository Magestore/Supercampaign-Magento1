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
 * @category 	Magestore
 * @package 	Magestore_Bannerslider
 * @copyright 	Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license 	http://www.magestore.com/license-agreement.html
 */

/**
 * Bannerslider Edit Form Content Tab Block
 * 
 * @category 	Magestore
 * @package 	Magestore_Bannerslider
 * @author  	Magestore Developer
 */
class Magestore_Campaign_Block_Adminhtml_Bannerslider_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    /**
     * prepare tab form's information
     *
     * @return Magestore_Bannerslider_Block_Adminhtml_Bannerslider_Edit_Tab_Form
     */
    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        if (Mage::getSingleton('adminhtml/session')->getBannersliderData()) {
            $data = Mage::getSingleton('adminhtml/session')->getBannersliderData();
            Mage::getSingleton('adminhtml/session')->setBannersliderData(null);
        } elseif (Mage::registry('bannerslider_data'))
            $data = Mage::registry('bannerslider_data')->getData();

        //zend_debug::dump($data);die();
        $fieldset = $form->addFieldset('bannerslider_form', array('legend' => Mage::helper('campaign')->__('Slider information')));
        
        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig();
        $wysiwygConfig->addData(array(
            'add_variables'				=> false,
            'plugins'					=> array(),
            'widget_window_url'			=> Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/widget/index'),
            'directives_url'			=> Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg/directive'),
            'directives_url_quoted'		=> preg_quote(Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg/directive')),
            'files_browser_window_url'	=> Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg_images/index'),
        ));        

        $fieldset->addField('title', 'text', array(
            'label' => Mage::helper('campaign')->__('Title'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'title',
        ));

        $fieldset->addField('show_title', 'select', array(
            'label' => Mage::helper('campaign')->__('Show Title'),
            'name' => 'show_title',
            'values' => array(
                array(
                    'value' => 0,
                    'label' => Mage::helper('campaign')->__('Enabled'),
                ),
                array(
                    'value' => 1,
                    'label' => Mage::helper('campaign')->__('Disabled'),
                ),
            ),
        ));

        /*$fieldset->addField('campaign_id', 'select', array(
            'label' => Mage::helper('campaign')->__('Campaign'),
            'name' => 'campaign_id',
            'values' => Mage::getSingleton('campaign/campaign')->getCampaignOption(),
        ));*/

        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('campaign')->__('Status'),
            'name' => 'status',
            'values' => Mage::getSingleton('campaign/sliderstatus')->getOptionHash(),
        ));
		
		$fieldset->addField('style_content', 'hidden', array(
            'label' => Mage::helper('campaign')->__('Select available Slider Styles'),
            'name' => 'style_content',           
            'values' => 0,
            'onchange'	=> 'onchangeStyleShow()',
        ));

        $fieldset->addField('custom_code', 'editor', array(
            'name' => 'custom_code',
            'label' => Mage::helper('campaign')->__('Custom slider'),
            'title' => Mage::helper('campaign')->__('Custom slider'),
            'style' => 'width:700px; height:150px;',
            'wysiwyg'	=> true,
            'required'	=> false,
            'config'	=> $wysiwygConfig,    
        ));

        $fieldset->addField('style_slide', 'select', array(
            'label' => Mage::helper('campaign')->__('Select Slider Mode'),
            'name' => 'style_slide',
            'values' => Mage::helper('campaign')->getStyleSlider(),
            'note'   => '<a href="javasrcipt:void(0)" target="_blank" id="style-slide-view"></a>',
            'onchange'	=> 'onchangeStyleSlider(0)',
        ));
        
        $fieldset->addField('sort_type', 'select', array(
            'label' => Mage::helper('campaign')->__('Sort type'),
            'name' => 'sort_type',
            'values' => array(
                array(
                    'value' => 0,
                    'label' => Mage::helper('campaign')->__('Random'),
                ),
                array(
                    'value' => 1,
                    'label' => Mage::helper('campaign')->__('Orderly'),
                ),
            ),
        ));
		
        $fieldset->addField('note_color', 'select', array(
            'name' => 'note_color',
            'label' => $this->__('Color'),
            'title' => $this->__('Color'),            
            'values' => Mage::helper('campaign')->getOptionColor(),
        ));
		
        $fieldset->addField('width', 'text', array(
            'label' => Mage::helper('campaign')->__('Width'),
            'name' => 'width',           
        ));
        
        $fieldset->addField('height', 'text', array(
            'label' => Mage::helper('campaign')->__('Height'),
            'name' => 'height',           
        ));
                
        $fieldset->addField('animationB', 'select', array(
            'label' => Mage::helper('campaign')->__('Animation Effect'),
            'name' => 'animationB',
            'values' => Mage::helper('campaign')->getAnimationB(),
        ));  
        if(isset ($data['animationB'])){
		$data['animationA']	= $data['animationB'];
        }	
        $fieldset->addField('animationA', 'select', array(
            'label' => Mage::helper('campaign')->__('Animation Effect'),
            'name' => 'animationA',
            'values' => Mage::helper('campaign')->getAnimationA(),
        ));

        $slider_id = $this->getRequest()->getParam('id');
        if($slider_id){
            $fieldset->addField('sliderid', 'hidden', array(
                'label' => Mage::helper('campaign')->__('Alt Text'),
                'name' => 'sliderid',
            ));
            $data['sliderid'] = $slider_id;
        }

        $fieldset->addField('slider_speed', 'text', array(
            'label' => Mage::helper('campaign')->__('Speed'),
            'name' => 'slider_speed',
            'note' => 'Seconds number. This is the display time of a banner',
        ));

        $data['url_view'] = Mage::helper('campaign')->getPreviewSlider();
        $fieldset->addField('url_view', 'text', array(
            'label' => Mage::helper('campaign')->__('Max Item'),
            'name' => 'url_view',           
        ));

        $fieldset->addField('position', 'select', array(
            'name' => 'position',
            'label' => $this->__('Position'),
            'title' => $this->__('Position'),            
            'values' => Mage::helper('campaign')->getBlockIdsToOptionsArray(),
            'onchange'	=> 'onchangeposition()',
            'note' => '<a href="javasrcipt:void(0)" id="position-tip-1" style="display: none">Preview Position</a>
                        <a href="javasrcipt:void(0)" id="position-tip-2" style="display: none">Preview Position</a>
                        <a href="javasrcipt:void(0)" id="position-tip-3" style="display: block">Preview Position</a>',
        ));
		
		$fieldset->addField('position_note', 'select', array(
            'name' => 'position_note',
            'label' => $this->__('Position'),
            'title' => $this->__('Position'),            
            'values' => Mage::helper('campaign')->getPositionNote(),
			'note'	=> 'is position will be shown on all pages'
        ));
            
        $categoryIds = implode(", ", Mage::getResourceModel('catalog/category_collection')->addFieldToFilter('level', array('gt' => 0))->getAllIds());
//        if(!isset($data['category_ids'])){
//            $data['category_ids'] = $categoryIds;
//        }
        $fieldset->addField('category_ids', 'text', array(
            'label' => Mage::helper('campaign')->__('Categories'),
            'name' => 'category_ids',
            'after_element_html' => '<a id="category_link" href="javascript:void(0)" onclick="toggleMainCategories()"><img src="' . $this->getSkinUrl('images/rule_chooser_trigger.gif') . '" alt="" class="v-middle rule-chooser-trigger" title="Select Categories"></a>
                <div  id="categories_check" style="display:none">
                    <a href="javascript:toggleMainCategories(1)">Check All</a> / <a href="javascript:toggleMainCategories(2)">Uncheck All</a>
                </div>
                <div id="main_categories_select" style="display:none"></div>
                    <script type="text/javascript">
                    function toggleMainCategories(check){
                        var cate = $("main_categories_select");
                        if($("main_categories_select").style.display == "none" || (check ==1) || (check == 2)){
                            $("categories_check").style.display ="";
                            var url = "' . $this->getUrl('*/bannerslider_bannerslider/chooserMainCategories') . '";
                            if(check == 1){
                                $("category_ids").value = "'.$categoryIds.'";
                            }else if(check == 2){
                                $("category_ids").value = "";
                            }
                            var params = $("category_ids").value.split(", ");
                            var parameters = {"form_key": FORM_KEY,"selected[]":params };
                            var request = new Ajax.Request(url,
                                {
                                    evalScripts: true,
                                    parameters: parameters,
                                    onComplete:function(transport){
                                        $("main_categories_select").update(transport.responseText);
                                        $("main_categories_select").style.display = "block"; 
                                    }
                                });
                        if(cate.style.display == "none"){
                            cate.style.display = "";
                        }else{
                            cate.style.display = "none";
                        } 
                    }else{
                        cate.style.display = "none";
                        $("categories_check").style.display ="none";
                    }
                };
		</script>
            '
        ));       

        $fieldset->addField('description', 'editor', array(
            'name' => 'description',
            'label' => Mage::helper('campaign')->__('Note\'s content'),
            'title' => Mage::helper('campaign')->__('Note\'s content'),
            'style' => 'width:700px; height:150px;',
            'wysiwyg'	=> true,
            'required'	=> false,
            'config'	=> $wysiwygConfig,    
        ));
        
        
        $fieldset->addField('caption', 'select', array(
            'label' => Mage::helper('campaign')->__('Caption'),
            'name' => 'caption',
            'values' => Mage::helper('campaign')->getOptionYesNo(),
            'after_element_html' => '<script type="text/javascript">                                            
                        function onchangeStyleShow(){
                            $(\'url_view\').parentNode.parentNode.hide();   
                            $(\'caption\').parentNode.parentNode.hide();   
							$(\'description\').parentNode.parentNode.hide();
                            var cc = $(\'style_content\').value;
                            if(cc == 0){
                                $(\'style_slide\').parentNode.parentNode.show();                                                                                           
                                $(\'custom_code\').parentNode.parentNode.hide();
                                 onchangeStyleSlider(0);
                            }
                            else if(cc == 1) {                               
                                $(\'custom_code\').parentNode.parentNode.show();                                                           
                                $(\'style_slide\').parentNode.parentNode.hide();                                                                                           
                                onchangeStyleSlider(100);
								$(\'position\').parentNode.parentNode.show();
                            }                        
                        }
                        
                        function onchangeStyleSlider(itcheck){
                            if(itcheck){
                                var check = 100;
                            }else{
                                var check = parseInt($(\'style_slide\').value);
                            }                            
							$(\'style-slide-view\').show();
                            var url = $(\'url_view\').value;							
                            $(\'description\').parentNode.parentNode.hide();
							$(\'note_color\').parentNode.parentNode.hide();
                            switch (check){
                                case 1:                                    
                                    activesomeField14();	
									$(\'note_style_slide\').show();									
                                    url += "id/1";
                                    $(\'style-slide-view\').writeAttribute(\'href\', url);
                                    break;
                                case 2:                                     
                                    activesomeField14();                                                              
                                    $(\'note_style_slide\').show();
                                    url += "id/2";
                                    $(\'style-slide-view\').writeAttribute(\'href\', url);
                                    break;
                                case 3: 
                                    activesomeField14();           
                                    $(\'note_style_slide\').show();
                                    url += "id/3";
                                    $(\'style-slide-view\').writeAttribute(\'href\', url);
                                    break;
                                case 4: 
                                    activesomeField14();         
                                    $(\'note_style_slide\').show();
                                    url += "id/4";
                                    $(\'style-slide-view\').writeAttribute(\'href\', url);
                                    break;
                                case 5: 
                                    activesomeField56();               
                                    $(\'sort_type\').parentNode.parentNode.hide();
                                    $(\'style-slide-view\').hide();
                                    $(\'width\').parentNode.parentNode.show();                                                           
                                    $(\'height\').parentNode.parentNode.show(); 
                                    break;                               
				case 6: 
                                    activesomeField56();
                                    $(\'note_style_slide\').show();
                                    $(\'note_color\').parentNode.parentNode.show();
                                    $(\'slider_speed\').parentNode.parentNode.show();
                                    $(\'position_note\').parentNode.parentNode.show();
                                    $(\'description\').parentNode.parentNode.show();
                                    $(\'style-slide-view\').show();
									url += "id/9";
                                    $(\'style-slide-view\').writeAttribute(\'href\', url);
                                    break;                               
                                case 7: 
                                    activesomeField710();                                                                        
									$(\'note_style_slide\').show();
                                    url += "id/5";
                                    $(\'style-slide-view\').writeAttribute(\'href\', url);
                                    break;
                                case 8: 
                                    activesomeField710();
									$(\'note_style_slide\').show();
                                    url +="id/6";
                                    $(\'style-slide-view\').writeAttribute(\'href\', url);
                                    break;
                                case 9: 
                                    activesomeField710();
									$(\'note_style_slide\').show();
                                    url += "id/7";
                                    $(\'style-slide-view\').writeAttribute(\'href\', url);
                                    break;
                                case 10: 
                                    activesomeField56();
									$(\'note_style_slide\').show();
                                    url += "id/8";
                                    $(\'style-slide-view\').writeAttribute(\'href\', url);
                                    break;
                                default:
                                    $(\'sort_type\').parentNode.parentNode.hide();
                                    $(\'width\').parentNode.parentNode.hide();                                                           
                                    $(\'height\').parentNode.parentNode.hide();                                                           
                                    $(\'animationB\').parentNode.parentNode.hide();                                                                       
                                    $(\'caption\').parentNode.parentNode.hide();                                            
									$(\'position\').parentNode.parentNode.hide();
									$(\'position_note\').parentNode.parentNode.hide();
									$(\'animationA\').parentNode.parentNode.hide();
									$(\'slider_speed\').parentNode.parentNode.hide();
									$(\'note_style_slide\').hide();
                                    $(\'style-slide-view\').writeAttribute(\'href\', \'\');
									
                            }
                        }
                        
                        function onchangeposition(){
                            
                             var checkposition = $(\'position\').value;
                             var tip = null;
                            if (checkposition == "customer-content-top"){
                                                                
                               $(\'position-tip-3\').hide();
                               $(\'position-tip-2\').hide();
                               $(\'position-tip-1\').show();
                                new Tooltip("position-tip-1", "'.Mage::getBaseUrl('media').'bannercampaign/bannerslider-ex1.png");
                            }
                            else if (checkposition == "checkout-content-top"){                                                          
                                 $(\'position-tip-3\').hide();
                                 $(\'position-tip-1\').hide();
                                 $(\'position-tip-2\').show();
                                 new Tooltip("position-tip-2", "'.Mage::getBaseUrl('media').'bannercampaign/bannerslider-ex2.png");
                            }else if(checkposition == "note-allsite"){                                                              
                                 $(\'position-tip-3\').hide();
                                 $(\'position-tip-1\').hide();
                                 $(\'position-tip-2\').hide();                                 
                                
                                 new Tooltip("position-tip-4", "'.Mage::getBaseUrl('media').'bannercampaign/bannerslider-ex4.PNG");
                            }else if(checkposition == "pop-up"){                                                                     
                                 $(\'position-tip-3\').hide();
                                 $(\'position-tip-1\').hide();
                                 $(\'position-tip-2\').hide();                                 
                                
                                 new Tooltip("position-tip-5", "'.Mage::getBaseUrl('media').'bannercampaign/bannerslider-ex5.PNG");
                            }
                            else{
                                                               
                                $(\'position-tip-2\').hide();
                                $(\'position-tip-1\').hide();
                                $(\'position-tip-3\').show();
                                new Tooltip("position-tip-3", "'.Mage::getBaseUrl('media').'bannercampaign/bannerslider-ex3.png");
                            }
                            
                            var category = checkposition.split("-");
                            var selCata = category[0];
                          
                            $(\'category_ids\').parentNode.parentNode.hide();
                            if(selCata == "category"){
                                $(\'category_ids\').parentNode.parentNode.show();
                            }                                                        
                        }
                        
                       function activesomeField14(){
                            $(\'sort_type\').parentNode.parentNode.show();
                            $(\'position\').parentNode.parentNode.show();
                            $(\'width\').parentNode.parentNode.show();                                                           
                            $(\'height\').parentNode.parentNode.show();                                                                                                                                   							
                            $(\'animationA\').parentNode.parentNode.show();
                            $(\'slider_speed\').parentNode.parentNode.show();
                            $(\'animationB\').parentNode.parentNode.hide();
                            $(\'position_note\').parentNode.parentNode.hide();	
                            if(!$(\'width\').value || $(\'width\').value == 0) $(\'width\').value = 400;
                            if(!$(\'height\').value || $(\'height\').value == 0) $(\'height\').value = 200;
                            if(!$(\'slider_speed\').value) $(\'slider_speed\').value = 4500;
                      }
						
		     function activesomeField56(){
                            $(\'sort_type\').parentNode.parentNode.show();
                            $(\'slider_speed\').parentNode.parentNode.hide();
                            $(\'width\').parentNode.parentNode.hide();                                                           
                            $(\'height\').parentNode.parentNode.hide();                                                                                                                                   
                            $(\'position\').parentNode.parentNode.hide();
                            $(\'animationA\').parentNode.parentNode.hide();
                            $(\'animationB\').parentNode.parentNode.hide();
                            $(\'position_note\').parentNode.parentNode.hide();
                            if(!$(\'width\').value || $(\'width\').value == 0) $(\'width\').value = 400;
                            if(!$(\'height\').value || $(\'height\').value == 0) $(\'height\').value = 200;
                            if(!$(\'slider_speed\').value) $(\'slider_speed\').value = 4500;							
						}
						
                    function activesomeField710(){
                        $(\'sort_type\').parentNode.parentNode.show();
			$(\'position\').parentNode.parentNode.show();
			$(\'animationB\').parentNode.parentNode.show();  
			$(\'slider_speed\').parentNode.parentNode.show();
			$(\'animationA\').parentNode.parentNode.hide();
			$(\'position_note\').parentNode.parentNode.hide();	
			$(\'width\').parentNode.parentNode.hide();                                                           
                        $(\'height\').parentNode.parentNode.hide();                     
			if(!$(\'slider_speed\').value) $(\'slider_speed\').value = 4500;							
		}
						
                        function hideSomeField(){
                            $(\'style_content\').parentNode.parentNode.hide();                                                           
                            $(\'style_slide\').parentNode.parentNode.hide();                                                           
                            $(\'sort_type\').parentNode.parentNode.hide();                                                                                                                                      
                            $(\'custom_code\').parentNode.parentNode.hide();     
                            $(\'width\').parentNode.parentNode.hide();                                                           
                            $(\'height\').parentNode.parentNode.hide();                                                           
                            $(\'animationB\').parentNode.parentNode.hide();      
							$(\'animationA\').parentNode.parentNode.hide();      
							$(\'slider_speed\').parentNode.parentNode.hide();							
                        }
                        
                        function showSomeField(){
                            $(\'style_content\').parentNode.parentNode.show();                                                           
                                 $(\'style_slide\').parentNode.parentNode.show();                                                           
                                 $(\'sort_type\').parentNode.parentNode.show();                                                                                                                                      
                                 $(\'custom_code\').parentNode.parentNode.show();     
                                 $(\'width\').parentNode.parentNode.show();                                                           
                                 $(\'height\').parentNode.parentNode.show();                                                           
                                 $(\'animationB\').parentNode.parentNode.show();        
							$(\'animationA\').parentNode.parentNode.show();      
							$(\'slider_speed\').parentNode.parentNode.show();							
                        }
                        
                        Event.observe(window,\'load\',onchangeStyleSlider); 
                        Event.observe(window,\'load\',onchangeStyleShow);   
                        Event.observe(window,\'load\',onchangeposition); 
                        </script>',     
        ));

        //Generate code for banner slider
//        if($this->getRequest()->getParam('id')){
//        $genertate_code = $fieldset->addField('show_code', 'select', array(
//            'label' => Mage::helper('campaign')->__('Generate Code:'),
//            'name' => 'show_code',
//            'values' => array(
//                array(
//                    'value' => 'no',
//                    'label' => Mage::helper('campaign')->__('No'),
//                ),
//                array(
//                    'value' => 'yes',
//                    'label' => Mage::helper('campaign')->__('Yes'),
//                ),
//            ),
//        ));
//
//        $template_code = $fieldset->addField('template_code', 'textarea', array(
//            'label' => Mage::helper('campaign')->__('Code for template:'),
//            'name' => 'template_code',
//            'width' =>'1000',
//            'height'=>'800',
//        ));
//        $block_code = $fieldset->addField('block_code', 'textarea', array(
//            'label' => Mage::helper('campaign')->__('Code for block:'),
//            'name' => 'block_code',
//        ));
//
//            $xml_code = $fieldset->addField('xml_code', 'textarea', array(
//                'label' => Mage::helper('campaign')->__('Code for xml:'),
//                'name' => 'xml_code',
//            ));
//
//
//        $layout_code = $fieldset->addField('layout_code', 'textarea', array(
//            'label' => Mage::helper('campaign')->__('Code for layout'),
//            'name' => 'layout_code',
//            'value'  => '<b>phuong<b/>',
//        ));
//
//            //$this->setForm($form);
//            $this->setChild('form_after', $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
//                    ->addFieldMap($genertate_code->getHtmlId(), $genertate_code->getName())
//                    ->addFieldMap($template_code->getHtmlId(), $template_code->getName())
//                    ->addFieldMap($block_code->getHtmlId(), $block_code->getName())
//                    ->addFieldMap($xml_code->getHtmlId(), $xml_code->getName())
//                    ->addFieldMap($layout_code->getHtmlId(), $layout_code->getName())
//                    ->addFieldDependence(
//                        $template_code->getName(),
//                        $genertate_code->getName(),
//                        'yes'
//                    )
//                    ->addFieldDependence(
//                        $block_code->getName(),
//                        $genertate_code->getName(),
//                        'yes'
//                    )
//                    ->addFieldDependence(
//                        $xml_code->getName(),
//                        $genertate_code->getName(),
//                        'yes'
//                    )
//                    ->addFieldDependence(
//                        $layout_code->getName(),
//                        $genertate_code->getName(),
//                        'yes'
//                    )
//            );
//        }
        //end if Generate code for banner slider

        $form->setValues($data);

        return parent::_prepareForm();
    }

}