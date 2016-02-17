<?php
include_once('lib/Mobile_Detect.php');
class Magestore_Campaign_Block_Default extends Mage_Core_Block_Template {

    public function _prepareLayout() {
        return parent::_prepareLayout();
    }

    public function getBlockData() {
        if (!$this->hasData('block_data')) {
            $bannerslider_id = $this->getBannersliderId();
            if ($bannerslider_id) {
                $block_data = Mage::getModel('campaign/bannerslider')->load($bannerslider_id);
            } else {
                $block_data = $this->getSliderData();
            }



            $category = Mage::registry('current_category');
            $cateIds = array();
            if ($category) {
                $cateIds = $category->getPathIds();
                $categoryIds = $block_data->getCategoryIds();
                $categoryIds = explode(",", $categoryIds);
                if (strncasecmp('category', $block_data->getPosition(), 8) == 0) {
                    if (count(array_intersect($cateIds, $categoryIds)) == 0) {
                        $block_data = null;
                        return null;
                    }
                }
            }

            $today = date("Y-m-d");
            $randomise = $block_data->getSortType() ? false : true;
            $banners = Mage::getModel('campaign/banner')->getCollection()
                    ->addFieldToFilter('bannerslider_id', $block_data->getId())
                    ->addFieldToFilter('status', 0)                   
                    ->addFieldToFilter('start_time', array('lteq' => $today))
                    ->addFieldToFilter('end_time', array('gteq' => $today))
                   ->setOrder('order_banner', "ASC");
           $banners->getSelect()->columns(array($randomise ? 'Rand() as order' : ''));


            $result = array();
            $result['block'] = $block_data;
            $result['banners'] = array();
            foreach ($banners as $banner){
                $result['banners'][] = $banner->getData();

            }

            $this->setData('block_data', $result);
        }
        return $this->getData('block_data');
    }

    /**Visitorsegment for slider**/
    //get slider
    public function getSegmentcampaign(){
        $result = $this->getBlockData();

        if($this->checkUserLogin()){
            return $result;
        }
        return NULL;
    }

    //z set visitorsegment check user login
    public function checkUserLogin(){
        $result = $this->getBlockData();
        $block = $result['block'];
        $campaignId = $block['campaign_id'];
        $model_campaign = Mage::getModel('campaign/campaign')->load($campaignId);

        $user = $model_campaign->getLoginUser();

        if($user != ''){
            //if all user
            if($user == 'all_user'){
                return true;
            }else{
                //if registed or loged
                $login = Mage::getSingleton('customer/session')->isLoggedIn(); //Check if User is Logged In
                if($user == 'registed_loged'){
                    if($login){
                        return true;
                    }
                }
                //if register or logout
                if($user == 'logout_not_register'){
                    if($login == false){
                        return true;
                    }
                }
            }

            return false;
        }else{
            return false;
        }
    }

    /**End Visitorsegment for slider**/

    public function returntemplateSlider($style, $result) {
        $html = '';
        switch ($style) {
            case "1":
                $html = $this->getLayout()->createBlock('campaign/default')->setBlockData($result)->setTemplate('bannercampaign/slider1.phtml')->toHtml();
                break;
            case "2":
                $html = $this->getLayout()->createBlock('campaign/default')->setBlockData($result)->setTemplate('bannercampaign/slider2.phtml')->toHtml();
                break;
            case "3":
                $html = $this->getLayout()->createBlock('campaign/default')->setBlockData($result)->setTemplate('bannercampaign/slider3.phtml')->toHtml();
                break;
            case "4":
                $html = $this->getLayout()->createBlock('campaign/default')->setBlockData($result)->setTemplate('bannercampaign/slider4.phtml')->toHtml();
                break;
            case "5":
                $html = $this->getLayout()->createBlock('campaign/default')->setBlockData($result)->setTemplate('bannercampaign/slider5.phtml')->toHtml();
                break;
            case "6":
                $html = $this->getLayout()->createBlock('campaign/default')->setBlockData($result)->setTemplate('bannercampaign/slider6.phtml')->toHtml();
                break;
            case "7":
                $html = $this->getLayout()->createBlock('campaign/default')->setBlockData($result)->setTemplate('bannercampaign/slider7.phtml')->toHtml();
                break;
            case "8":
                $html = $this->getLayout()->createBlock('campaign/default')->setBlockData($result)->setTemplate('bannercampaign/slider8.phtml')->toHtml();
                break;
            case "9":
                $html = $this->getLayout()->createBlock('campaign/default')->setBlockData($result)->setTemplate('bannercampaign/slider9.phtml')->toHtml();
                break;
            case "10":
                $html = $this->getLayout()->createBlock('campaign/default')->setBlockData($result)->setTemplate('bannercampaign/slider10.phtml')->toHtml();
                break;
            case "11":
                $html = $this->getLayout()->createBlock('campaign/default')->setBlockData($result)->setTemplate('bannercampaign/popup.phtml')->toHtml();
                break;
            case "12":
                $html = $this->getLayout()->createBlock('campaign/default')->setBlockData($result)->setTemplate('bannercampaign/note.phtml')->toHtml();
                break;
            default :
                $html = '';
        }
        return $html;
    }

    public function getBannerImage($imageName) {
        return Mage::helper('campaign')->getBannerImage($imageName);
    }

    public function getTarget($x) {
        if ($x == 0) {
            return '_self';
        } elseif ($x == 1) {
            return '_parent';
        } else {
            return '_blank';
        }
    }

    public function getMinItem($value) {
        if (!$value)
            return 2;
        return $value;
    }

    public function getMaxItem($value) {
        if (!$value)
            return 4;
        if ($value > 12)
            return 12;
        return $value;
    }

    public function isDisplayPopup() {
        $cookie = Mage::getSingleton('core/cookie');
        if ($cookie->get('isdisplaypopup')) {
            return false;
        } else {
            setcookie("isdisplaypopup", 'true', time() + 120, "/");
            return true;
        }
    }

}