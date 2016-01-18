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
 * Campaign Status Model
 * 
 * @category    Magestore
 * @package     Magestore_Campaign
 * @author      Magestore Developer
 */
class Magestore_Campaign_Model_Countdown_Type extends Varien_Object
{
    const NORMAL    = 1;
    const PRICE    = 2;
    
    /**
     * get model option as array
     *
     * @return array
     */
    static public function getOptionArray()
    {
        return array(
            self::NORMAL    => Mage::helper('campaign')->__('Normal'),
            self::PRICE   => Mage::helper('campaign')->__('Update price')
        );
    }
    
    /**
     * get model option hash as array
     *
     * @return array
     */
    static public function getOptionHash()
    {
        $options = array();
        foreach (self::getOptionArray() as $value => $label) {
            $options[] = array(
                'value'    => $value,
                'label'    => $label
            );
        }
        return $options;
    }


    public function getProducts()
    {
        $products = array();

        $collection = Mage::getModel('catalog/product')
            ->getCollection()
            ->addAttributeToSort('name', 'ASC');
        //zend_debug::dump($collection);
        foreach($collection as $product){
            $products[] = array('value'=>$product->getId() , 'label' =>$product->getName());
        }
        //die('nnn');
        return $products;
    }
}