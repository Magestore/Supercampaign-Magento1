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
 * Campaign Model
 * 
 * @category    Magestore
 * @package     Magestore_Campaign
 * @author      Magestore Developer
 */
class Magestore_Campaign_Model_Mailchimp extends Mage_Core_Model_Abstract
{

    public function _construct()
    {
        parent::_construct();
        //$this->_init('campaign/mailchimp');
        if($this->canMailchimp()){
            $this->_api = Mage::getSingleton('monkey/api'); //api model
        }
        $this->_listId = Mage::getStoreConfig('campaign/general/mc_list');
    }

    public function getLists(){
        return Mage::getModel('campaign/system_config_source_mcList')->toOptionArray();
    }

    /**
     * add new email to mailchimp list
     * @param $email
     * @param $vars all data
     * @param string $email_type change the email type preference for the member ("html", "text", or "mobile").
     * @param bool $confirm
     * @return mixed
     * @throws Exception
     */
    public function addMember($email, $vars=array(), $email_type='html', $confirm = false){
        if(!$this->canMailchimp()){
            return false;
        }
        if(!is_object($this->_api)){
            throw new Exception('Api source model undefined');
        }
        $result = $this->_api->listSubscribe($this->_listId, $email, $vars, $email_type, $confirm);
        return $result;
    }

    /**
     * @param $email of member in list to update
     * @param array $vars
     * @param string $email_type change the email type preference for the member ("html", "text", or "mobile").
     * @return mixed
     * @throws Exception
     */
    public function updateMember($email, $vars=array(), $email_type='html'){
        if(!$this->canMailchimp()){
            return false;
        }
        if(!is_object($this->_api)){
            throw new Exception('Api source model undefined');
        }
        $result = $this->_api->listUpdateMember($this->_listId, $email, $vars, $email_type);
        return $result;
    }

    protected function canMailchimp(){
        if($this->_canMailchimp === null){
            if(Mage::helper('core')->isModuleEnabled('Ebizmarts_MageMonkey')){
                if((int)Mage::getStoreConfig('monkey/general/active') == 1){
                    $this->_canMailchimp = true;
                    return true;
                }
            }
            $this->_canMailchimp = false;
        }
        return $this->_canMailchimp;
    }
}

