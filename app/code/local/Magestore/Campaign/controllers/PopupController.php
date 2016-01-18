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
 * Campaign Index Controller
 *
 * @category    Magestore
 * @package     Magestore_Campaign
 * @author      Magestore Developer
 */
class Magestore_Campaign_PopupController extends Mage_Core_Controller_Front_Action
{
    /**
     * index action
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function startAction()
    {
        $data = $this->getRequest()->getParams();
        $result = array();
        if ($data['email']) {
            //$customer = Mage::helper('campaign')->findCustomerByEmail($data['email-customer']);
            $email = $data['email'];
            if(!isset($data['name']) || $data['name'] == ''){
                $name = explode('@', $data['email']);
                $name = $name[0];
            }else{
                $name = $data['name'];
            }
            if(isset($data['campaign_id']) && $data['campaign_id'] != ''){
                $campaign = Mage::getModel('campaign/campaign')->load($data['campaign_id']);
            }else{
                $campaign = Mage::getModel('campaign/campaign')->getActiveCampaign();
            }
            $maillist = Mage::getModel('campaign/maillist');
            $maillist->setCampaignId($campaign->getId());
            if ($maillist->checkUnique($email)) {
                //save email and auto generate coupon code to save to maillist
                if ($campaign->saveEmail($name, $email)) {
                    $result['check'] = true;
                    $result['html'] = $this->getLayout()
                        ->createBlock($campaign->getPopup()->getBlockType())
                        ->setCampaign($campaign) //is required for get coupon from block
                        ->setEmail($email) //is required for get coupon from block
                        ->getHtml(2);
                } else {
                    $result['check'] = false;
                    $result['msg'] = $email;
                }
            } else {
                $result['check'] = false;
                $result['msg'] = 'Email has been used. Try a new one!';
            }

        } else {
            $result['check'] = false;
            $result['msg'] = 'Data invalid';
        }
        $json = json_encode($result);
        $this->getResponse()
            ->clearHeaders()
            ->setHeader('Content-Type', 'application/json')
            ->setBody($json);
    }
}

