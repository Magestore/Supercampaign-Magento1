<?php

class Magestore_Campaign_Block_Adminhtml_Banner_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {

        $form = new Varien_Data_Form();
        $this->setForm($form);

        $dataObj = new Varien_Object(
            array(
                'store_id' => '',
                'name_in_store' => '',
                'status_in_store' => '',
                'click_url_in_store' => '',
            )
        );
        if (Mage::getSingleton('adminhtml/session')->getBannerData()) {
            $data = Mage::getSingleton('adminhtml/session')->getBannerData();
            Mage::getSingleton('adminhtml/session')->setBannerData(null);
        } elseif (Mage::registry('banner_data'))
            $data = Mage::registry('banner_data')->getData();

        if (isset($data)) {
            $dataObj->addData($data);
        }
        $data = $dataObj->getData();
        $inStore = $this->getRequest()->getParam('store');
        $defaultLabel = Mage::helper('campaign')->__('Use Default');
        $defaultTitle = Mage::helper('campaign')->__('-- Please Select --');
        $scopeLabel = Mage::helper('campaign')->__('STORE VIEW');

        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig();
        $wysiwygConfig->addData(array(
            'add_variables' => false,
            'plugins' => array(),
            'widget_window_url' => Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/widget/index'),
            'directives_url' => Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg/directive'),
            'directives_url_quoted' => preg_quote(Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg/directive')),
            'files_browser_window_url' => Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg_images/index'),
        ));

        $fieldset = $form->addFieldset('banner_form', array('legend' => Mage::helper('campaign')->__('Banner information')));

        $image_calendar = Mage::getBaseUrl('skin') . 'adminhtml/default/default/images/grid-cal.gif';

        $fieldset->addField('name', 'text', array(
            'label' => Mage::helper('campaign')->__('Name'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'name',
            'disabled' => ($inStore && !$data['name_in_store']),
            'after_element_html' => $inStore ? '</td><td class="use-default">
                                      <input id="name_default" name="name_default" type="checkbox" value="1" class="checkbox config-inherit" ' . ($data['name_in_store'] ? '' : 'checked="checked"') . ' onclick="toggleValueElements(this, Element.previous(this.parentNode))" />
                                      <label for="name_default" class="inherit" title="' . $defaultTitle . '">' . $defaultLabel . '</label>
                        </td><td class="scope-label">
                                      [' . $scopeLabel . ']
                        ' : '</td><td class="scope-label">
                                      [' . $scopeLabel . ']',
        ));

        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('campaign')->__('Status'),
            'name' => 'status',
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
            'disabled' => ($inStore && !$data['status_in_store']),
            'after_element_html' => $inStore ? '</td><td class="use-default">
                                      <input id="status_default" name="status_default" type="checkbox" value="1" class="checkbox config-inherit" ' . ($data['status_in_store'] ? '' : 'checked="checked"') . ' onclick="toggleValueElements(this, Element.previous(this.parentNode))" />
                                      <label for="status_default" class="inherit" title="' . $defaultTitle . '">' . $defaultLabel . '</label>
                        </td><td class="scope-label">
                                      [' . $scopeLabel . ']
                        ' : '</td><td class="scope-label">
                                      [' . $scopeLabel . ']',
        ));
//        if ($this->getRequest()->getParam('id') || count(Mage::helper('campaign')->getOptionSliderId()) > 1){
//            $fieldset->addField('bannerslider_id', 'select', array(
//                'label' => Mage::helper('campaign')->__('Slider'),
//                'name' => 'bannerslider_id',
//                'values' => Mage::helper('campaign')->getOptionSliderId(),
//            ));
//        }

        $slider_id = $this->getRequest()->getParam('sliderid');

        $fieldset->addField('sliderid', 'hidden', array(
            'label' => Mage::helper('campaign')->__('Alt Text'),
            'name' => 'sliderid',
        ));
        if($slider_id){
            $data['sliderid'] = $slider_id;
        }


        $fieldset->addField('image_alt', 'text', array(
            'label' => Mage::helper('campaign')->__('Alt Text'),
            'name' => 'image_alt',
            'note' => 'Used for SEO',
        ));

        $fieldset->addField('click_url', 'text', array(
            'label' => Mage::helper('campaign')->__('URL'),
            'required' => false,
            'name' => 'click_url',
            'disabled' => ($inStore && !$data['click_url_in_store']),
            'after_element_html' => $inStore ? '</td><td class="use-default">
                                      <input id="click_url_default" name="click_url_default" type="checkbox" value="1" class="checkbox config-inherit" ' . ($data['click_url_in_store'] ? '' : 'checked="checked"') . ' onclick="toggleValueElements(this, Element.previous(this.parentNode))" />
                                      <label for="click_url_default" class="inherit" title="' . $defaultTitle . '">' . $defaultLabel . '</label>
                        </td><td class="scope-label">
                                      [' . $scopeLabel . ']
                        ' : '</td><td class="scope-label">
                                      [' . $scopeLabel . ']',
        ));

        // $fieldset->addField('clicktotal', 'text', array(
        // 'label' => Mage::helper('bannerslider')->__('Click Total'),
        // 'required' => false,
        // 'readonly'  => 'readonly',
        // 'name' => 'clicktotal',
        // ));

        // $fieldset->addField('imptotal', 'text', array(
        // 'label' => Mage::helper('bannerslider')->__('Impression Total'),
        // 'required' => false,
        // 'readonly'  => 'readonly',
        // 'name' => 'imptotal',
        // ));  	
        if (isset($data['image']) && $data['image']) {
            $imageName = Mage::helper('campaign')->reImageName($data['image']);
            $data['image'] = 'bannercampaign' . '/' . $imageName;
        }
        $fieldset->addField('image', 'image', array(
            'label' => Mage::helper('campaign')->__('Banner Image'),
            'required' => false,
            'name' => 'image',
        ));
        try {
            $data['start_time']=date('Y-m-d H:i:s',Mage::getModel('core/date')->timestamp(strtotime($data['start_time'])));
            $data['end_time']=date('Y-m-d H:i:s',Mage::getModel('core/date')->timestamp(strtotime($data['end_time'])));
        } catch (Exception $e) {

        }

        $note = $this->__('The current server time is').': '.$this->formatTime(now(),Mage_Core_Model_Locale::FORMAT_TYPE_SHORT,true);
//        $fieldset->addField('start_time', 'date', array(
//            'label'     => Mage::helper('campaign')->__('Start time'),
//            'name'      => 'start_time',
//            'input_format'  => Varien_Date::DATETIME_INTERNAL_FORMAT,
//            'image' => $image_calendar,
//            'format'    =>Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
//            'time' => true,
//            'required'  => true,
//        ));
//        $fieldset->addField('end_time', 'date', array(
//            'label'     => Mage::helper('campaign')->__('End time'),
//            'name'      => 'end_time',
//            'input_format'  => Varien_Date::DATETIME_INTERNAL_FORMAT,
//            'image' => $image_calendar,
//            'format'    =>Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
//            'time' => true,
//            'required'  => true,
//            'note'=>$note,
//        ));


        $fieldset->addField('tartget', 'select', array(
            'label' => Mage::helper('campaign')->__('Target'),
            'class' => 'required-entry',
            'name' => 'tartget',
            'values' => array(
                array(
                    'value' => 2,
                    'label' => Mage::helper('campaign')->__('New Window without Browser Navigation'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('campaign')->__('New Window with Browser Navigation'),
                ),
                array(
                    'value' => 1,
                    'label' => Mage::helper('campaign')->__('Parent Window with Browser Navigation'),
                ),
            ),
        ));
        $form->setValues($data);

        return parent::_prepareForm();
    }

    protected function _prepareLayout() {

        $return = parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        return $return;
    }

}