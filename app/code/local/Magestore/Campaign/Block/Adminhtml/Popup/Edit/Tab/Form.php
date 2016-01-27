<?php

class Magestore_Campaign_Block_Adminhtml_Popup_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Load Wysiwyg on demand and Prepare layout
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        }
    }

	protected function _prepareForm(){
		$form = new Varien_Data_Form();
		$this->setForm($form);
		
		if (Mage::getSingleton('adminhtml/session')->getPopupData()){
			$data = Mage::getSingleton('adminhtml/session')->getPopupData();
			Mage::getSingleton('adminhtml/session')->setPopupData(null);
		}elseif(Mage::registry('popup_data'))
			$data = Mage::registry('popup_data')->getData();
		
		$fieldset = $form->addFieldset('popup_form', array('legend'=>Mage::helper('campaign')->__('Popup information')));

		$fieldset->addField('title', 'text', array(
			'label'		=> Mage::helper('campaign')->__('Title:'),
			'class'		=> 'required-entry',
			'required'	=> true,
			'name'		=> 'title',
		));

        if(!isset($data['status'])) $data['status'] = Magestore_Campaign_Model_Status::STATUS_ENABLED;
        $fieldset->addField('status', 'select', array(
            'label'		=> Mage::helper('campaign')->__('Status:'),
            'name'		=> 'status',
            'values'	=> Magestore_Campaign_Model_Status::getOptionHash(),
        ));

		/*$fieldset->addField('popup_type', 'select', array(
			'label'		=> Mage::helper('campaign')->__('Popup Content Type:'),
			'required'	=> true,
			'name'		=> 'popup_type',
            'note'      => 'Type of popup.',
            'values' => array(
                array(
                    'value' => 'static',
                    'label' => Mage::helper('campaign')->__('Static'),
                ),
                array(
                    'value' => 'video',
                    'label' => Mage::helper('campaign')->__('Video'),
                ),
                array(
                    'value' => 'sticker',
                    'label' => Mage::helper('campaign')->__('Sticker'),
                ),
                array(
                    'value' => 'subscribe',
                    'label' => Mage::helper('campaign')->__('Subscribe'),
                ),
                array(
                    'value' => 'register',
                    'label' => Mage::helper('campaign')->__('Register'),
                ),
            ),
		));*/

        $fieldset->addField('template_code', 'hidden', array(
            'name'		=> 'template_code',
            'label'		=> Mage::helper('campaign')->__('Template code:'),
            'required'	=> false,
        ));

        $fieldset->addField('load_template', 'label', array(
            'label'		=> Mage::helper('campaign')->__(''),
            'after_element_html' => '<button id="" type="button" class="scalable add" style="" alt="Load template"
 title="Load template" onclick="popupwindow(\''.$this->getUrl('campaignadmin/adminhtml_popup/loadTemplate').'\', \'_blank\', 1000, 500);" href="javascript:void(0);" <span="">'.$this->__('Create popup using predefined templates').'
        </button>
        <script type="text/javascript">
        var popupLoadTemplate;
        function popupwindow(url, title, w, h) {
  var left = (screen.width/2)-(w/2);
  var top = (screen.height/2)-(h/2);
  wLoad = window.open(url, title, \'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, copyhistory=no, width=\'+w+\', height=\'+h+\', top=\'+top+\', left=\'+left);
  //load after window close
  wLoad.onunload = function(e){
    if (wLoad.closed) {
        //window closed
        var template_id = wLoad.document.getElementById(\'template_id\').value;
        window.location.href = "'.$this->getUrl('campaignadmin/adminhtml_popup/newfromtemplate/').'template_id/"+template_id;
        console.log(wLoad.document.getElementById(\'template_id\').value);
    }else{
       //just refreshed
    }
  }
  return popupLoadTemplate;
}


</script>',
        ));


        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }

        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig(
            array(
                'hidden'=>false,
                'add_variables' => true,
                'add_widgets' => true,
                'add_images'=>true,
                'widget_window_url'	=> Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/widget/index'),
                'directives_url'	=> Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg/directive'),
                'directives_url_quoted'	=> preg_quote(Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg/directive')),
                'files_browser_window_url'	=> Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg_images/index')
            )
        );
        $fieldset->addField('popup_content', 'editor', array(
            'label' => Mage::helper('campaign')->__('Content:'),
            'title' => Mage::helper('campaign')->__('Content:'),
            'style'		=> 'width:800px; height:350px;',
            'name' => 'popup_content',
            'wysiwyg' => true,
            'config'        =>$wysiwygConfig,
            'required'	=> true,
        ));

        $fieldset->addField('width', 'text', array(
            'label'		=> Mage::helper('campaign')->__('Width:'),
            'required'	=> false,
            'name'		=> 'width',
        ));


        if (!Mage::app()->isSingleStoreMode()) {
            $field = $fieldset->addField('store', 'multiselect', array(
                'name'      => 'store[]',
                'label'     => $this->__('Store View'),
                'title'     => $this->__('Store View'),
                'required'  => true,
                'style'        => 'height:180px;',
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            ));
            $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
            $field->setRenderer($renderer);
        }
        else {
            $fieldset->addField('store', 'hidden', array(
                'name'      => 'store[]',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
            $data['store'] = Mage::app()->getStore(true)->getId();
        }

        $fieldset->addField('page_id', 'select', array(
            'label'		=> Mage::helper('campaign')->__('Show On Page:'),
            'required'	=> true,
            'name'		=> 'page_id',
            'note'      => 'Show popup when with page selectd.',
            'values' => array(
                array(
                    'value' => 0,
                    'label' => Mage::helper('campaign')->__('All Page'),
                ),
                array(
                    'value' => 1,
                    'label' => Mage::helper('campaign')->__('Home Page'),
                ),
                array(
                    'value' => 2,
                    'label' => Mage::helper('campaign')->__('Product Page'),
                ),
                array(
                    'value' => 3,
                    'label' => Mage::helper('campaign')->__('Category Page'),
                ),
                array(
                    'value' => 4,
                    'label' => Mage::helper('campaign')->__('Checkout Page'),
                ),
                array(
                    'value' => 5,
                    'label' => Mage::helper('campaign')->__('Cart Page'),
                ),
                array(
                    'value' => 6,
                    'label' => Mage::helper('campaign')->__('Other Page'),
                ),
                array(
                    'value' => 7,
                    'label' => Mage::helper('campaign')->__('Specified Url'),
                ),
            ),
        ));

        $fieldset->addField('categories', 'text', array(
            'label'		=> Mage::helper('campaign')->__('Category Ids:'),
            'required'	=> false,
            'name'		=> 'categories',
            'note'      => 'Show popup for categories have selected.',
        ));

        $fieldset->addField('specified_url', 'text', array(
            'label'		=> Mage::helper('campaign')->__('Specified Url:'),
            'required'	=> false,
            'name'		=> 'specified_url',
        ));

        $fieldset->addField('exclude_url', 'text', array(
            'label'		=> Mage::helper('campaign')->__('Exclude Url:'),
            'required'	=> false,
            'name'		=> 'exclude_url',
            'note'      => "Don't show popup when open page have url like exclude url.",
        ));

        $productIds = implode(", ", Mage::getResourceModel('catalog/product_collection')->getAllIds());
        $fieldset->addField('products', 'text', array(
            'label' => Mage::helper('campaign')->__('Products'),
            'name' => 'products',
            'class' => 'rule-param',
            'after_element_html' => '<a id="product_link" href="javascript:void(0)" onclick="toggleMainProducts()"><img src="' . $this->getSkinUrl('images/rule_chooser_trigger.gif') . '" alt="" class="v-middle rule-chooser-trigger" title="Select Products"></a><input type="hidden" value="'.$productIds.'" id="product_all_ids"/><div id="main_products_select" style="display:none;width:640px"></div>
                <script type="text/javascript">
                    function toggleMainProducts(){
                        if($("main_products_select").style.display == "none"){
                            var url = "' . $this->getUrl('campaignadmin/adminhtml_popup/chooserMainProducts') . '";
                            var params = $("products").value.split(", ");
                            var parameters = {"form_key": FORM_KEY,"selected[]":params };
                            var request = new Ajax.Request(url,
                            {
                                evalScripts: true,
                                parameters: parameters,
                                onComplete:function(transport){
                                    $("main_products_select").update(transport.responseText);
                                    $("main_products_select").style.display = "block";
                                }
                            });
                        }else{
                            $("main_products_select").style.display = "none";
                        }
                    };


                </script>'
        ));

        $show_when = $fieldset->addField('show_when', 'select', array(
            'label'		=> Mage::helper('campaign')->__('Show When:'),
            'required'	=> true,
            'name'		=> 'show_when',
            'values' => array(
                array(
                    'value' => 'after_load_page',
                    'label' => Mage::helper('campaign')->__('After Load Page'),
                ),
                array(
                    'value' => 'after_seconds',
                    'label' => Mage::helper('campaign')->__('After Seconds'),
                ),
            ),
        ));

        $seconds_number0 = $fieldset->addField('seconds_delay', 'text', array(
            'label'		=> Mage::helper('campaign')->__('Seconds:'),
            'required'	=> false,
            'name'		=> 'seconds_delay',
            'note'      => 'Seconds to show popup.',
        ));

		$form->setValues($data);

        $this->setForm($form);
        $this->setChild('form_after', $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
                ->addFieldMap($show_when->getHtmlId(), $show_when->getName())
                ->addFieldMap($seconds_number0->getHtmlId(), $seconds_number0->getName())
                ->addFieldDependence(
                    $seconds_number0->getName(),
                    $show_when->getName(),
                    'after_seconds'
                )
        );

		return parent::_prepareForm();
	}
}