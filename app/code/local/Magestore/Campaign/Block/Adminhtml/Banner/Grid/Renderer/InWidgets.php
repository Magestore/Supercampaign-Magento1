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
 * Campaign Grid Block
 * 
 * @category    Magestore
 * @package     Magestore_Campaign
 * @author      Magestore Developer
 */
class Magestore_Campaign_Block_Adminhtml_Banner_Grid_Renderer_InWidgets
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $value =  $row->getData($this->getColumn()->getIndex());
        $widgets = Mage::getModel('campaign/widget')->getCollection();
        $widget_ids = explode('&', $value);
        if(!is_array($widget_ids)){
            $widget_ids = array();
        }
        //zend_debug::dump($value);die;
        $widgets->addFieldToFilter('widget_id', array('in'=>array($widget_ids)));
        $rendered = '';
        foreach ($widgets as $w) {
            $rendered .= $w->getTitle().'; ';
        }
        return trim($rendered, '; ');
    }
}