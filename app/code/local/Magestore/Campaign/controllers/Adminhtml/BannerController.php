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
class Magestore_Campaign_Adminhtml_BannerController extends Mage_Adminhtml_Controller_Action
{
    /**
     * init layout and set active for current menu
     *
     * @return Magestore_Campaign_Adminhtml_CampaignController
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('campaign/banner')
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
        $widgetBlock = $this->getLayout()->createBlock('campaign/adminhtml_banner');
        $this->_initAction()
            ->_addContent($widgetBlock)
            ->renderLayout();
    }

    /**
     * view and edit item action
     */
    public function editAction()
    {
        $widgetId     = $this->getRequest()->getParam('id');
        $model  = Mage::getModel('campaign/widget_banner')->load($widgetId);
        if ($model->getId() || $widgetId == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
            Mage::register('widget_banner_data', $model);
            /**
             * footer controller
             */
            $this->loadLayout();
            $this->_setActiveMenu('campaign/banner_new');

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('campaign/adminhtml_banner_edit'))
                ->_addLeft($this->getLayout()->createBlock('campaign/adminhtml_banner_edit_tabs'));
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('campaign')->__('Widget does not exist')
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
            if(isset($data[Magestore_Campaign_Model_Widget_Banner::PREFIX_DATA])){
                $data = $data[Magestore_Campaign_Model_Widget_Banner::PREFIX_DATA];
            }
            $model = Mage::getModel('campaign/widget_banner');
            $id = $this->getRequest()->getParam('id');
            if($id){
                $model->load($id);
                $model->addData($data)->setId($id);
            }else{
                $model->setData($data);
            }
            try {
                $model->save();
                /**
                 * footer controller
                 */
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('campaign')->__('Banner was successfully saved')
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
        $massIds = $this->getRequest()->getParam('mass_ids');
        if (!is_array($massIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($massIds as $id) {
                    $campaign = Mage::getModel('campaign/widget_banner')->load($id);
                    $campaign->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Total of %d record(s) were successfully deleted',
                    count($massIds))
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
        $massIds = $this->getRequest()->getParam('mass_ids');
        if (!is_array($massIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($massIds as $id) {
                    Mage::getSingleton('campaign/widget_banner')
                        ->load($id)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($massIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }


    /**
     * export grid item to CSV type
     */
    public function exportCsvAction()
    {
        $fileName   = 'widget.csv';
        $content    = $this->getLayout()
           ->createBlock('campaign/adminhtml_banner_grid')
           ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export grid item to XML type
     */
    public function exportXmlAction()
    {
        $fileName   = 'widget.xml';
        $content    = $this->getLayout()
           ->createBlock('campaign/adminhtml_banner_grid')
           ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('banner');
    }

    public function ajaxGridAction(){
        //get params
        Mage::register('widget_reloaded_ids', $this->getRequest()->getPost('widget_reloaded_ids'));
        $grid = $this->getLayout()->createBlock('campaign/adminhtml_banner_edit_tab_widgetgrid');
        $this->getResponse()->setBody($grid->toHtml());
        $this->renderLayout();
        $this->getResponse()->sendResponse();
        exit;
    }

    /**
     * get grid in campaign edit widget banner tab
     */
    public function getGridTabAction(){
        //get params
        Mage::register('banner_reloaded_ids', $this->getRequest()->getPost('banner_reloaded_ids'));
        $grid = $this->getLayout()->createBlock('campaign/adminhtml_campaign_edit_tab_bannergrid');
        $serialer = Mage::getModel('core/layout')->createBlock('adminhtml/widget_grid_serializer');
        $serialer->initSerializerBlock($grid, 'getSerializeData', 'banner_ids', 'banner_reloaded_ids');
        $js = '<script type="text/javascript"></script>';
        $this->getResponse()->setBody($grid->toHtml().$serialer->toHtml());
        $this->renderLayout();
        $this->getResponse()->sendResponse();
        exit;
    }

    public function getBannerGridAction(){
        //get params
        Mage::register('filter', $this->getRequest()->getParam('filter'));
        Mage::register('banner_reloaded_ids', $this->getRequest()->getPost('banner_reloaded_ids'));
        $grid = $this->getLayout()->createBlock('campaign/adminhtml_campaign_edit_tab_bannergrid');
        $this->getResponse()->setBody($grid->toHtml());
        $this->renderLayout();
        $this->getResponse()->sendResponse();
        exit;
    }
}