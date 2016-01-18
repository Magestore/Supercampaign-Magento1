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
class Magestore_Campaign_Adminhtml_ResettemplateController extends Mage_Adminhtml_Controller_Action
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
            $template = Mage::getModel('campaign/template')->load($request->getTemplateId());
            //if($campaign->getId()){
                if($template->getId()){
                    $json = json_encode(array('result'=>'success', 'status'=>'success', 'content'=>$template->getContent()));
                    $this->getResponse()
                        ->clearHeaders()
                        ->setHeader('Content-Type', 'application/json')
                        ->setBody($json);
                    return;
                }else{
                    throw new Exception('Can not find template item');
                }
            //}
            //throw new Exception('Can not find campaign item');
        }catch (Exception $e){
            $json = json_encode(array('result'=>$e->getMessage(), 'status'=>'error', 'content'=>''));
            $this->getResponse()
                ->clearHeaders()
                ->setHeader('Content-Type', 'application/json')
                ->setBody($json);
        }
        exit;
    }

    public function headertextAction(){
        try{
            $params = $this->getRequest()->getParams();
            $request = new Varien_Object($params);
            //$campaign = Mage::getModel('campaign/campaign')->load($request->getCampaignId());
            $template = Mage::getModel('campaign/template')->load($request->getTemplateId());
            //if($campaign->getId()){
                if($template->getId()){
                    $json = json_encode(array('result'=>'success', 'status'=>'success', 'content'=>$template->getContent()));
                    $this->getResponse()
                        ->clearHeaders()
                        ->setHeader('Content-Type', 'application/json')
                        ->setBody($json);
                    return;
                }else{
                    throw new Exception('Can not find template item');
                }
            //}
            //throw new Exception('Can not find campaign item');
        }catch (Exception $e){
            $json = json_encode(array('result'=>$e->getMessage(), 'status'=>'error', 'content'=>''));
            $this->getResponse()
                ->clearHeaders()
                ->setHeader('Content-Type', 'application/json')
                ->setBody($json);
        }
        exit;
    }

    public function sidebarAction(){
        try{
            $params = $this->getRequest()->getParams();
            $request = new Varien_Object($params);
            //$campaign = Mage::getModel('campaign/campaign')->load($request->getCampaignId());
            $template = Mage::getModel('campaign/template')->load($request->getTemplateId());
            //if($campaign->getId()){
                //$sidebar = $campaign->getSidebar()
                //    ->setTemplateId($request->getTemplateId());
                //$template = $sidebar->getTemplate();
                if($template->getId()){
                    $json = json_encode(array('result'=>'success', 'status'=>'success', 'content'=>$template->getContent()));
                    $this->getResponse()
                        ->clearHeaders()
                        ->setHeader('Content-Type', 'application/json')
                        ->setBody($json);
                    return;
                }else{
                    throw new Exception('Can not find template item');
                }
            //}
            //throw new Exception('Can not find campaign item');
        }catch (Exception $e){
            $json = json_encode(array('result'=>$e->getMessage(), 'status'=>'error', 'content'=>''));
            $this->getResponse()
                ->clearHeaders()
                ->setHeader('Content-Type', 'application/json')
                ->setBody($json);
        }

        exit;
    }

    /**
     * reset popup template
     */
    public function popupAction()
    {
        try{
            $params = $this->getRequest()->getParams();
            $request = new Varien_Object($params);
            //$campaign = Mage::getModel('campaign/campaign')->load($request->getCampaignId());
            $template = Mage::getModel('campaign/popup_template')->load($request->getTemplateId());
            //if($campaign->getId()){
                if($template->getId()){
                    $json = json_encode(array('result'=>'success', 'status'=>'success', 'content'=>$template->getContent()));
                    $this->getResponse()
                        ->clearHeaders()
                        ->setHeader('Content-Type', 'application/json')
                        ->setBody($json);
                    return;
                }else{
                    throw new Exception('Can not find template item');
                }
            //}
            //throw new Exception('Can not find campaign item');
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