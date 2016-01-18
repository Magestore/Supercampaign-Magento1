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
class Magestore_Campaign_Adminhtml_LoadtemplateController extends Mage_Adminhtml_Controller_Action
{
    /**
     * get ajax content and reset text/html in editor
     * index action
     */
    public function indexAction()
    {
        try{
            $params = $this->getRequest()->getParams();
            $request = new Varien_Object($params);
            //$campaign = Mage::getModel('campaign/campaign')->load($request->getCampaignId());
            $content = Mage::getModel('campaign/template')->getContent($request->getTemplateId());
            $json = json_encode(array('result'=>'success', 'status'=>'success', 'content'=>$content));
            $this->getResponse()
                ->clearHeaders()
                ->setHeader('Content-Type', 'application/json')
                ->setBody($json);
            return;
        }catch (Exception $e){
            $json = json_encode(array('result'=>$e->getMessage(), 'status'=>'error', 'content'=>''));
            $this->getResponse()
                ->clearHeaders()
                ->setHeader('Content-Type', 'application/json')
                ->setBody($json);
        }
        exit;
    }

    
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('campaign');
    }



}