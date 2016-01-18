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
class Magestore_Campaign_Model_StaticBlock extends Mage_Core_Model_Abstract
{
    protected $_cms_block; //Mage_Cms_Model_Block
    protected $_collection; //Mage_Cms_Model_Resource_Block_Collection
    protected $_stores; //array store - id
    /*public function _construct()
    {
        parent::_construct();
        $this->_init('campaign/staticBlock');
    }*/


    /**
     * prepare collection of static block
     *
     * @param string $identifier
     * @return $this
     */
    public function setIdentifier($identifier){
        if(!$this->_identifier){
            $this->_identifier = $identifier;
            $this->_cms_block;
            $collection = Mage::getModel('cms/block')->getCollection();
            $collection->join(array('store'=>$collection->getTable('cms/block_store')),
                $collection->getMainTable().'block_id = store.block_id',
                array($collection->getMainTable().'.*', 'store.store_id'))
                ->addFieldToFilter('identifier', $identifier)
            ;
            $this->_collection = $collection;
            $this->_stores = array();
            foreach($collection as $block){
                $this->_stores[$block->getStoreId()] = $block->getId();
            }
        }
        return $this;
    }

    /**
     * get Mage_Cms_Model_Block by store
     *
     * @param int $store_id
     * @return mixed
     */
    public function getModelByStore($store_id){
        $block_id = '';
        foreach($this->_stores as $_store_id => $id){
            if($_store_id == $store_id){
                $block_id = $id;
                break;
            }
        }
        return Mage::getModel('cms/block')->load($block_id);
    }

    public function getIdsAvailableStore(){
        $option = array();
        foreach($this->_stores as $_store_id => $id){
            $option[] = array(
                'store_id' => $_store_id,
                'block_id' => $id
            );
        }
        return $option;
    }

    public function getIdentifier(){
        return $this->_identifier;
    }
}

