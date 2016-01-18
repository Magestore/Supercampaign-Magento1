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
class Magestore_Campaign_Game_HalloweenController extends Mage_Core_Controller_Front_Action
{
    /**
     * index action
     */
    public function indexAction()
    {
        return '';
        zend_debug::Dump(Mage::getModel('campaign/popup_type_game_halloween')->randomPoints(5));
    }

    public function startAction()
    {
        $data = $this->getRequest()->getParams();
        $result = array();
        if (isset($data['charId']) && $data['charId']) {
            try{
                if(isset($data['campaign_id']) && $data['campaign_id'] != ''){
                    $campaign = Mage::getModel('campaign/campaign')->load($data['campaign_id']);
                }else{
                    $campaign = Mage::getModel('campaign/campaign')->getActiveCampaign();
                }
                //random points
                $points = Mage::getModel('campaign/popup_type_game_halloween')
                    ->randomPoints($data['charId']);
                Mage::getSingleton('customer/session')->setPointsCampaignGame($points);
                Mage::getSingleton('customer/session')->setCharIdCampaignGame($data['charId']);
                $result['html'] = $this->getLayout()
                    ->createBlock($campaign->getPopup()->getBlockType())
                    ->setCampaign($campaign) //is required for get coupon from block
                    ->getHtml(2);
                $result['check'] = true;
                $result['msg'] = 'success';
            }
            catch(Exception $e){
                $result['html'] = '';
                $result['check'] = false;
                $result['msg'] = $e->getMessage();
            }
        } else {
            $result['check'] = false;
            $result['msg'] = 'charId param is invalid';
        }
        $json = json_encode($result);
        $this->getResponse()
            ->clearHeaders()
            ->setHeader('Content-Type', 'application/json')
            ->setBody($json);
    }

    public function resultAction()
    {
        $data = $this->getRequest()->getParams();
        $result = array();
        if (isset($data['email']) && $data['email']) {
            $email = $data['email'];
            if(!isset($data['name']) || $data['name'] == ''){
                $name = explode('@', $data['email']);
                $name = $name[0];
            }else{
                $name = $data['name'];
            }
            try{
                if(isset($data['campaign_id']) && $data['campaign_id'] != ''){
                    $campaign = Mage::getModel('campaign/campaign')->load($data['campaign_id']);
                }else{
                    $campaign = Mage::getModel('campaign/campaign')->getActiveCampaign();
                }

                $maillist = Mage::getModel('campaign/maillist');
                $maillist->setCampaignId($campaign->getId());
                if ($maillist->checkUnique($email)) {
                    //save email and auto generate coupon code to save to maillist
                    $points = Mage::getSingleton('customer/session')->getPointsCampaignGame();
                    $charId = Mage::getSingleton('customer/session')->getCharIdCampaignGame();
                    $modelType = $campaign->getPopup()->getModelType();
                    $player = $modelType->getPlayer();
                    $charNames = $modelType->getCharacterName();
                    //create player
                    $expired_date = $campaign->toUTCTimezone('2015-11-02 23:59:00');
                    $player->setData(array(
                        'email' => $email,
                        'name'  => $name,
                        'points'=> $points[$charId],
                        'campaign_id' => $campaign->getId(),
                        'character_name' => $charNames[$charId],
                        'expired_date' => $expired_date//$campaign->getData('end_time')
                    ));
                    if (!$player->saveEmail($email, $name)) {
                        throw new Exception('Can not save email');
                    }
                    //create new customer when customer not exist
                    $customer = $player->findCustomerByEmail($email);
                    if ($customer == null) {
                        //create new customer
                        $name = explode('@', $data['email']);
                        $customer = $player->createCustomer($email, $name);
                        $customer->setIsNewAccount(true);
                        //for auto login
                        Mage::getSingleton('customer/session')->setCustomer($customer);
                    }else{
                        $customer->setIsNewAccount(false);
                    }
                    if(Mage::helper('core')->isModuleEnabled('Magestore_RewardPoints')){
                        // + point to customer
                        Mage::helper('rewardpoints/action')->addTransaction('halloween_game', $customer, $player);
                        // end + point
                        $player->sendEmail($customer); //send email after add points complete
                    }
                    $player->save(); //save player
                    $result['charId'] = $charId;
                    $result['points'] = $points;
                    $result['html'] = $this->getLayout()
                        ->createBlock($campaign->getPopup()->getBlockType())
                        ->setCampaign($campaign) //is required for get coupon from block
                        ->setEmail($email) //is required for get coupon from block
                        ->getHtml(3);
                    $result['check'] = true;
                    $result['msg'] = 'success';
                } else {
                    throw new Exception('Email has been used. Try a new one!');
                }
            }
            catch(Exception $e){
                $result['html'] = '';
                $result['check'] = false;
                $result['msg'] = $e->getMessage();
            }
        } else {
            $result['check'] = false;
            $result['msg'] = 'charId param is invalid';
        }
        $json = json_encode($result);
        $this->getResponse()
            ->clearHeaders()
            ->setHeader('Content-Type', 'application/json')
            ->setBody($json);
    }
}

