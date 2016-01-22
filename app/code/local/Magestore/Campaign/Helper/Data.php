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
 * Campaign Helper
 *
 * @category    Magestore
 * @package     Magestore_Campaign
 * @author      Magestore Developer
 */
class Magestore_Campaign_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function convertContentToHtml($content){
        /* @var $helper Mage_Cms_Helper_Data */
        $helper = Mage::helper('cms');
        $processor = $helper->getBlockTemplateProcessor();
        $html = $processor->filter($content);
        return $html;
    }

    /**
     * alias function to check url is accepted
     * @param $includes
     * @param string $excludes
     * @return bool
     */
    public function checkAccept($includes, $excludes = ''){
        return $this->checkInclude($includes, $excludes);
    }

    /**
     * to check current url is include
     * @param string $includes path
     * @return bool
     */
    public function checkInclude($includes, $excludes){
        //convert string to array
        if(!is_array($includes)){
            $includes = str_replace(' ', '', $includes); //clear null string
            $includes = explode(';', $includes); //to array
        }
        //begin check
        if($this->matchUri($includes) &&
            $this->matchUri('checkout/cart/;checkout/onepage/;onestepcheckout/index/index')){
            //case both include and exclude sample page in pages are special page
            return true;
            /*if($this->matchUri($includes)){
                return true; //only pages in includes are accepted
            }*/
        }else{
            //check excludes first
            if(!$this->checkExclude($excludes)){
                if(count($includes) == 1 && $includes[0] == null){
                    return true; //include all for empty includes
                }
                if($this->matchUri($includes)){
                    return true; //only pages in includes are accepted
                }
            }
        }
        //this url page is in excludes
        return false;
    }

    /**
     * want to do not accept url with these excludes
     * @param string $includes path
     * @return bool true if match url in exclude
     */
    public function checkExclude($excludes){
        return $this->matchUri($excludes);
    }

    /**
     * check current page url is match with matched given
     * @param $matched
     * @return bool
     */
    public function matchUri($matched){

        $request = Mage::app()->getRequest();
        $curUrl = Mage::helper('core/url')->getCurrentUrl();
        if(!is_array($matched)){
            $matched = explode(';', $matched);
        }
        //check 1
        $curUri = $request->getServer('REQUEST_URI');
        if(in_array($curUri, $matched)){
            return true;
        }
        $curUriTrim = trim($curUri, '/');
        //null input text is not match
        if(count($matched) == 1 && $matched[0] == null){
            return false;
        }
        //check 2
        foreach ($matched as $value) {
            $value = trim($value);
            if(strlen($value) > 1){
                $value = trim($value, '/'); //match uri/ -> uri
            }
            if($value == '*'){
                return true; //* match with all page
            }
            if($value == '/' && Mage::getBlockSingleton('page/html_header')->getIsHomePage()){
                return true; // accept only home page
            }
            if($value == null){
                continue; //not match null
            }
            if($curUriTrim != ''){
                if((strpos($curUrl, strtolower($value)) !== false ||
                        strpos(strtolower($value), $curUriTrim) !== false)
                    && $value != '/'){
                    return true; //match any not is home page
                }
            }
        }
        return false;
    }


    /**
     * save and create new static block when save campaign
     * @param string $identifier of static block
     * @param string (html) $content
     * @param string $title
     * @param array $stores id
     * @return int id
     */
    public function saveStaticBlock($identifier, $content, $title = '', $stores = array(0))
    {
        if ($this->isExistCmsBlock($identifier, $stores)) {
            $this->deleteCmsBlock($identifier, $stores);
        }
        $block = Mage::getModel('cms/block');
        $block->setIdentifier($identifier);
        $block->setContent($content);
        $block->setTitle($title);
        $block->setIsActive(true);
        $block->setStores($stores); //set use all store with default
        $block->save();
        return $block->getId();
    }

    /**
     * check exist cms block by identifier and store
     * @param $identifier
     * @param array $stores
     * @return bool
     */
    public function isExistCmsBlock($identifier, $stores = array(0))
    {
        $collection = Mage::getModel('cms/block')->getCollection();
        $collection
            ->getSelect()
            ->join(array('store' => $collection->getTable('cms/block_store')),
                'main_table.block_id = store.block_id',
                array('store.store_id'));
        $collection->addFieldToFilter('main_table.identifier', $identifier)
            ->addFieldToFilter('store.store_id', array('in' => $stores));
        if ($collection->getSize() > 0) {
            return true;
        }
        return false;
    }


    public function deleteCmsBlock($identifier, $stores = array(0))
    {
        $collection = Mage::getModel('cms/block')->getCollection();
        $collection
            ->getSelect()
            ->join(array('store' => $collection->getTable('cms/block_store')),
                'main_table.block_id = store.block_id',
                array('store.store_id'))
            ->group('main_table.block_id');
        $collection->addFieldToFilter('main_table.identifier', $identifier)
            ->addFieldToFilter('store.store_id', array('in' => $stores));
        $item = $collection->getFirstItem();
        //$item->delete();
        Mage::getModel('cms/block')->load($item->getId())->delete();
    }

    /**
     * prepare collection to get
     *
     * @param string $popup_type
     * @param string $form_type
     * @return Campaign_Model_Resource_Popup_Template_Collection
     */
    /*public function getPopupTemplate($popup_type = '', $form_type = '')
    {
        $templates = Mage::getResourceModel('campaign/popup_template_collection');
        if ($popup_type && $popup_type != 'all')
            $templates->addFieldToFilter('popup_type', $popup_type);
        if ($form_type && $form_type != 'all')
            $templates->addFieldToFilter('type', $form_type);
        return $templates;
    }*/

    /*public function popTemplateOptions($popup_type = '', $form_type = '', $blank_first = false, $title = '-- Please select template --')
    {
        $options = array();
        if ($blank_first) {
            $options[] = array(
                'label' => $title,
                'value' => '',
            );
        }
        $collection = $this->getPopupTemplate($popup_type, $form_type);
        foreach ($collection as $template) {
            $options[] = array(
                'label' => $template->getTitle(),
                'value' => $template->getId(),
            );
        }
        return $options;
    }*/

    /*public function popTemplateOverview($popup_type = '', $form_type = '')
    {
        $options = array();
        $collection = $this->getPopupTemplate($popup_type, $form_type);
        foreach ($collection as $template) {
            if ($template->getOverview() != null)
                $options[$template->getId()] = Mage::getBlockSingleton('campaign/adminhtml_popup_overview_template')->getSkinUrl($template->getOverview());
        }
        return $options;
    }*/
    public function checkSegmentpopup(){
        return true;
    }
    public function checkStylepopup(){
        return true;
    }
    public function checkCookiepopup(){
        return true;
    }

}