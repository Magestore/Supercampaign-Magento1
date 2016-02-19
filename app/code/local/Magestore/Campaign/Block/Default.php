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
        //priority from highest to lowest
        $campaignId = $this->getCampaignId();
        $model_campaign = Mage::getModel('campaign/campaign')->load($campaignId);
        $userip = $model_campaign->getUserIp();
        if($userip != ''){
            if($this->checkUserIP()){
                return $result;
            }
        }
        if($this->checkUserLogin() && $this->checkReturnCustomer() && $this->checkCustomerGroup() && $this->checkDevices()){
            return $result;
        }
        return NULL;
    }

    public function getCampaignId(){
        $result = $this->getBlockData();
        $block = $result['block'];
        $campaignId = $block['campaign_id'];
        return $campaignId;
    }

    //z set visitorsegment check value
    public function checkDevices(){
        $detector = new Mobile_Detect();
        $devicetoshow = array();
        $campaignId = $this->getCampaignId();
        $model_campaign = Mage::getModel('campaign/campaign')->load($campaignId);
        $devices = $model_campaign->getDevices();
        if($devices != ''){
            if(!is_array($devices)){
                $devicetoshow[] = $devices;
            }else{
                $devicetoshow = $devices;
            }
            //explode in array
            $sub_device = array();
            foreach ($devicetoshow as $subgr) {
                if(in_array(trim($subgr), $devicetoshow)){
                    $sub_device[] = explode(',', trim($subgr));
                }
            }
        }else{
            return false;
        }
        //end get value of device
        if($devices != ''){
            $tablet = $detector->isTablet();
            $mobile = $detector->isMobile();

            foreach($sub_device as $subg){
                foreach($subg as $sub){
                    if($sub == 'all_device'){
                        return true;
                    }
                    if($sub == 'pc_laptop'){
                        if($tablet == false && $mobile == false){
                            return true;
                        }
                    }
                    if($sub == 'tablet_mobile'){
                        if($tablet || $mobile){
                            return true;
                        }
                    }
                }
            }

            return false;
        }else{
            return false;
        }
    }
    //z set visitorsegment check user login
    public function checkUserLogin(){
        $campaignId = $this->getCampaignId();
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

    /**
     * option show with new user or visited user
     * @return bool
     */
    //check enable cookie
    public function enableCookie(){
        return true; //cookie alway enabled
        $campaignId = $this->getCampaignId();
        $model_campaign = Mage::getModel('campaign/campaign')->load($campaignId);
        $enable = $model_campaign->getCookiesEnabled();
        if($enable != ''){
            if($enable == 1){
                $this->checkReturnCustomer();
            }else{
                return false;
            }
        }
    }

    public function statusCookie(){
        $campaignId = $this->getCampaignId();
        $model_campaign = Mage::getModel('campaign/campaign')->load($campaignId);
        $enable = $model_campaign->getCookiesEnabled();
        return $enable;
    }

    //z set visitorsegment check return customer
    public function checkReturnCustomer(){

        $login = Mage::getSingleton('customer/session')->isLoggedIn();
        if (Mage::getSingleton('customer/session')->isLoggedIn()) {

            $customer = Mage::getSingleton('customer/session')->getCustomer();
            $customer_name = $customer->getName();
        }
        $campaignId = $this->getCampaignId();
        $model_campaign = Mage::getModel('campaign/campaign')->load($campaignId);
        $ipcustomer = Mage::helper('core/http')->getRemoteAddr();
        //$popupid = $this->getPopupId();
        $camPaignid = $model_campaign->getCampaignId();
        $getReturn = $model_campaign->getReturningUser();
        $cookiepopup = $model_campaign->getCookieTime();

        $fixip = str_replace(".","_",$ipcustomer);
        $customer_cookie = Mage::getSingleton('core/cookie')->get($fixip);
        $allcookie = Mage::getModel('core/cookie')->get();

        // if empty cookie time
        if($cookiepopup == '' || $cookiepopup < 1){
            return true;
        }

        //check cookie customer
        if($customer_cookie) {
            if($getReturn == 'alluser'){
                return true;
            }
            if($getReturn == 'new'){
                return false;
            }
            if($getReturn == 'return'){
                return true;
            }
        }
        //set cookie for new customer
        if(!$customer_cookie) {
            if($ipcustomer){
                //set cookie for customer
                $name = $ipcustomer;
                $value = $campaignId;
                $period = $cookiepopup * 86400;
                Mage::getModel('core/cookie')->set($name, $value, $period);
            }
            return true;
        }
    }

    /**
     * option show or not with current customer's group
     * @return bool
     */
    public function checkCustomerGroup(){
        $grouptoshow = array();
        $campaignId = $this->getCampaignId();
        $model_campaign = Mage::getModel('campaign/campaign')->load($campaignId);
        $group = $model_campaign->getCustomerGroupIds();

        //call group code of group customer
        $login = Mage::getSingleton('customer/session')->isLoggedIn();
        $gid = Mage::getSingleton('customer/session')->getCustomerGroupId();
        $groupcustomer = Mage::getModel('customer/group')->load($gid);
        $groupcode = $groupcustomer->getCustomerGroupCode();

        if($group != ''){
            if(!is_array($group)){
                $grouptoshow[] = $group;
            }else{
                $grouptoshow = $group;
            }
            //explode in array
            $sub_group = array();
            foreach ($grouptoshow as $subgr) {
                if(in_array(trim($subgr), $grouptoshow)){
                    $sub_group[] = explode(',', trim($subgr));
                }
            }

        }else{
            return false;
        }

        //end get value
        if($group != ''){

            foreach($sub_group as $subg){
                foreach($subg as $sub){

                    if($sub == 'all_group'){
                        return true;

                    }

                    if($sub == 'general'){

                        if($groupcode == 'General'){
                            return true;
                        }


                    }
                    if($sub == 'wholesale'){

                        if($groupcode == 'Wholesale'){
                            return true;
                        }

                    }
                    if($sub == 'vip_member'){

                        if($groupcode == 'VIP Member'){
                            return true;
                        }
                        break;
                    }
                    if($sub == 'private_sale_member'){

                        if($groupcode == 'Private Sales Member'){
                            return true;
                        }

                    }

                }
            }


            return false;
        }else{
            return false;
        }

    }


    public function checkUserIP(){
        $ipcustomer = Mage::helper('core/http')->getRemoteAddr();
        $campaignId = $this->getCampaignId();
        $model_campaign = Mage::getModel('campaign/campaign')->load($campaignId);
        $ipdata = $model_campaign->getUserIp();
        if($ipdata != ''){
            if($ipdata == $ipcustomer){
                return true;
            }
        }
        return false;
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