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
class Magestore_Campaign_Model_Popup_Type_Game_Halloween extends Magestore_Campaign_Model_Popup_Type_Game
{
    const BLOCK_TYPE = 'campaign/popup_type_halloween';

    const TYPE_CODE = 'game_halloween';
    const PREFIX_DATA = 'game_halloween_'; //prefix in data array key

    const TEMPLATE_GROUP = 'game';
    const TEMPLATE_TYPE_STEP_1 = 'halloween_step_1';
    const TEMPLATE_TYPE_STEP_2 = 'halloween_step_2';
    const TEMPLATE_TYPE_STEP_3 = 'halloween_step_3';

    public function _construct()
    {
        parent::_construct();
        $this->setTypeCode(self::TYPE_CODE);
        $this->_init('campaign/popup_type_'.strtolower($this->getTypeCode()));
        $this->_helper = Mage::helper('campaign');
    }

    public function getBlockType(){
        return self::BLOCK_TYPE;
    }

    public function getPrefix(){
        return self::PREFIX_DATA;
    }

    /**
     * get html with processor cms block
     * @return mixed
     */
    public function toHtml(){
        return $this->getData('content_step_1');
    }

    /**
     * get html with processor cms block
     * @return mixed
     */
    public function getContentStep1Html(){
        return Mage::helper('campaign')->convertContentToHtml($this->getData('content_step_1'));
    }

    public function getContentStep2Html(){
        return Mage::helper('campaign')->convertContentToHtml($this->getData('content_step_2'));
    }

    public function getContentStep3Html(){
        return Mage::helper('campaign')->convertContentToHtml($this->getData('content_step_3'));
    }

    public function getPlayer(){
        $campaign = $this->getCampaign();
        $maillist = Mage::getModel('campaign/maillist')
            ->getMaillistByCampaign($campaign->getId());
        $player = Mage::getModel('campaign/popup_type_game_player')->getPlayerByMaillist($maillist->getId());
        if($player){
            return $player;
        }
        return Mage::getModel('campaign/popup_type_game_player');
    }

    /**
     * unused, get from campaign model
     * @return bool|string
     */
    public function getExpiredDate(){
        return date('Y-m-d H:i:s', strtotime('+2 days'));
    }

    /**
     * get character ids
     * @return array
     */
    public function getCharacters(){
        return array(1, 2, 3, 4, 5); //5 character
    }

    public function getCharacterName(){
        return array(1=>'Green door', 2=>'Orange door', 3=>'Blue door', 4=>'Purple door', 5=>'Black door');
    }

    /**
     * config points id and Probability
     * @return array ('points'=>'percent Probability')
     */
    public function getPointsProbability(){
        return array('5'=>55, '10'=>40, '30'=>5); //points available
    }

    public function getPointsQty(){
        return array('5'=>2, '10'=>2, '30'=>1); //5 character
    }

    /**
     * ramdom points and return character id
     * @return array ('char_id'=>'points', ... )
     */
    public function randomPoints($choose_id){
        $pointProb = $this->getPointsProbability();
        $id = $this->checkWithSet($pointProb);
        $rd_point = (int) $id;
        $pointsQty = $this->getPointsQty();
        $outsidePoints = array();
        $matched = false;
        foreach($pointsQty as $point => $qty){
            for($i=1; $i<=$qty; $i++){
                if($point == $rd_point && !$matched){
                    $matched = true;
                    continue;
                }
                $outsidePoints[] = $point;
            }
        }
        shuffle($outsidePoints); //random sorted
        $chars = $this->getCharacters();
        array_splice($chars, $choose_id-1, 1);//remove character id choose
        $mapped = array();
        foreach($chars as $i => $id){
            $mapped[$id] = (isset($outsidePoints[$i])) ? $outsidePoints[$i] : 0;
        }
        $mapped[$choose_id] = $rd_point;
        return $mapped; //('char_id'=>'points', ... )
    }

    /**
     * get random index of array
     * @param $set
     * @param int $length
     * @return bool|int|string
     */
    protected function checkWithSet($set, $length = 10000){
        $set_prob = array();
        $sum_set = 0;
        foreach($set as $id => $prob){
            $sum_set += $prob;
        }
        if($sum_set == 0) $sum_set = 1; //no zero
        $left = 0;
        foreach($set as $id => $prob){
            $set_prob[$id] = $left + $prob/$sum_set * $length;
            $left = $set_prob[$id];
        }
        $rand = mt_rand(1, $length);
        foreach($set_prob as $id => $right){
            if($rand <= $right){
                return $id;
            }
        }
        return false;
    }
}