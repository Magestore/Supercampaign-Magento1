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
class Magestore_Campaign_CountdownController extends Mage_Core_Controller_Front_Action
{
    /**
     * index action
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function showdonaddcartAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function showheaderAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function setupdatepriceAction(){
        //die('het roi');
        $productsales = $this->getProductcd();
        //zend_debug::dump($productsales); die('aaa');
        $platinum = Mage::getModel('catalog/product')->setStoreId($this->getRequest()->getParam('store', 0))->load($id);
        $old = $platinum->getPrice();
        if($old < 399){
            $platinum->setPrice($old + 3);
            try{
                foreach ($platinum->getOptions() as $_option) {
                    $values = $_option->getValues();
                    foreach ($values as $v) {
                        print_r($v->getTitle());
                        echo "<br />";
                    }
                }
                //$platinum->save();
            }catch(Exception $e){
                //Zend_Debug::dump($e->getMessage());die('2');
            }
        }
    }


}