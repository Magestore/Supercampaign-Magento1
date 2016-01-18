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
class Magestore_Campaign_Model_Popup_Type_Game extends Magestore_Campaign_Model_Popup_Abstract
{
    const BLOCK_TYPE = 'campaign/popup_type_halloween'; //declare block that used as your block custom type
    const TYPE_CODE = 'game';

    public function _construct()
    {
        parent::_construct();
        $this->setTypeCode(self::TYPE_CODE);
        $this->_init('campaign/popup_type_'.strtolower($this->getTypeCode()));
    }

    public function getBlockType(){
        return self::BLOCK_TYPE;
    }
}