<?php

/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml sales order create search products block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Magestore_Campaign_Block_Adminhtml_Popup_Edit_Tab_Information_Selector_Popups extends Mage_Adminhtml_Block_Widget_Grid {
    

    protected $_selectedProducts;
	
	public function __construct() {
		parent::__construct ();
		$this->setUseAjax ( true );
	}
	
        /**
     * Grid Row JS Callback
     *
     * @return string
     */
    public function getRowClickCallback()
    {
        $js = "  
            function (grid, event) {
                var trElement = Event.findElement(event, 'tr');
                var isInput = Event.element(event).tagName == 'INPUT';
                var input = $('".$this->getInput()."');
                if (trElement) {
                    var checkbox = Element.select(trElement, 'input');
                    $$(\"#".$this->getId()." input[type=checkbox][class=checkbox]\").each(function(e){
                        if (e.name != \"check_all\"){
                            if (e.checked  && e != checkbox[0]){
                                e.checked = '';
                            }
                        }
                    });
                    if (checkbox[0]) {
                        var checked = isInput ? checkbox[0].checked : !checkbox[0].checked;
                        if(checked){
                            input.value = checkbox[0].value;
                        }else{
                            var vl = checkbox[0].value;
                            if(input.value.search(vl) == 0){
                                if(input.value == vl) input.value = '';
                                input.value = input.value.replace(vl+', ','');
                            }else{
                                input.value = input.value.replace(', '+ vl,'');
                            }
                        }
                        checkbox[0].checked =  checked;
                        grid.reloadParams['selected[]'] = input.value.split( ', ');
                    }
                }
            }
        ";
        return $js;
    }

    public function getCheckboxCheckCallback(){
        $js = ' function (grid, element, checked) {
        var input = $("'.$this->getInput().'");
        if (checked) {
            $$("#'.$this->getId().' input[type=checkbox][class=checkbox]").each(function(e){
                if(e.name != "check_all"){
                    if(!e.checked){
                        if(input.value == "")
                            input.value = e.value;
                        else
                            input.value = input.value + ", "+e.value;
                        e.checked = true;
                        grid.reloadParams["selected[]"] = input.value.split(", ");
                    }
                }
            });
        }else{
            $$("#'.$this->getId().' input[type=checkbox][class=checkbox]").each(function(e){
                if(e.name != "check_all"){
                    if(e.checked){
                        var vl = e.value;
                        if(input.value.search(vl) == 0){
                            if(input.value == vl) input.value = "";
                            input.value = input.value.replace(vl+", ","");
                        }else{
                            input.value = input.value.replace(", "+ vl,"");
                        }
                        e.checked = false;
                        grid.reloadParams["selected[]"] = input.value.split(", ");
                    }
                }
            });
                            
        }
    } ';
        return $js;
    }
    public function getRowInitCallback(){
       $js =' function (grid, row) {
            if (!grid.reloadParams) {
                grid.reloadParams["selected"] = $("'.$this->getInput().'").value;
            }
        } ';
        return $js;
    }

    
    protected function _prepareCollection() {
        $collection = Mage::getModel('campaign/popup')->getCollection();
        $collection->addFieldToFilter('popup_id', array('neq'=>$this->getRequest()->getParam('popup_id')));
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
	
    protected function _prepareColumns() {
        $this->addColumn('in_popups', array(
            'header_css_class'  => 'a-center',
            'type'              => 'radio',
            'html_name' => 'popupId',
            'width' => '40px',
            //'field_name'        => 'in_popups[]',
            'value'            => $this->getRequest()->getParam('selected'),
            'align'             => 'center',
            'index'             => 'popup_id',
            'filter'    => false,
            'sortable' => false
        ));
                
        $this->addColumn('popup_id', array(
            'header'    => Mage::helper('catalog')->__('ID'),
            'sortable'  => true,
            'width'     => '60px',
            'index'     => 'popup_id'
        ));

        $this->addColumn('title_select_popup', array(
            'header'    => Mage::helper('catalog')->__('Popup Title'),
            'align'     => 'right',
            'column_css_class' => 'small_width',
            'index'     => 'title',
        ));
        return parent::_prepareColumns();
    }
    public function getGridUrl(){
        return $this->getUrl('*/*/'.$this->getGridUrlCall(), array(
            '_current'          => true,
            'selected'   => $this->getRequest()->getParam('selected'),
            'collapse'          => null
        ));
    }
}

