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
 * Campaign Adminhtml Controller
 * 
 * @category    Magestore
 * @package     Magestore_Campaign
 * @author      Magestore Developer
 */
class Magestore_Campaign_Adminhtml_CampaignController extends Mage_Adminhtml_Controller_Action
{
    /**
     * init layout and set active for current menu
     *
     * @return Magestore_Campaign_Adminhtml_CampaignController
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('campaign/campaign')
            ->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Items Manager'),
                Mage::helper('adminhtml')->__('Item Manager')
            );
        return $this;
    }
 
    /**
     * index action
     */
    public function indexAction()
    {
        $this->_initAction()
            ->renderLayout();
    }

    /**
     * view and edit item action
     */
    public function editAction()
    {
        $campaignId     = $this->getRequest()->getParam('id');
        $model  = Mage::getModel('campaign/campaign')->load($campaignId);
        if ($model->getId() || $campaignId == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
            /*set start date, end date to UTC timezone*/
            //$model->setStartTime($model->toLocaleTimezone($model->getStartTime()));
            //$model->setEndTime($model->toLocaleTimezone($model->getEndTime()));
            $model->setStartTime($model->getStartTime());
            $model->setEndTime($model->getEndTime());

            //forms data array
            $popup_data = array();
            $sidebar_data = array();
            $bannerlistpage_data = array();

            //get childs model
            $popup = $model->getPopup();
            $sidebar = $model->getSidebar();
            $headertext = $model->getHeadertext();

            //append prefix all data popup type to array
            $_data = $popup->getAllData();
            foreach ($_data as $key => $value) {
                $popup_data['popup_'.$key] = $value;
            }

            Mage::register('campaign_data', $model);

            /**
             * add default content template to edit new campaign
             */
            if(!$campaignId){
                /*get default popup template content*/
                $templates = Mage::getModel('campaign/template')->getOptions(
                    Magestore_Campaign_Model_Popup_Type_Static::TEMPLATE_GROUP,
                    Magestore_Campaign_Model_Popup_Type_Static::TEMPLATE_TYPE);
                if(count($templates)){
                    $popup_data['popup_static_template_id'] = $templates[0]['value']; //templateId
                    $template = Mage::getModel('campaign/template')->load($templates[0]['value']);
                    $popup_data['popup_static_content'] = $template->getContent();
                }
                $templates_form_1 = Mage::getModel('campaign/template')->getOptions(
                    Magestore_Campaign_Model_Popup_Type_Form::TEMPLATE_GROUP,
                    Magestore_Campaign_Model_Popup_Type_Form::TEMPLATE_TYPE_STEP_1);
                if(count($templates_form_1)){
                    $popup_data['popup_form_template_id_one'] = $templates_form_1[0]['value'];//templateId
                    $template = Mage::getModel('campaign/template')->load($templates_form_1[0]['value']);
                    $popup_data['popup_form_content_step_one'] = $template->getContent();
                }
                $templates_form_2 = Mage::getModel('campaign/template')->getOptions(
                    Magestore_Campaign_Model_Popup_Type_Form::TEMPLATE_GROUP,
                    Magestore_Campaign_Model_Popup_Type_Form::TEMPLATE_TYPE_STEP_2);
                if(count($templates_form_2)){
                    $popup_data['popup_form_template_id_two'] = $templates_form_2[0]['value'];//templateId
                    $template = Mage::getModel('campaign/template')->load($templates_form_2[0]['value']);
                    $popup_data['popup_form_content_step_two'] = $template->getContent();
                }
                /*get default popup game template content*/
                $options= Mage::getModel('campaign/template')->getOptions(
                    Magestore_Campaign_Model_Popup_Type_Game_Halloween::TEMPLATE_GROUP,
                    Magestore_Campaign_Model_Popup_Type_Game_Halloween::TEMPLATE_TYPE_STEP_1);
                if(count($options)){
                    $popup_data['popup_game_halloween_template_step_1'] = $options[0]['value'];//templateId
                    $template = Mage::getModel('campaign/template')->load($options[0]['value']);
                    $popup_data['popup_game_halloween_content_step_1'] = $template->getContent();
                }
                $options= Mage::getModel('campaign/template')->getOptions(
                    Magestore_Campaign_Model_Popup_Type_Game_Halloween::TEMPLATE_GROUP,
                    Magestore_Campaign_Model_Popup_Type_Game_Halloween::TEMPLATE_TYPE_STEP_2);
                if(count($options)){
                    $popup_data['popup_game_halloween_template_step_2'] = $options[0]['value'];//templateId
                    $template = Mage::getModel('campaign/template')->load($options[0]['value']);
                    $popup_data['popup_game_halloween_content_step_2'] = $template->getContent();
                }
                $options= Mage::getModel('campaign/template')->getOptions(
                    Magestore_Campaign_Model_Popup_Type_Game_Halloween::TEMPLATE_GROUP,
                    Magestore_Campaign_Model_Popup_Type_Game_Halloween::TEMPLATE_TYPE_STEP_3);
                if(count($options)){
                    $popup_data['popup_game_halloween_template_step_3'] = $options[0]['value'];//templateId
                    $template = Mage::getModel('campaign/template')->load($options[0]['value']);
                    $popup_data['popup_game_halloween_content_step_3'] = $template->getContent();
                }
                /*get default sidebar template content*/
                $templates = Mage::getModel('campaign/template')->getOptions(
                    Magestore_Campaign_Model_Sidebar::TEMPLATE_GROUP);
                if(count($templates)){
                    $sidebar_data['sidebar_template_id'] = $templates[0]['value']; //templateId
                    $template = Mage::getModel('campaign/template')->load($templates[0]['value']);
                    $sidebar_data['sidebar_content'] = $template->getContent();
                }
                /*get default headertext template content*/
                $templates = Mage::getModel('campaign/template')->getOptions(
                    Magestore_Campaign_Model_Headertext::TEMPLATE_GROUP);
                if(count($templates)){
                    $headertext->setData('template_id', $templates[0]['value']); //templateId
                    $template = Mage::getModel('campaign/template')->load($templates[0]['value']);
                    $headertext->setData('content', $template->getContent());
                }
            }
            Mage::register('popup_data', new Varien_Object($popup_data));

            /**
             * edit data header text
             */
            /*$headertext_data = array();
            foreach ($model->getHeadertext()->getData() as $key => $value) {
                $headertext_data['headertext_'.$key] = $value;
            }*/
            Mage::register('headertext_data', new Varien_Object($headertext->getData()));

            /**
             * edit data sidebar
             */
            foreach ($sidebar->getData() as $key => $value) {
                $sidebar_data[Magestore_Campaign_Model_Sidebar::PREFIX.$key] = $value;
            }
            Mage::register('sidebar_data', new Varien_Object($sidebar_data));

            /**
             * zeus edit data countdown
             */
            $countdown_data = array();
            foreach ($model->getCountdown()->getData() as $key => $value) {
                $countdown_data['countdown_'.$key] = $value;
            }
            Mage::register('countdown_data', new Varien_Object($countdown_data));

            /*zeus edit data countdown*/

            /**
             * footer controller
             */
            $this->loadLayout();
            $this->_setActiveMenu('campaign/campaign');

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('campaign/adminhtml_campaign_edit'))
                ->_addLeft($this->getLayout()->createBlock('campaign/adminhtml_campaign_edit_tabs'));
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('campaign')->__('Campaign does not exist')
            );
            $this->_redirect('*/*/');
        }
    }
 
    public function newAction()
    {
        $this->_forward('edit');
    }
 
    /**
     * save item action
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $campaignData = new Varien_Object($data);
            /* save campaign model */

            $model = Mage::getModel('campaign/campaign');

            //save store
            if(isset($data['stores']) && is_array($data['stores'])){
                $store = implode(',', $data['stores']);
                $data['store'] = $store.','; //fix store view in grid
            }


            $model->setData($data)
                ->setId($this->getRequest()->getParam('id'));
            /*set start date, end date to UTC timezone*/
            $model->setStartTime($model->toUTCTimezone($campaignData->getStartTime()));
            $model->setEndTime($model->toUTCTimezone($campaignData->getEndTime()));

            //add data for widget banner
            if(isset($data[Magestore_Campaign_Model_Widget_Banner::PREFIX_DATA])){
                if($model->getWidgetBannerId()){

                }
            }

            //get data for popup have prefix popup_
            $popup_data = array();
            //$headertext_data = array();
            $sidebar_data = array();
//            $bannerlistpage_data = array();
//            $bannermenu_data = array();
//            $bannerhomepage_data = array();
            $countdown_data = array();

            foreach($data as $key => $value){
                //get popup data
                if(strpos($key, 'popup_') === 0){
                    $name = substr($key, strlen('popup_')); //remove prefix string
                    $popup_data[$name] = $value;
                }
                //get header text data
                /*if(strpos($key, 'headertext_') === 0){
                    $name = substr($key, strlen('headertext_')); //remove prefix string
                    $headertext_data[$name] = $value;
                }*/
                //get left sidebar data
                if(strpos($key, 'sidebar_') === 0){
                    $name = substr($key, strlen('sidebar_')); //remove prefix string
                    $sidebar_data[$name] = $value;
                }

//                //zeus get bannerlistpage data
//                if(strpos($key, 'bannerlistpage_') === 0){
//                    $name = substr($key, strlen('bannerlistpage_')); //remove prefix string
//                    $bannerlistpage_data[$name] = $value;
//                }
//
//                //zeus get bannermenu data
//                if(strpos($key, 'bannermenu_') === 0){
//                    $name = substr($key, strlen('bannermenu_')); //remove prefix string
//                    $bannermenu_data[$name] = $value;
//                }
//
//                //zeus get bannermenu data
//                if(strpos($key, 'bannerhomepage_') === 0){
//                    $name = substr($key, strlen('bannerhomepage_')); //remove prefix string
//                    $bannerhomepage_data[$name] = $value;
//                }

                //zeus get countdown data
                if(strpos($key, 'countdown_') === 0){
                    $name = substr($key, strlen('countdown_')); //remove prefix string
                    $countdown_data[$name] = $value;
                }

            }
            $popup_data = new Varien_Object($popup_data); //convert array to model
            $sidebar_data = new Varien_Object($sidebar_data); //convert array to model
            //$headertext_data = new Varien_Object($data[Magestore_Campaign_Model_Headertext::PREFIX]); //convert array to model
//            $bannerlistpage_data = new Varien_Object($bannerlistpage_data); //convert array to model
//            $bannermenu_data = new Varien_Object($bannermenu_data); //convert array to model
//            $bannerhomepage_data = new Varien_Object($bannerhomepage_data); //convert array to model
            $countdown_data = new Varien_Object($countdown_data); //convert array to model

            try {

                $model->save();


                /*get all added banner to this campaign*/
                /*saving banner campaign_id*/
                $banner_ids = array();
                if(isset($data['banner_ids'])){
                    $banner_ids = explode('&', $data['banner_ids']);
                }
                $banners = Mage::getModel('campaign/widget_banner')->getCollection();
                $banners->addFieldToFilter(array('campaign_id', 'widget_banner_id'),
                    array($model->getId(), array('in'=>$banner_ids)));
                foreach($banners as $banner){
                    if(in_array($banner->getId(), $banner_ids)){
                        $banner->setCampaignId($model->getId());//set campaign id to banners
                    }else{
                        $banner->setCampaignId('');//set no campaign id to banners
                    }
                    $banner->save();
                }

                /**
                 * save popup
                 */
                $popup = $model->getPopup($popup_data->getPopupType()); //must set type to load exactily type model
                if($popup->getId()){
                    $popup->addData($popup_data->getData());
                }else{
                    $popup->setData($popup_data->getData());
                }
                $popup->setPopupType($popup_data->getPopupType());
                $popup->setCampaignId($model->getId());
                $popup->save();

                /**
                 * save header text
                 */
//                $headertext = $model->getHeadertext();
//                if($headertext->getId()){
//                    $headertext->addData($data[Magestore_Campaign_Model_Headertext::PREFIX]);
//                }else{
//                    $headertext->setData($data[Magestore_Campaign_Model_Headertext::PREFIX]);
//                }
//                $headertext->setCampaignId($model->getId());
//                $headertext->save();

                /**
                 * save sidebar
                 */
                $sidebar = $model->getSidebar();
                if($sidebar->getId()){
                    $sidebar->addData($sidebar_data->getData());
                }else{
                    $sidebar->setData($sidebar_data->getData());
                }
                $sidebar->setCampaignId($model->getId());
                $sidebar->save();


                /* zeus save inmage banner listpage*/
//                if(isset($_FILES['bannerlistpage_input_banner']['name']) && $_FILES['bannerlistpage_input_banner']['name'] != '') {
//                    try {
//                        /* Starting upload */
//                        $uploader = new Varien_File_Uploader('bannerlistpage_input_banner');
//
//                        // Any extention would work
//                        $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
//                        $uploader->setAllowRenameFiles(false);
//
//                        // Set the file upload mode
//                        // false -> get the file directly in the specified folder
//                        // true -> get the file in the product like folders
//                        //	(file.jpg will go in something like /media/f/i/file.jpg)
//                        $uploader->setFilesDispersion(false);
//
//                        // We set media as the upload dir
//                        $path = Mage::getBaseDir() . DS . 'media' . DS . 'magestore'. DS . 'campaign';
//                        $result = $uploader->save($path, $_FILES['bannerlistpage_input_banner']['name'] );
//
//                        //zend_debug::dump($uploader); die('khong up duoc anh');
//                    } catch (Exception $e) {}
//
//                    //this way the name is saved in DB
//                    $bannerlistpage_data['path_bannerlistpage'] = substr($path,28);
//                    //zend_debug::dump($bannerlistpage_data['path_bannerlistpage']); die('khong up duoc anh');
//
//                    $bannerlistpage_data->setData('input_banner', 'magestore/campaign/'.$result['file']);//$_FILES['input_banner']['name'];
//                    //zend_debug::dump($bannerlistpage_data); die('khong up duoc anh');
//                }

                /**
                 * zeus save bannerlistpage
                 */
//                $bannerlistpage = $model->getBannerlistpage();
//                if(is_array($bannerlistpage_data->getData('input_banner'))){
//                    $bannerlistpage_data_value = $bannerlistpage_data->getData('input_banner');
//                    $bannerlistpage_data->setInputBanner($bannerlistpage_data_value['value']);
//                }

//                if($bannerlistpage->getId()){
//                    $bannerlistpage->addData($bannerlistpage_data->getData());
//                }else{
//                    $bannerlistpage->setData($bannerlistpage_data->getData());
//                }
//                $bannerlistpage->setCampaignId($model->getId());
//                $bannerlistpage->save();


                /* zeus save inmage banner menu*/
//                if(isset($_FILES['bannermenu_input_bannermenu']['name']) && $_FILES['bannermenu_input_bannermenu']['name'] != '') {
//                    try {
//                        /* Starting upload */
//                        $uploader = new Varien_File_Uploader('bannermenu_input_bannermenu');
//
//                        // Any extention would work
//                        $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
//                        $uploader->setAllowRenameFiles(false);
//
//                        // Set the file upload mode
//                        // false -> get the file directly in the specified folder
//                        // true -> get the file in the product like folders
//                        //	(file.jpg will go in something like /media/f/i/file.jpg)
//                        $uploader->setFilesDispersion(false);
//
//                        // We set media as the upload dir
//                        $path = Mage::getBaseDir() . DS . 'media' . DS . 'magestore'. DS . 'campaign';
//                        $result = $uploader->save($path, $_FILES['bannermenu_input_bannermenu']['name'] );
//                        //zend_debug::dump($uploader); die('khong up duoc anh');
//                    } catch (Exception $e) {}
//
////                    //this way the name is saved in DB
////                    $bannermenu_data->setData('path_bannermenu', substr($path,28));
////                    //zend_debug::dump($bannerlistpage_data['path_bannerlistpage']); die('khong up duoc anh');
////                    $bannermenu_data->setData('input_bannermenu', 'magestore/campaign/'.$result['file']);//$_FILES['bannermenu_input_bannermenu']['name'];
//
//                }
                /**
                 * zeus save bannermenu
                 */
//                $bannermenu = $model->getBannermenu();
//                if(is_array($bannermenu_data->getData('input_bannermenu'))){
//                    $bannermenu_data_value = $bannermenu_data->getData('input_bannermenu');
//                    $bannermenu_data->setInputBannermenu($bannermenu_data_value['value']);
//                }
//                if($bannermenu->getId()){
//                    $bannermenu->addData($bannermenu_data->getData());
//                }else{
//                    $bannermenu->setData($bannermenu_data->getData());
//                }
//                $bannermenu->setCampaignId($model->getId());
//                $bannermenu->save();


                /* zeus save inmage banner homepage*/
//                if(isset($_FILES['bannerhomepage_input_bannerhomepage']['name']) && $_FILES['bannerhomepage_input_bannerhomepage']['name'] != '') {
//                    try {
//                        /* Starting upload */
//                        $uploader = new Varien_File_Uploader('bannerhomepage_input_bannerhomepage');
//
//                        // Any extention would work
//                        $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
//                        $uploader->setAllowRenameFiles(false);
//
//                        // Set the file upload mode
//                        // false -> get the file directly in the specified folder
//                        // true -> get the file in the product like folders
//                        //	(file.jpg will go in something like /media/f/i/file.jpg)
//                        $uploader->setFilesDispersion(false);
//
//                        // We set media as the upload dir
//                        $path = Mage::getBaseDir() . DS . 'media' . DS . 'magestore'. DS . 'campaign';
//                        $result = $uploader->save($path, $_FILES['bannerhomepage_input_bannerhomepage']['name'] );
//                    } catch (Exception $e) {}
//
//                    //this way the name is saved in DB
//                    $bannerhomepage_data['path_bannerhomepage'] = substr($path,28);
//                    //zend_debug::dump($bannerlistpage_data['path_bannerlistpage']); die('khong up duoc anh');
//                    $bannerhomepage_data->setData('input_bannerhomepage', 'magestore/campaign/'.$result['file']);//$_FILES['input_bannerhomepage']['name'];
//                    //zend_debug::dump($bannerlistpage_data); die('khong up duoc anh');
//                }

                /**
                 * zeus save bannerhomepage
                 */
//                $bannerhomepage = $model->getBannerhomepage();
//                if(is_array($bannerhomepage_data->getData('input_bannerhomepage'))){
//                    $bannerhomepage_data_value = $bannerhomepage_data->getData('input_bannerhomepage');
//                    $bannerhomepage_data->setInputBannerhomepage($bannerhomepage_data_value['value']);
//                }
//                if($bannerhomepage->getId()){
//                    $bannerhomepage->addData($bannerhomepage_data->getData());
//                }else{
//                    $bannerhomepage->setData($bannerhomepage_data->getData());
//                }
//                $bannerhomepage->setCampaignId($model->getId());
//                $bannerhomepage->save();

                /**
                 * zeus save countdown data
                 */
                $countdown = $model->getCountdown();
                if(is_array($countdown_data->getData('showcountdown'))){
                    $locates = $countdown_data->getData('showcountdown');
                    $countdown_locate = implode(',', $locates);
                    $countdown_data->setShowcountdown($countdown_locate);
                }else{
                    $countdown_data->setShowcountdown('');
                }
                //save product selected to productId field
                if(is_array($countdown_data->getData('selected_products'))){
                    $productsSelected = $countdown_data->getData('selected_products');
                    if($productsSelected[0] == 'on' || $productsSelected[0] == 'no'){
                        array_shift($productsSelected);
                    }
                    $showcountdown = implode(',', $productsSelected);
                    $countdown_data->setProductId($showcountdown);
                }
                if($data['end_time'] != ''){
                    $endtime = $data['end_time'];
                    $endtimex = substr($endtime,8,10);
                    $epint = intval($endtimex);
                    $countdown_data->setDayCountdown($epint);
                }
                if($data['end_time'] != ''){
                    $endtimet = $data['end_time'];
                    $endtimexm = substr($endtimet,5,8);
                    $epintk = intval($endtimexm);
                    $countdown_data->setMonthCountdown($epintk);
                }
                if($countdown->getId()){
                    $countdown->addData($countdown_data->getData());
                }else{
                    $countdown->setData($countdown_data->getData());
                }
                $countdown->setCampaignId($model->getId());
                $countdown->save();


                /**
                 * footer controller
                 */
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('campaign')->__('Campaign was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('campaign')->__('Unable to find item to save')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete item action
     */
    public function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('campaign/campaign');
                $model->setId($this->getRequest()->getParam('id'))
                    ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Item was successfully deleted')
                );
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    /**
     * mass delete item(s) action
     */
    public function massDeleteAction()
    {
        $campaignIds = $this->getRequest()->getParam('campaign');
        if (!is_array($campaignIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($campaignIds as $campaignId) {
                    $campaign = Mage::getModel('campaign/campaign')->load($campaignId);
                    $campaign->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Total of %d record(s) were successfully deleted',
                    count($campaignIds))
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
    
    /**
     * mass change status for item(s) action
     */
    public function massStatusAction()
    {
        $campaignIds = $this->getRequest()->getParam('campaign');
        if (!is_array($campaignIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($campaignIds as $campaignId) {
                    Mage::getSingleton('campaign/campaign')
                        ->load($campaignId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($campaignIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    /*public function countdownGridAction(){
        Mage::register('reset_search_product', 1);
        $grid = $this->getLayout()->createBlock('campaign/adminhtml_campaign_edit_tab_countdown_grid')->toHtml();
        $this->getResponse()
            ->setBody($grid);
        return;
    }*/

    /**
     * export grid item to CSV type
     */
    public function exportCsvAction()
    {
        $fileName   = 'campaign.csv';
        $content    = $this->getLayout()
                           ->createBlock('campaign/adminhtml_campaign_grid')
                           ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export grid item to XML type
     */
    public function exportXmlAction()
    {
        $fileName   = 'campaign.xml';
        $content    = $this->getLayout()
                           ->createBlock('campaign/adminhtml_campaign_grid')
                           ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }


    //Tit banner ajax grid
    /*public function ajaxGridAction(){
        //get params
        Mage::register('widget_reloaded_ids', $this->getRequest()->getPost('widget_reloaded_ids'));
        $grid = $this->getLayout()->createBlock('campaign/adminhtml_banner_edit_tab_widgetgrid');
        $this->getResponse()->setBody($grid->toHtml());
        $this->renderLayout();
        $this->getResponse()->sendResponse();
        exit;
    }*/

    /**
     * get grid in campaign edit widget banner tab
     */
    public function getBannerGridTabAction(){
        //get params
        Mage::register('banner_reloaded_ids', $this->getRequest()->getPost('banner_reloaded_ids'));
        $grid = $this->getLayout()->createBlock('campaign/adminhtml_campaign_edit_tab_banner_grid');
        $serialer = Mage::getModel('core/layout')->createBlock('adminhtml/widget_grid_serializer');
        $serialer->initSerializerBlock($grid, 'getSerializeData', 'banner_ids', 'banner_reloaded_ids');
        $js = '<script type="text/javascript"></script>';
        $this->getResponse()->setBody($grid->toHtml().$serialer->toHtml());
        $this->renderLayout();
        $this->getResponse()->sendResponse();
        exit;
    }

    public function getBannerGridAjaxAction(){
        //get params
        Mage::register('filter', $this->getRequest()->getParam('filter'));
        Mage::register('banner_reloaded_ids', $this->getRequest()->getPost('banner_reloaded_ids'));
        $grid = $this->getLayout()->createBlock('campaign/adminhtml_campaign_edit_tab_banner_grid');
        $this->getResponse()->setBody($grid->toHtml());
        $this->renderLayout();
        $this->getResponse()->sendResponse();
        exit;
    }

    
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('campaign');
    }
}