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
class Magestore_Campaign_CookieController extends Mage_Core_Controller_Front_Action
{

    /**
     * cookie_life_time is value for cookie life time
     */
    public function setAction()
    {
        //$dataPost = $this->getRequest()->getPost();
        $dataPost = $this->getRequest()->getParams();
        $popup = Mage::getModel('campaign/popup');
        if(isset($dataPost['popup_id'])){
            $popup->load($dataPost['popup_id']);
        }
        //set variable cookies
        $cookie = Mage::getModel('core/cookie');
        foreach ($dataPost as $cookieVarName => $cookieVal) {
            if($popup->getId()){
                $cookieLifeTime = ($popup->getData('cookie_time') != '')?$popup->getData('cookie_time'):true;
                $cookie->set($cookieVarName, $cookieVal, $cookieLifeTime);
            }elseif(isset($dataPost['cookie_life_time'])){
                $cookie->set($cookieVarName, $cookieVal, $dataPost['cookie_life_time']);
            }else{
                $cookie->set($cookieVarName, $cookieVal, true);
            }
        }
        $json = json_encode($cookie->get());
        $this->getResponse()
            ->clearHeaders()
            ->setHeader('Content-Type', 'application/json')
            ->setBody($json);
    }

}

