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
class Magestore_Campaign_Model_Template extends Mage_Core_Model_Abstract
{

    public function _construct()
    {
        parent::_construct();
        $this->_init('campaign/template');
    }


    public function getOptions($group = '', $type = '', $blank_first = false, $title = '-- Please select template --'){
        $options = array();
        if($blank_first){
            $options[] = array(
                'label' => $title,
                'value' => '',
            );
        }
        $collection = $this->getCollectionTypes($group, $type);
        foreach($collection as $template){
            $options[] = array(
                'label' => $template->getTitle(),
                'value' => $template->getId(),
            );
        }
        return $options;
    }

    public function getOverviews($group = '', $type = ''){
        $options = array();
        $collection = $this->getCollectionTypes($group, $type);
        foreach($collection as $template){
            if($template->getOverview() != null)
                $options[$template->getId()] = Mage::getBlockSingleton('campaign/adminhtml_overview_template')
                    ->getSkinUrl($template->getOverview());
        }
        return $options;
    }

    /**
     * get this collection filter by type
     * @param $type
     * @return mixed
     */
    public function getCollectionTypes($group, $type){
        $collection = $this->getCollection();
        $cond = array();
        $cols = array();
        if($group == 'all' || $group == 'ALL' || $group == 'All'){

        }elseif($group){
            $cols[] = '`group`';
            $cond[] = array('eq'=>$group);
        }
        if(count($cond)){
            $collection->addFieldToFilter($cols, $cond);
        }
        $cond = array();
        $cols = array();
        if($type == 'all' || $type == 'ALL' || $type == 'All'){

        }elseif($type){
            $cols[] = '`type`';
            $cond[] = array('eq'=>$type);
        }
        if(count($cond)){
            $collection->addFieldToFilter($cols, $cond);
        }
        return $collection;
    }


    /*-----------new ver 3.0------------*/

    /**
     * get full path of short path
     * @param string $path
     * @return string
     */
    public function getFullPath($path = ''){
        $path = trim($path, '_');
//        $patharr = explode('_', $path);
//        $_path = 'adminhtml/Magestore_Campaign/templates/groups';
//        if(isset($patharr[0]) && $patharr[0] != ''){
//            $_path .= '/'.$patharr[0].'/types';
//        }
//        if(isset($patharr[1]) && $patharr[1] != ''){
//            $_path .= '/'.$patharr[1].'/childs';
//        }
//        if(isset($patharr[2]) && $patharr[2] != ''){
//            $_path .= '/'.$patharr[2];
//            $last = trim(str_replace($patharr[0].'_'.$patharr[1].'_'.$patharr[2], '', $path), '_');
//        }
//        if(isset($last) && $last != ''){
//            $last = str_replace('_', '/', $last);
//            $_path .= '/'.$last;
//        }
        $_path = 'adminhtml/Magestore_Campaign/templates';
        $last = str_replace('_', '/', $path);
        $_path .= '/'.$last;
        return $_path;
    }

    /**
     * get node as array
     * @param string $path
     * @return array
     */
    public function getConfigAsArray($path = ''){
        $_path = $this->getFullPath($path);
        $temps = array();
        $tempNode = Mage::getConfig()->getNode($_path);
        if($tempNode){
            $temps = $tempNode->asArray();
        }
        return $temps;
    }

    /**
     * get template array
     * @param string $path group_type_child[_*]
     * return array
     */
    public function getTemplates($path = ''){
        $path = trim($path, '_');
        if($path){
            return $this->getConfigAsArray($path);
        }else{
            $tempNode = Mage::getConfig()->getNode('adminhtml/Magestore_Campaign/templates/groups');
            $groups = $tempNode->asArray();
            return $groups;
        }
    }

    public function getTemplateOption($path = ''){
        $path = trim($path, '_');
        if($path){
            $node = $this->getConfigAsArray($path);
            return $this->_recursiConf($node, $path);
        }else{
            $tempNode = Mage::getConfig()->getNode('adminhtml/Magestore_Campaign/templates');
            $node = $tempNode->asArray();
            return $this->_recursiConf($node);
        }
    }


    protected function _recursiConf($node, $parentPath = '', &$result = array()){
        if(is_array($node)){
            $temp = array();
            foreach($node as $key => $childNode){
                if($key == '@attributes' || $key == '@'){
                    continue;
                }
                if(is_array($childNode)){
                    if($parentPath != ''){
                        $_path = $parentPath.'_'.$key;
                    }else{
                        $_path = $key;
                    }
                    $this->_recursiConf($childNode, $_path, $result);
                }else{
                    if($key == 'image'){
                        $temp[$key] = $this->getOverviewsV2($parentPath);
                    }elseif($key == 'title'){
                        $temp['label'] = $childNode;
                    }else{
                        $temp['value'] = $parentPath;
                    }
                }
            }
            if(!empty($temp)){
                $result[$parentPath] = $temp;
            }
            return $result;
        }else{
            return $node;
        }
    }


    /**
     * get titles as array for node path, include node name to be a key
     * @param string $path
     * @return array
     */
    public function getSelectTitle($path = ''){
        $path = trim($path, '_');
        if($path){
            $_path = $this->getFullPath($path);
            $node = Mage::getConfig()->getNode($_path);
            if(!is_object($node)){
                array('no data');
            }
            return $this->_recursiTitle($node);
        }else{
            $node = Mage::getConfig()->getNode('adminhtml/Magestore_Campaign/templates');
            if(!is_object($node)){
                array('no data');
            }
            return $this->_recursiTitle($node);
        }
    }

    /**
     * recur and get title attribute for childs to array
     * @param $node
     * @return array
     */
    protected function _recursiTitle($node){
        if($node->hasChildren()){
            $temp = array();
            foreach($node->children() as $childName => $childNode){
                if($childNode->getAttribute('title')){
                    $temp[$childNode->getName()] = $childNode->getAttribute('title');
                }else{
                    $temp[$childNode->getName()] = $childNode->getName();
                }
            }
            return $temp;
        }else{
            return array($node->getName()=>$node->getAttribute('title'));
        }
    }

    /**
     * get options for select element html by path
     * @param $path | when null default is select from group
     * @param bool $blank_first
     * @param string $title
     * @return array contain Title and path code
     */
    public function getSelectOption($path = '', $blank_first = false, $title = '-- Please select template --'){
        $options = array();
        if($blank_first){
            $options[] = array(
                'label' => $title,
                'value' => '',
            );
        }
        $temps = $this->getConfigAsArray($path);
        foreach($temps as $key => $val){
            if(is_array($val)&& isset($val['title'])){
                $options[] = array(
                    'label' => $val['title'],
                    'value' => ($path)? trim($path, '_').'_'.$key : $key,
                    'image' => $this->getOverviewsV2($path.'_'.$key)
                );
            }else{
                $options[] = array(
                    'label' => $temps['title'],
                    'value' => $path,
                    'image' => $this->getOverviewsV2($path)
                );
                break;
            }
        }
        return $options;
    }

    /**
     * get options for select element html by path and group with child path
     * @param string $path
     * @param string $group_path
     * @param bool $blank_first
     * @param string $title
     * @return array
     */
    public function getSelectOptionGroup($path = '', $group_path = '', $blank_first = false, $title = '-- Please select template --'){
        $options = array();
        if($blank_first){
            $options[] = array(
                'label' => $title,
                'value' => '',
                'image' => ''
            );
        }
        $temps = $this->getConfigAsArray($path);
        foreach($temps as $key => $val){
            if(is_array($val)&& isset($val['title'])){
                $options[] = array(
                    'label' => $val['title'],
                    'value' => ($path)? trim($path, '_').'_'.$key : $key,
                    'image' => $this->getOverviewsV2($path.'_'.$key.'_'.$group_path)
                );
            }
        }
        if($group_path){
            $optTemp = array();
            foreach($options as $opt){
                $childNode = $this->getConfigAsArray($opt['value'].'_'.trim($group_path, '_'));
                if(is_array($childNode) && !empty($childNode)){
                    if(isset($childNode['title'])){
                        $optTemp[] = array(
                            'label' => $childNode['title'],
                            'value' => ($path)? $opt['value'].'_'.trim($group_path, '_') : $opt['value'],
                            'image' => $opt['image']
                        );
                    }
                }
            }
            $options = $optTemp;
        }
        return $options;
    }

    /**
     * get content of file template by path
     * @param $temp_path
     * @return string
     */
    public function getContent($temp_path){
        $_path = $this->getFullPath($temp_path);
        $tempNode = Mage::getConfig()->getNode($_path);
        if($tempNode && $tempNode->file){
            $_subDir = str_replace('_', '/', trim($temp_path, '_'));
            $content = Mage::getBlockSingleton('core/template')
                ->setTemplate('campaign/templates/'.$_subDir.'/'.$tempNode->file)
                ->toHtml();
            //$content = file_get_contents(Mage::getBaseDir('design').DS.'adminhtml'
            //    .DS.'default'.DS.'default'.DS.'template'.DS.'campaign'.DS.'templates'.DS.$_subDir.DS.$tempNode->file);
            //zend_debug::dump($content);die;
            return $content;
        }
        return '';
    }


    /**
     * get template overview image url for path
     * @param $temp_path
     * @return string
     */
    public function getOverviewsV2($temp_path){
        $_path = $this->getFullPath($temp_path);
        $tempNode = Mage::getConfig()->getNode($_path);
        if($tempNode && $tempNode->file){
            $_subDir = str_replace('_', '/', trim($temp_path, '_'));
            $url = Mage::getBlockSingleton('core/template')
                ->getSkinUrl('images/magestore/campaign/templates/'.$_subDir.'/'.$tempNode->image);
            return $url;
        }
        return '';
    }

}