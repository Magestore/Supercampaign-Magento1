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

//        $fieldset->addField('campaign_id', 'select', array(
//            'label' => Mage::helper('campaign')->__('Campaign'),
//            'name' => 'campaign_id',
//            'required' => true,
//            'values' => Mage::getSingleton('campaign/campaign')->getCampaignOption(),
//        ));

		$fieldset->addField('popup_type', 'select', array(
			'label'		=> Mage::helper('campaign')->__('Popup Content Type:'),
			'required'	=> true,
			'name'		=> 'popup_type',
            'note'      => 'Type of popup.',
            'values' => array(
                array(
                    'value' => 0,
                    'label' => Mage::helper('campaign')->__('Template'),
                ),
                array(
                    'value' => 1,
                    'label' => Mage::helper('campaign')->__('Image'),
                ),
            ),
		));

        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }

//        $fieldset->addField('popup_content', 'editor', array(
//            'name'		=> 'popup_content',
//            'label'		=> Mage::helper('campaign')->__('Content:'),
//            'title'		=> Mage::helper('campaign')->__('Content:'),
//            'style'		=> 'width:800px; height:350px;',
//            'wysiwyg'	=> true,
//            'config'    => Mage::getSingleton('cms/wysiwyg_config')->getConfig(),
//            'required'	=> true,
//        ));

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

        $fieldset->addField('height', 'text', array(
            'label'		=> Mage::helper('campaign')->__('Height:'),
            'required'	=> false,
            'name'		=> 'height',
        ));

		$fieldset->addField('status', 'select', array(
			'label'		=> Mage::helper('campaign')->__('Status:'),
			'name'		=> 'status',
			'values'	=> Mage::getSingleton('campaign/sliderstatus')->getOptionHash(),
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

//        $fieldset->addField('product_id', 'text', array(
//            'label'		=> Mage::helper('campaign')->__('Product Ids:'),
//            'required'	=> false,
//            'name'		=> 'product_id',
//            'note'      => 'Show popup for products detail page have selected.',
//        ));

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

        $fieldset->addField('show_when', 'select', array(
            'label'		=> Mage::helper('campaign')->__('Show When:'),
            'required'	=> true,
            'name'		=> 'show_when',
            'values' => array(
                array(
                    'value' => 0,
                    'label' => Mage::helper('campaign')->__('After Load Page'),
                ),
                array(
                    'value' => 1,
                    'label' => Mage::helper('campaign')->__('After Seconds'),
                ),
            ),
        ));

        $fieldset->addField('second_show', 'text', array(
            'label'		=> Mage::helper('campaign')->__('Seconds:'),
            'required'	=> false,
            'name'		=> 'second_show',
            'note'      => 'Seconds to show popup.',
        ));

        $fieldset->addField('scrolling_show', 'text', array(
            'label'		=> Mage::helper('campaign')->__('Scrolling Px:'),
            'required'	=> false,
            'name'		=> 'scrolling_show',
            'note'      => 'Position scrolling to show popup.',
        ));

        $fieldset->addField('selector_show', 'text', array(
            'label'		=> Mage::helper('campaign')->__('Selector:'),
            'required'	=> false,
            'name'		=> 'selector_show',
            'note'      => 'Show popup when click or hover into id and class selected.',
        ));

        $fieldset->addField('close_on_hoverout', 'select', array(
            'label'		=> Mage::helper('campaign')->__('Close on hover out:'),
            'required'	=> true,
            'name'		=> 'close_on_hoverout',
            'values' => array(
                array(
                    'value' => 0,
                    'label' => Mage::helper('campaign')->__('No'),
                ),
                array(
                    'value' => 1,
                    'label' => Mage::helper('campaign')->__('Yes'),
                ),
            ),
        ));

		$form->setValues($data);
		return parent::_prepareForm();
	}
}