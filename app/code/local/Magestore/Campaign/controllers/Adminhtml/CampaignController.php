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
//            $model->setStartTime($model->toLocaleTimezone($model->getStartTime()));
//            $model->setEndTime($model->toLocaleTimezone($model->getEndTime()));
            $model->setStartTime($model->getStartTime());
            $model->setEndTime($model->getEndTime());

            Mage::register('campaign_data', $model);
            Mage::getSingleton('adminhtml/session')->setCurrentCampaign($model);
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
     * show cowdown with products
     */
    public function chooserMainProductsAction() {
        $request = $this->getRequest();
        $block = $this->getLayout()->createBlock(
            'campaign/adminhtml_campaign_edit_tab_content_maincontent_grid', 'campaign_chooser_sku', array('input' =>'generalcountdown_products','grid_url_call'=>'chooserMainProducts','id'=>'productGrid','js_form_object' => $request->getParam('form'),
        ));

        if ($block) {
            $this->getResponse()->setBody($block->toHtml());
        }
    }
 
    /**
     * save item action
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            /* save campaign model */
            $model = Mage::getModel('campaign/campaign');

            //save store
//            if(isset($data['stores']) && is_array($data['stores'])){
//                $store = implode(',', $data['stores']);
//                $data['store'] = $store.','; //fix store view in grid
//            }
            $campaignData = new Varien_Object($data['general']);
            //save login user
            //$logUsr = implode(',', $campaignData->getData('login_user'));
            //$campaignData->setData('login_user', $logUsr.',');

            //save customer group
            $gIds = implode(',', $campaignData->getData('customer_group_ids'));
            $campaignData->setData('customer_group_ids', $gIds.',');

            //save devices
            $div = implode(',', $campaignData->getData('devices'));
            $campaignData->setData('devices', $div.',');


            $model->setData($campaignData->getData())
                ->setId($this->getRequest()->getParam('id'));
            /*set start date, end date to UTC timezone*/
            $model->setStartTime($model->toUTCTimezone($campaignData->getStartTime()));
            $model->setEndTime($model->toUTCTimezone($campaignData->getEndTime()));

            try {

                $model->save();

                /*get all added popups to this campaign*/
                /*saving campaign_id top popups*/
                if(isset($data['popup_ids'])){
                    $popupIds = explode('&', $data['popup_ids']);
                    $popups = Mage::getModel('campaign/popup')->getCollection();
                    $popups->addFieldToFilter(array('campaign_id', 'popup_id'),
                        array($model->getId(), array('in'=>$popupIds)));
                    foreach($popups as $popup){
                        if(in_array($popup->getId(), $popupIds)){
                            $popup->setCampaignId($model->getId());//set campaign id
                        }else{
                            $popup->setCampaignId('');//set no campaign id (delete old)
                        }
                        $popup->save();
                    }
                }

                /*get all added banner to this campaign*/
                /*saving banner campaign_id*/
                if(isset($data['banner_ids'])){
                    $banner_ids = explode('&', $data['banner_ids']);
                    $banners = Mage::getModel('campaign/bannerslider')->getCollection();
                    $banners->addFieldToFilter(array('campaign_id', 'bannerslider_id'),
                        array($model->getId(), array('in'=>$banner_ids)));
                    foreach($banners as $banner){
                        if(in_array($banner->getId(), $banner_ids)){
                            $banner->setCampaignId($model->getId());//set campaign id to banners
                            $banner->setStartTime($campaignData->getStartTime());
                            $banner->setEndTime($campaignData->getEndTime());
                            $sub_banner = Mage::getModel('campaign/banner')->getCollection();
                            $sub_banner->addFieldToFilter(array('banner_id', 'bannerslider_id'),
                                array($banner->getId(), array('in'=>$banner_ids)));
                            //set banner item limit time
                            foreach($sub_banner as $subbn){
                                if(in_array($subbn->getBannersliderId(), $banner_ids)){
                                    $subbn->setStartTime($campaignData->getStartTime());
                                    $subbn->setEndTime($campaignData->getEndTime());
                                    $subbn->save();
                                }
                            }
                        }else{
                            $banner->setCampaignId('');//set no campaign id to banners
                        }
                        $banner->save();
                    }
                }

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
    public function getPopupGridTabAction(){
        //get params
        Mage::register('popup_reloaded_ids', $this->getRequest()->getPost('popup_reloaded_ids'));
        $grid = $this->getLayout()->createBlock('campaign/adminhtml_campaign_edit_tab_popup_grid');
        $serialer = Mage::getModel('core/layout')->createBlock('adminhtml/widget_grid_serializer');
        $serialer->initSerializerBlock($grid, 'getSerializeData', 'popup_ids', 'popup_reloaded_ids');
        $this->getResponse()->setBody($grid->toHtml().$serialer->toHtml());
        $this->renderLayout();
        $this->getResponse()->sendResponse();
        exit;
    }

    public function getPopupGridAjaxAction(){
        //get params
        Mage::register('filter', $this->getRequest()->getParam('filter'));
        Mage::register('popup_reloaded_ids', $this->getRequest()->getPost('popup_reloaded_ids'));
        $grid = $this->getLayout()->createBlock('campaign/adminhtml_campaign_edit_tab_popup_grid');
        $this->getResponse()->setBody($grid->toHtml());
        $this->renderLayout();
        $this->getResponse()->sendResponse();
        exit;
    }

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