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
        //skip old data popup when load template from old popup editing
        $editIdNewFromTemplate = Mage::getSingleton('adminhtml/session')->getPopupIdNewFromTemplate();
        if(isset($editIdNewFromTemplate)){
            $id = $editIdNewFromTemplate;
        }
        //end

        $model  = Mage::getModel('campaign/popup')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);

            //skip old data popup when load template from old popup editing
            if (!empty($data)){
                if(isset($editIdNewFromTemplate)){
                    $model->addData($data);
                }else{
                    $model->setData($data);
                }
            }
            //end


            Mage::register('popup_data', $model);
            Mage::getSingleton('adminhtml/session')->setPopupEditId($id);


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

    public function chooserMainCategoriesAction() {
        $request = $this->getRequest();
        $ids = $request->getParam('selected', array());

        if (is_array($ids)) {
            foreach ($ids as $key => &$id) {
                $id = (int) $id;
                if ($id <= 0) {
                    unset($ids[$key]);
                }
            }

            $ids = array_unique($ids);
        } else {
            $ids = array();
        }

        $block = $this->getLayout()->createBlock('campaign/adminhtml_popup_edit_tab_content_maincontent_categories','maincontent_category', array('js_form_object' => $request->getParam('form')))
            ->setCategoryIds($ids)
        ;

        if ($block) {
            $this->getResponse()->setBody($block->toHtml());
        }
    }

    /**
     * Initialize category object in registry
     *
     * @return Mage_Catalog_Model_Category
     */
    protected function _initCategory() {
        $categoryId = (int) $this->getRequest()->getParam('id', false);
        $storeId = (int) $this->getRequest()->getParam('store');

        $category = Mage::getModel('catalog/category');
        $category->setStoreId($storeId);

        if ($categoryId) {
            $category->load($categoryId);
            if ($storeId) {
                $rootId = Mage::app()->getStore($storeId)->getRootCategoryId();
                if (!in_array($rootId, $category->getPathIds())) {
                    $this->_redirect('*/*/', array('_current' => true, 'id' => null));
                    return false;
                }
            }
        }

        Mage::register('category', $category);
        Mage::register('current_category', $category);

        return $category;
    }

    /**
     * Get tree node (Ajax version)
     */
    public function categoriesJsonAction() {
        if ($categoryId = (int) $this->getRequest()->getPost('id')) {
            $this->getRequest()->setParam('id', $categoryId);

            if (!$category = $this->_initCategory()) {
                return;
            }
            $this->getResponse()->setBody(
                $this->getLayout()->createBlock('adminhtml/catalog_category_tree')
                    ->getTreeJson($category)
            );
        }
    }

    public function chooserPopupsAction() {
        $request = $this->getRequest();
        $block = $this->getLayout()->createBlock(
            'campaign/adminhtml_popup_edit_tab_information_selector_popups',
            'information_selector_popups',
            array('input' =>'trigger_popup','grid_url_call'=>'chooserPopups','id'=>'popupGrid',
                'js_form_object' => $request->getParam('form'),
        ));

        if ($block) {
            $this->getResponse()->setBody($block->toHtml());
        }
    }


    public function saveAction() {
        if ($data = $this->getRequest()->getPost()) {

            //save store
            if(isset($data['store']) && is_array($data['store'])){
                $store = implode(',', $data['store']);
                $data['store'] = $store.','; //fix store view in grid
            }

            //save login user
            if(isset($data['login_user']) && is_array($data['login_user'])){
                $store = implode(',', $data['login_user']);
                $data['login_user'] = $store.',';
            }

            //save customer group
            if(isset($data['customer_group_ids']) && is_array($data['customer_group_ids'])){
                $store = implode(',', $data['customer_group_ids']);
                $data['customer_group_ids'] = $store.',';
            }

            //save devices
            if(isset($data['devices']) && is_array($data['devices'])){
                $store = implode(',', $data['devices']);
                $data['devices'] = $store.',';
            }

            $id = $this->getRequest()->getParam('id');
            //skip old data popup when load template from old popup editing
            //get session and clear with true option
            $editIdNewFromTemplate = Mage::getSingleton('adminhtml/session')->getPopupIdNewFromTemplate(true);
            if(isset($editIdNewFromTemplate)){
                $id = $editIdNewFromTemplate;
            }
            //end
            $model = Mage::getModel('campaign/popup');
            $model->setData($data)
                ->setId($id);

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
        $popupIds = $this->getRequest()->getParam('popup');
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
        $popupIds = $this->getRequest()->getParam('popup');
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

    /*
     * popup load template
     */
    public function loadTemplateAction(){
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * load template data
     */
    public function newFromTemplateAction(){
        $templateId = $this->getRequest()->getParam('template_id');
        $template = Mage::getModel('campaign/template')->load($templateId);
        if($template->getId()){
            $data = $template->getData();
            $data['popup_content'] = $template->getTemplateContentHtml();
            $data['content_for_success'] = $template->getContentSuccessHtml();
            //skip old data popup when load template from old popup editing
            $popupEditId = Mage::getSingleton('adminhtml/session')->getPopupEditId();
            if(isset($popupEditId)){
                unset($data['title']);//skip replace title in old popup
            }
            Mage::getSingleton('adminhtml/session')->setPopupIdNewFromTemplate(
                Mage::getSingleton('adminhtml/session')->getPopupEditId(true));
            //end
            Mage::getSingleton('adminhtml/session')->setFormData($data);

        }else{
            Mage::getSingleton('adminhtml/session')->addError('Can\'t load template.');
        }
        $this->_forward('edit');
    }
}