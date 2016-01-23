<?php

class Magestore_Campaign_Adminhtml_PopupController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction(){
        $this->loadLayout()
            ->_setActiveMenu('campaign/popup')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
        return $this;
    }

    public function indexAction(){
        $this->_initAction()
            ->renderLayout();
    }

    public function editAction() {
        $id	 = $this->getRequest()->getParam('id');
        $model  = Mage::getModel('campaign/popup')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data))
                $model->setData($data);

            Mage::register('popup_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('campaign/popup');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('campaign/adminhtml_popup_edit'))
                ->_addLeft($this->getLayout()->createBlock('campaign/adminhtml_popup_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('campaign')->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function chooserMainProductsAction() {
        $request = $this->getRequest();
        $block = $this->getLayout()->createBlock(
            'campaign/adminhtml_popup_edit_tab_content_maincontent_grid', 'promo_widget_chooser_sku', array('input' =>'products','grid_url_call'=>'chooserMainProducts','id'=>'productGrid','js_form_object' => $request->getParam('form'),
        ));

        if ($block) {
            $this->getResponse()->setBody($block->toHtml());
        }
    }

    public function saveAction() {
        if ($data = $this->getRequest()->getPost()) {
            //zend_debug::dump($data); die('ccc');

            $login_user = $data['login-user'];

            $model = Mage::getModel('campaign/popup');
            $model->setData($data)
                ->setId($this->getRequest()->getParam('id'));

            try {
                if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL)
                    $model->setCreatedTime(now())
                        ->setUpdateTime(now());
                else
                    $model->setUpdateTime(now());

                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('campaign')->__('Item was successfully saved'));
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
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('campaign')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction() {
        if( $this->getRequest()->getParam('id') > 0 ) {
            try {
                $model = Mage::getModel('campaign/popup');
                $model->setId($this->getRequest()->getParam('id'))
                    ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {
        $popupIds = $this->getRequest()->getParam('campaign');
        if(!is_array($popupIds)){
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        }else{
            try {
                foreach ($popupIds as $popupId) {
                    $popup = Mage::getModel('campaign/popup')->load($popupId);
                    $popup->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Total of %d record(s) were successfully deleted', count($popupIds)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction() {
        $popupIds = $this->getRequest()->getParam('campaign');
        if(!is_array($popupIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($popupIds as $popupId) {
                    $popup = Mage::getSingleton('campaign/popup')
                        ->load($popupId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($popupIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function exportCsvAction(){
        $fileName   = 'popup.csv';
        $content	= $this->getLayout()->createBlock('campaign/adminhtml_popup_grid')->getCsv();
        $this->_prepareDownloadResponse($fileName,$content);
    }

    public function exportXmlAction(){
        $fileName   = 'popup.xml';
        $content	= $this->getLayout()->createBlock('campaign/adminhtml_popup_grid')->getXml();
        $this->_prepareDownloadResponse($fileName,$content);
    }
}