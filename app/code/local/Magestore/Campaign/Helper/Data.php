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
    const CONFIG_ENABLE_PATH = 'campaign/general/enable';

    public function isEnabled(){
        return Mage::getStoreConfig(self::CONFIG_ENABLE_PATH);
    }

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



    //Zeus Helper banner slider

    public function getBlockIdsToOptionsArray() {
        return array(
            array(
                'label' => $this->__('------- Please choose position -------'),
                'value' => ''),
            array(
                'label' => $this->__('Popular positions'),
                'value' => array(
                    array('value' => 'cms-page-content-top', 'label' => $this->__('Homepage-Content-Top')),
                )),
            array(
                'label' => $this->__('Default for using in CMS page template'),
                'value' => array(
                    array('value' => 'custom', 'label' => $this->__('Custom')),
                )),
            array(
                'label' => $this->__('General (will be disaplyed on all pages)'),
                'value' => array(
                    array('value' => 'sidebar-right-top', 'label' => $this->__('Sidebar-Top-Right')),
                    array('value' => 'sidebar-right-bottom', 'label' => $this->__('Sidebar-Bottom-Right')),
                    array('value' => 'sidebar-left-top', 'label' => $this->__('Sidebar-Top-Left')),
                    array('value' => 'sidebar-left-bottom', 'label' => $this->__('Sidebar-Bottom-Left')),
                    array('value' => 'content-top', 'label' => $this->__('Content-Top')),
                    array('value' => 'menu-top', 'label' => $this->__('Menu-Top')),
                    array('value' => 'menu-bottom', 'label' => $this->__('Menu-Bottom')),
                    array('value' => 'page-bottom', 'label' => $this->__('Page-Bottom')),
                )),
            array(
                'label' => $this->__('Catalog and product'),
                'value' => array(
                    array('value' => 'catalog-sidebar-right-top', 'label' => $this->__('Catalog-Sidebar-Top-Right')),
                    array('value' => 'catalog-sidebar-right-bottom', 'label' => $this->__('Catalog-Sidebar-Bottom-Right')),
                    array('value' => 'catalog-sidebar-left-top', 'label' => $this->__('Catalog-Sidebar-Top-Left')),
                    array('value' => 'catalog-sidebar-left-bottom', 'label' => $this->__('Catalog-Sidebar-Bottom-Left')),
                    array('value' => 'catalog-content-top', 'label' => $this->__('Catalog-Content-Top')),
                    array('value' => 'catalog-menu-top', 'label' => $this->__('Catalog-Menu-Top')),
                    array('value' => 'catalog-menu-bottom', 'label' => $this->__('Catalog-Menu-Bottom')),
                    array('value' => 'catalog-page-bottom', 'label' => $this->__('Catalog-Page-Bottom')),
                )),
            array(
                'label' => $this->__('Category only'),
                'value' => array(
                    array('value' => 'category-sidebar-right-top', 'label' => $this->__('Category-Sidebar-Top-Right')),
                    array('value' => 'category-sidebar-right-bottom', 'label' => $this->__('Category-Sidebar-Bottom-Right')),
                    array('value' => 'category-sidebar-left-top', 'label' => $this->__('Category-Sidebar-Top-Left')),
                    array('value' => 'category-sidebar-left-bottom', 'label' => $this->__('Category-Sidebar-Bottom-Left')),
                    array('value' => 'category-content-top', 'label' => $this->__('Category-Content-Top')),
                    array('value' => 'category-menu-top', 'label' => $this->__('Category-Menu-Top')),
                    array('value' => 'category-menu-bottom', 'label' => $this->__('Category-Menu-Bottom')),
                    array('value' => 'category-page-bottom', 'label' => $this->__('Category-Page-Bottom')),
                )),
            array(
                'label' => $this->__('Product only'),
                'value' => array(
                    array('value' => 'product-sidebar-right-top', 'label' => $this->__('Product-Sidebar-Top-Right')),
                    array('value' => 'product-sidebar-right-bottom', 'label' => $this->__('Product-Sidebar-Bottom-Right')),
                    array('value' => 'product-sidebar-left-top', 'label' => $this->__('Product-Sidebar-Top-Left')),
                    array('value' => 'product-content-top', 'label' => $this->__('Product-Content-Top')),
                    array('value' => 'product-menu-top', 'label' => $this->__('Product-Menu-Top')),
                    array('value' => 'product-menu-bottom', 'label' => $this->__('Product-Menu-Bottom')),
                    array('value' => 'product-page-bottom', 'label' => $this->__('Product-Page-Bottom')),
                )),
            array(
                'label' => $this->__('Customer'),
                'value' => array(
                    array('value' => 'customer-content-top', 'label' => $this->__('Customer-Content-Top')),
                )),
            array(
                'label' => $this->__('Cart & Checkout'),
                'value' => array(
                    array('value' => 'cart-content-top', 'label' => $this->__('Cart-Content-Top')),
                    array('value' => 'checkout-content-top', 'label' => $this->__('Checkout-Content-Top')),
                )),
        );
    }

    public function getStyleSlider() {
        return array(
            array(
                'label' => $this->__('--------- Please choose style -------'),
                'value' => ''
            ),
            array(
                'label' => $this->__('Special Slider'),
                'value' => array(
                    array('value' => '5', 'label' => $this->__('Pop up on Home page')),
                    array('value' => '6', 'label' => $this->__('Note displayed on all pages')),
                )),
            array(
                'label' => $this->__('Unresponsive Slider'),
                'value' => array(
                    array(
                        'label' => $this->__('Slider Evolution Default'),
                        'value' => '1'
                    ),
                    array(
                        'label' => $this->__('Slider Evolution Caborno'),
                        'value' => '2'
                    ),
                    array(
                        'label' => $this->__('Slider Evolution Minimalist'),
                        'value' => '3'
                    ),
                    array(
                        'label' => $this->__('Slider Evolution Fresh'),
                        'value' => '4'
                    ),
                ),
            ),
            array(
                'label' => $this->__('Responsive Slider'),
                'value' => array(
                    array(
                        'label' => $this->__('FlexSlider 1'),
                        'value' => '7'
                    ),
                    array(
                        'label' => $this->__('FlexSlider 2'),
                        'value' => '8'
                    ),
                    array(
                        'label' => $this->__('FlexSlider 3'),
                        'value' => '9'
                    ),
                    array(
                        'label' => $this->__('FlexSlider 4'),
                        'value' => '10'
                    ),
                ),
            ),
        );
    }

    public function getOptionYesNo() {
        return array(
            array(
                'label' => $this->__('Yes'),
                'value' => '1'
            ),
            array(
                'label' => $this->__('No'),
                'value' => '0'
            ),
        );
    }



    public function getOptionSliderId() {
        $option = array();
        $bannerslider_id = Mage::app()->getRequest()->getParam('sliderid');
        if ($bannerslider_id) {
            $slider = Mage::getModel('campaign/bannerslider')->load($bannerslider_id);
            $option[] = array(
                'value' => $slider->getId(),
                'label' => $slider->getTitle(),
            );
            return $option;
        }
        $option[] = array(
            'value' => '',
            'label' => Mage::helper('campaign')->__('-------- Please select a slider --------'),
        );
        $slider = Mage::getModel('campaign/bannerslider')->getCollection();
        foreach ($slider as $value) {
            $option[] = array(
                'value' => $value->getId(),
                'label' => $value->getTitle(),
            );
        }

        return $option;
    }

    public function deleteImageFile($image) {
        if (!$image) {
            return;
        }
        $name = $this->reImageName($image);
        $banner_image_path = Mage::getBaseUrl('media') . DS . 'bannercampaign' . DS . $name;
        if (!file_exists($banner_image_path)) {
            return;
        }

        try {
            unlink($banner_image_path);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public static function uploadBannerImage() {
        $banner_image_path = Mage::getBaseDir('media') . DS . 'bannercampaign';
        $image = "";
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
            try {
                /* Starting upload */
                $uploader = new Varien_File_Uploader('image');

                // Any extention would work
                $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                $uploader->setAllowRenameFiles(true);

                $uploader->setFilesDispersion(true);

                $uploader->save($banner_image_path, $uploader->getCorrectFileName($_FILES['image']['name']));
                // Add by Hoang Vuong: 30/08/2013
                $image = substr(strrchr($uploader->getUploadedFileName(), "/"), 1);
            } catch (Exception $e) {

            }

            // $image = $_FILES['image']['name'];
        }
        return $image;
    }

    public function getStandardSlider() {
        return array(
            array(
                'label' => $this->__('Slider Evolution Default'),
                'value' => '1'
            ),
            array(
                'label' => $this->__('Slider Evolution Caborno'),
                'value' => '2'
            ),
            array(
                'label' => $this->__('Slider Evolution Minimalist'),
                'value' => '3'
            ),
            array(
                'label' => $this->__('Slider Evolution Fresh'),
                'value' => '4'
            ),
            array(
                'label' => $this->__('FlexSlider 1'),
                'value' => '5'
            ),
            array(
                'label' => $this->__('FlexSlider 2'),
                'value' => '6'
            ),
            array(
                'label' => $this->__('FlexSlider 3'),
                'value' => '7'
            ),
            array(
                'label' => $this->__('FlexSlider 4'),
                'value' => '8'
            ),
            array(
                'label' => $this->__('Note display on all pages'),
                'value' => '9'
            ),
        );
    }

    public function getAnimationA() {
        return array(
            array(
                'label' => $this->__('Fade'),
                'value' => 'fade'
            ),
            array(
                'label' => $this->__('Square'),
                'value' => 'squarerandom'
            ),
            array(
                'label' => $this->__('Bar'),
                'value' => 'bar'
            ),
            array(
                'label' => $this->__('Rain'),
                'value' => 'rain'
            ),
        );
    }

    public function getAnimationB() {
        return array(
            array(
                'label' => $this->__('Fade'),
                'value' => 'fade'
            ),
            array(
                'label' => $this->__('Slide'),
                'value' => 'slide'
            ),
        );
    }

    public function reImageName($imageName) {

        $subname = substr($imageName, 0, 2);
        $array = array();
        $subDir1 = substr($subname, 0, 1);
        $subDir2 = substr($subname, 1, 1);
        $array[0] = $subDir1;
        $array[1] = $subDir2;
        $name = $array[0] . '/' . $array[1] . '/' . $imageName;

        return strtolower($name);
    }

    public function getBannerImage($image) {
        $name = $this->reImageName($image);
        return Mage::getBaseUrl('media') . 'bannercampaign' . '/' . $name;
    }

    public function getPreviewSlider() {
        return Mage::getSingleton('adminhtml/url')->getUrl('*/bannerslider_standardslider/preview/');
    }

    public function getPathImageForBanner($image) {
        $name = $this->reImageName($image);
        return 'bannercampaign' . '/' . $name;
    }

    public function getOptionGridSlider() {
        return array(
            'pop-up' => 'Pop up at Home page',
            'note-allsite' => 'Note will be displayed on all pages',
            'cms-page-content-top' => 'Homepage content top',
            'custom' => 'Custom',
            'sidebar-right-top' => 'Sidebar-Top-Right(all pages)',
            'sidebar-right-bottom' => 'Sidebar-Bottom-Right (all pages)',
            'sidebar-left-top' => 'Sidebar-Top-Left(all pages)',
            'sidebar-left-bottom' => 'Sidebar-Bottom-Left(all pages)',
            'content-top' => 'Content-Top(all pages)',
            'menu-top' => 'Menu-Top(all pages)',
            'menu-bottom' => 'Menu-Bottom(all pages)',
            'page-bottom' => 'Page-Bottom(all pages)',
            'catalog-sidebar-right-top' => ' Catalog-Sidebar-Top-Right',
            'catalog-sidebar-right-bottom' => 'Catalog-Sidebar-Bottom-Right ',
            'catalog-sidebar-left-top' => 'Catalog-Sidebar-Top-Left ',
            'catalog-sidebar-left-bottom' => 'Catalog-Sidebar-Bottom-Left ',
            'catalog-content-top' => 'Catalog-Content-Top ',
            'catalog-menu-top' => 'Catalog-Menu-Top',
            'catalog-menu-bottom' => 'Catalog-Menu-Bottom',
            'catalog-page-bottom' => 'Catalog-Page-Bottom',
            'category-sidebar-right-top' => 'Category-Sidebar-Top-Right ',
            'category-sidebar-right-bottom' => 'Category-Sidebar-Bottom-Right',
            'category-sidebar-left-top' => 'Category-Sidebar-Top-Left ',
            'category-sidebar-left-bottom' => 'Category-Sidebar-Bottom-Left',
            'category-content-top' => 'Category-Content-Top',
            'category-menu-top' => 'Category-Menu-Top',
            'category-menu-bottom' => 'Category-Menu-Bottom ',
            'category-page-bottom' => 'Category-Page-Bottom',
            'product-sidebar-right-top' => 'Product-Sidebar-Top-Right',
            'product-sidebar-right-bottom' => 'Product-Sidebar-Bottom-Right',
            'product-sidebar-left-top' => 'Product-Sidebar-Top-Left',
            'product-sidebar-left-bottom' => 'Product-Sidebar-Bottom-Left',
            'product-content-top' => 'Product-Content-Top',
            'product-menu-top' => 'Product-Menu-Top',
            'product-menu-bottom' => 'Product-Menu-Bottom',
            'product-page-bottom' => 'Product-Page-Bottom',
            'customer-content-top' => 'Customer-Content-Top',
            'cart-content-top' => 'Cart-Content-Top',
            'checkout-content-top' => 'Checkout-Content-Top'
        );
    }

    public function getPositionNote() {
        return array(
            array('label' => $this->__('Top-Left'), 'value' => 1),
            array('label' => $this->__('Top-Middle'), 'value' => 2),
            array('label' => $this->__('Top-Right'), 'value' => 3),
            array('label' => $this->__('Left-Middle'), 'value' => 4),
            array('label' => $this->__('Right-Middle'), 'value' => 5),
            array('label' => $this->__('Bottom-Left'), 'value' => 6),
            array('label' => $this->__('Bottom-Middle'), 'value' => 7),
            array('label' => $this->__('Bottom-Right'), 'value' => 8),
        );
    }

    public function getOptionColor() {
        return array(
            array('label' => $this->__('Yellow'), 'value' => '#f7d700'),
            array('label' => $this->__('Red'), 'value' => '#dd0707'),
            array('label' => $this->__('Orange'), 'value' => '#ee5f00'),
            array('label' => $this->__('Green'), 'value' => '#83ba00'),
            array('label' => $this->__('Blue'), 'value' => '#23b8ff'),
            array('label' => $this->__('Gray'), 'value' => '#999'),
        );
    }

    public function getVaulePosition($value) {
        switch ($value) {
            case 1: return "top-left";
            case 2: return "middle-top";
            case 3: return "top-right";
            case 4: return "middle-left";
            case 5: return "middle-right";
            case 6: return "bottom-left";
            case 7: return "middle-bottom";
            case 8: return "bottom-right";
        }
    }

    public function getValueToCsv($itemCollection, $x=0) {

        $callback = null;
        $data = array();
        $data [] = '"' . str_replace(array('"', '\\'), array('""', '\\\\'), $itemCollection->getId()). '"';
        $data [] = '"' . str_replace(array('"', '\\'), array('""', '\\\\'), $itemCollection->getData('banner_name')) . '"';
        $data [] = '"' . str_replace(array('"', '\\'), array('""', '\\\\'), $itemCollection->getData('banner_url')) . '"';
        if($x ==0) $data [] = '"' . str_replace(array('"', '\\'), array('""', '\\\\'), $itemCollection->getData('slider_title')) . '"';
        $data [] = '"' . str_replace(array('"', '\\'), array('""', '\\\\'), $itemCollection->getData('clicks')) . '"';
        $data [] = '"' . str_replace(array('"', '\\'), array('""', '\\\\'), $itemCollection->getData('impmode')) . '"';
        $data [] = '"' . str_replace(array('"', '\\'), array('""', '\\\\'), $itemCollection->getData('date_click')) . '"';
        $callback = implode(',', $data);
        return $callback;
    }

    public function getValueToXml($keyIndex, $itemCollection){
        $callback = null;
        switch ($keyIndex){
            case "report_id":
                $callback = $itemCollection->getId();
                break;
            case "banner_name":
                $callback = $itemCollection->getBannerName();
                break;
            case "banner_url":
                $callback = $itemCollection->getBannerUrl();
                break;
            case "slider_title":
                $callback = $itemCollection->getSliderTitle();
                break;
            case "banner_click":

                $callback = $itemCollection->getBannerClick();
                break;
            case "banner_impress":

                $callback = $itemCollection->getBannerImpress();
                break;
            default: return null;
        }
        return $callback;
    }

    //zeus end helper slider


}