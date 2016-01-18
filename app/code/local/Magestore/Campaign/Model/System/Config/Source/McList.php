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
class Magestore_Campaign_Model_System_Config_Source_McList
{

    /**
     * Lists for API key will be stored here
     *
     * @access protected
     * @var array Email lists for given API key
     */
    protected $_lists   = null;

    /**
     * Load lists and store on class property
     *
     * @return void
     */
    public function __construct()
    {
        if(Mage::helper('core')->isModuleEnabled('Ebizmarts_MageMonkey')){
            if( is_null($this->_lists) ){
                $this->_lists = Mage::getSingleton('monkey/api')
                    ->lists();
            }
        }
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $lists = array();

        if(is_array($this->_lists)){
            $lists []= array('value' => '', 'label' => Mage::helper('campaign')->__('--- No selected ---'));
            foreach($this->_lists['data'] as $list){
                $lists []= array('value' => $list['id'], 'label' => $list['name'] . ' (' . $list['stats']['member_count'] . ' ' . Mage::helper('campaign')->__('members') . ')');
            }
        }else{
            $lists []= array('value' => '', 'label' => Mage::helper('campaign')->__('--- No data ---'));
        }

        return $lists;
    }
}

