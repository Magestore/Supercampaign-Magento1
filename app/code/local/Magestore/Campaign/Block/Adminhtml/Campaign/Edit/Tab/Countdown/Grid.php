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
class Magestore_Campaign_Block_Adminhtml_Campaign_Edit_Tab_Countdown_Grid
    extends Mage_Adminhtml_Block_Widget_Grid
{
    const GRID_ID = 'countdown_product_grid';

    public function __construct()
    {
        parent::__construct();
        $this->setId(self::GRID_ID);
        $this->setUseAjax(true);
        $this->setDefaultSort('entity_id');
        //$this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setDefaultFilter(array('countdown_in_products' => 1));
    }

    /**
     * Prepare html output
     *
     * @return string
     */
    /*protected function _toHtml()
    {
        $html = '<div class="entry-edit-head">
    <h4 class="icon-head head-edit-form fieldset-legend">'.Mage::helper('campaign')->__('Select Products to apply for Countdown').'</h4>
    <div class="form-buttons"></div>
</div>';
        //Mage::dispatchEvent('adminhtml_block_html_before', array('block' => $this));
        $html .= parent::_toHtml();
        return $html;
    }*/
    
    /**
     * prepare collection for block to display
     *
     * @return Magestore_Campaign_Block_Adminhtml_Campaign_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('catalog/product')->getCollection();
        $collection->addAttributeToSelect('name');
        if(!Mage::registry('reset_search_product')){
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = array(0);
            }
            $collection->addFieldToFilter('entity_id', array('in' => $productIds));
        }
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    
    /**
     * prepare columns for this grid
     *
     * @return Magestore_Campaign_Block_Adminhtml_Campaign_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('countdown_in_products', array(
            'name'              => 'countdown_in_products',
            'index'             => 'entity_id',
            'field_name'        => 'countdown_selected_products[]',
            'header_css_class'  => 'a-center',
            'type'              => 'checkbox',
            'values'            => $this->_getSelectedProducts(),
            'align'             => 'center',
            'width'             => '50px',
            'use_index'         => true,
            'disabled_values'   => array(),
        ));

        $this->addColumn('countdown_product_id', array(
            'index'     => 'entity_id',
            'header'    => Mage::helper('campaign')->__('ID'),
            'align'     =>'right',
            'width'     => '120px',
            'filter_index' => 'entity_id',
        ));

        $this->addColumn('countdown_product_name', array(
            'index'     => 'name',
            'filter_index' => 'name',
            'header'    => Mage::helper('campaign')->__('Name'),
            'align'     =>'left',
            //'width'     => '220px',
        ));

        return parent::_prepareColumns();
    }

    /**
     * Retrieve selected related products
     *
     * @return array
     */
    protected function _getSelectedProducts()
    {
        if (Mage::getSingleton('adminhtml/session')->getCountdownData()) {
            $data = Mage::getSingleton('adminhtml/session')->getCountdownData();
            Mage::getSingleton('adminhtml/session')->setCountdownData(null);
        } elseif (Mage::registry('countdown_data')) {
            $data = Mage::registry('countdown_data')->getData();
        }
        $product = (isset($data['countdown_product_id']))? $data['countdown_product_id']:'';
        if(!$product && $this->getRequest()->getParam('id')){
            $id = $this->getRequest()->getParam('id');
            $campaign = Mage::getModel('campaign/campaign')->load($id);
            $countdown = $campaign->getCountdown();
            $product = $countdown->getProductId(); //get product ids
        }
        return explode(',', $product);
    }
    
    /**
     * prepare mass action for this grid
     *
     * @return Magestore_Campaign_Block_Adminhtml_Campaign_Grid
     */
    protected function _prepareMassaction()
    {
        /*$this->setMassactionIdField('countdown_product_id');
        $this->getMassactionBlock()->setFormFieldName('countdown_products');
        $this->getMassactionBlock()->setUseAjax(true);
        $this->getMassactionBlock()->setHideFormElement(true);*/

        return $this;
    }

    /**
     * Get grid url
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/countdownGrid', array('_current'=> true));
    }

    /**
     * get url for each row in grid
     *
     * @return string
     */
    public function getRowUrl($row)
    {
        return false;//$this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}