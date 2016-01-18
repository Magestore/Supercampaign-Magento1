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
class Magestore_Campaign_Model_Giftcode extends Mage_Core_Model_Abstract
{
    const GIFT_CODE_TYPE_STATIC = 'static';
    const GIFT_CODE_TYPE_PROMOTION = 'promotion';

    public function _construct()
    {
        parent::_construct();
        $this->_init('campaign/giftcode');
    }

    /**
     * get all id of Shopping Cart Price Rule
     *
     * @param bool $check_expire true when get Rules without expire date
     * @return array
     */
    public function getShoppingCartPriceRuleSelectOption($check_expire = true){
        $option = array();
        $collection = Mage::getModel('salesrule/rule')->getCollection();
        $collection->addFieldToFilter('is_active', 1);
        if($check_expire){
            $collection->addFieldToFilter('to_date', array('gt' => Mage::getModel('core/date')->gmtDate()));
        }
        $collection->getSelect()
            ->columns(array(
                'id' => 'rule_id',
                'name' => 'name'
            ));
        foreach($collection as $salesRule){
            $option[] = array(
                'label' => $salesRule->getName(),
                'value' => $salesRule->getId()
            );
        }
        return $option;
    }
}

