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
class Magestore_Campaign_TestController extends Mage_Core_Controller_Front_Action
{
    /**
     * index action
     */
    public function TestAction()
    {
        //$this->loadLayout();

        //$this->renderLayout();

        Zend_debug::dump($this->getLayout()->createBlock('campaign/popup')->setTemplate('campaign/popup/subscribe/template04.phtml')->toHtml());

    }

}