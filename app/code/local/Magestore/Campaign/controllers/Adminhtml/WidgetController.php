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
class Magestore_Campaign_Adminhtml_WidgetController extends Mage_Adminhtml_Controller_Action
{
    /**
     * init layout and set active for current menu
     *
     * @return Magestore_Campaign_Adminhtml_CampaignController
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('campaign/widget')
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
        $widgetBlock = $this->getLayout()->createBlock('campaign/adminhtml_widget');
        $this->_initAction()
            ->_addContent($widgetBlock)
            ->renderLayout();
    }

    public function detailAction()
    {
        $widgetId     = $this->getRequest()->getParam('id');
        $model  = Mage::getModel('campaign/widget')->load($widgetId);
        if ($model->getId() || $widgetId == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
            Mage::register('widget_data', $model);

            $detailBlock = $this->getLayout()->createBlock('campaign/adminhtml_widget_detail');
            $editBlock = $this->getLayout()->createBlock('campaign/adminhtml_widget_detail_edit');

            $this->_title($this->__('Widget'))
                ->_title($this->__('Detail'))
                ->_title($detailBlock->getHeaderText());

            /**
             * footer controller
             */
            $this->_initAction();
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_setActiveMenu('campaign/widget_detail')
                ->_addBreadcrumb(Mage::helper('campaign')->__('Widget detail'),
                    Mage::helper('campaign')->__('Widget detail'))
                ->_addContent($editBlock)
                ->_addContent($detailBlock)
                ->renderLayout();

        } else {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('campaign')->__('Widget does not exist')
            );
            $this->_redirect('*/*/');
        }
    }

    /**
     * view and edit item action
     */
    public function editAction()
    {
        $widgetId     = $this->getRequest()->getParam('id');
        $model  = Mage::getModel('campaign/widget')->load($widgetId);
        if ($model->getId() || $widgetId == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('widget_data', $model);

            /**
             * footer controller
             */
            $this->loadLayout();
            $this->_setActiveMenu('campaign/widget_new');

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('campaign/adminhtml_widget_edit'))
                ->_addLeft($this->getLayout()->createBlock('campaign/adminhtml_widget_edit_tabs'));
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
            $widgetData = new Varien_Object($data);
            /* save campaign model */

            $model = Mage::getModel('campaign/widget');
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
                    Mage::helper('campaign')->__('Widget was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/detail', array('id' => $model->getId()));
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
                    $campaign = Mage::getModel('campaign/widget')->load($id);
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
                    Mage::getSingleton('campaign/widget')
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
           ->createBlock('campaign/adminhtml_widget_grid')
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
           ->createBlock('campaign/adminhtml_widget_grid')
           ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('widget');
    }

    public function ajaxGridAction(){
        //get params
        Mage::register('widget_selected_ids', $this->getRequest()->getPost('widget_reloaded_ids'));
        $grid = $this->getLayout()->createBlock('campaign/adminhtml_campaign_edit_tab_widgetBanner_grid');
        $this->getResponse()->setBody($grid->toHtml());
        $this->renderLayout();
        $this->getResponse()->sendResponse();
        exit;
    }
}