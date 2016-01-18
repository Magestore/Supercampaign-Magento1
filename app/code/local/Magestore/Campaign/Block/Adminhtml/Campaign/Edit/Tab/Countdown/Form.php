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
 * Campaign Edit Form Content Tab Block
 * 
 * @category    Magestore
 * @package     Magestore_Campaign
 * @author      Magestore Developer
 */
class Magestore_Campaign_Block_Adminhtml_Campaign_Edit_Tab_Countdown_Form
    extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare tab form's information
     *
     * @return Magestore_Campaign_Block_Adminhtml_Campaign_Edit_Tab_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $data = array();

        if (Mage::getSingleton('adminhtml/session')->getCountdownData()) {
            $data = Mage::getSingleton('adminhtml/session')->getCountdownData();
            Mage::getSingleton('adminhtml/session')->setCountdownData(null);
        } elseif (Mage::registry('countdown_data')) {
            $data = Mage::registry('countdown_data')->getData();
        }

        $fieldset = $form->addFieldset('campaign_form', array(
            'legend'=>Mage::helper('campaign')->__('Countdown page information')
        ));

        $fieldset->addField('countdown_status', 'select', array(
            'label'		=> Mage::helper('campaign')->__('Status:'),
            'name'		=> 'countdown_status',
            //'required'  => false,
            'values'    => Mage::getSingleton('campaign/countdown_status')->getOptionHash(),
        ));

        $countDownType = $fieldset->addField('countdown_type_countdown', 'select', array(
            'label'		=> Mage::helper('campaign')->__('Type Countdown:'),
            'name'		=> 'countdown_type_countdown',
            //'required'  => false,
            'values'    => Mage::getSingleton('campaign/countdown_type')->getOptionHash(),
            //'onchange' => 'changeCountdownType(this)',
            'after_element_html' => '
                <script type="text/javascript">
                function changeCountdownType(value){
                    if(value.value == "'.Magestore_Campaign_Model_Countdown_Type::NORMAL.'"){
                        if($("'.Magestore_Campaign_Block_Adminhtml_Campaign_Edit_Tab_Countdown_Grid::GRID_ID.'")){
                            var inputs = $("'.Magestore_Campaign_Block_Adminhtml_Campaign_Edit_Tab_Countdown_Grid::GRID_ID.'").select("input");
                            inputs.each(function(element){
                                element.writeAttribute("disabled", "disabled");
                            });
                            var buttons = $("'.Magestore_Campaign_Block_Adminhtml_Campaign_Edit_Tab_Countdown_Grid::GRID_ID.'").select("button");
                            buttons.each(function(element){
                                element.addClassName("disabled");
                                //Event.stopObserving(element, "click");
                                var clickEvt = element.readAttribute("onclick");
                                element.removeAttribute("onclick");
                                element.writeAttribute("onclickEvt", clickEvt);
                            });
                            var onclicks = $("'.Magestore_Campaign_Block_Adminhtml_Campaign_Edit_Tab_Countdown_Grid::GRID_ID.'").select("[onclick]");
                            onclicks.each(function(element){
                                element.addClassName("disabled");
                                //Event.stopObserving(element, "click");
                                var clickEvt = element.readAttribute("onclick");
                                element.removeAttribute("onclick");
                                element.writeAttribute("onclickEvt", clickEvt);
                            });
                            var onchange = $("'.Magestore_Campaign_Block_Adminhtml_Campaign_Edit_Tab_Countdown_Grid::GRID_ID.'").select("[onchange]");
                            onchange.each(function(element){
                                element.addClassName("disabled");
                                //element.addEventListener("click", );
                                var onchangeEvt = element.readAttribute("onchange");
                                element.removeAttribute("onchange");
                                element.writeAttribute("onchangeEvt", onchangeEvt);
                                element.writeAttribute("disabled", "disabled");
                            });
                        }
                    }else{
                        if($("'.Magestore_Campaign_Block_Adminhtml_Campaign_Edit_Tab_Countdown_Grid::GRID_ID.'")){
                            var inputs = $("'.Magestore_Campaign_Block_Adminhtml_Campaign_Edit_Tab_Countdown_Grid::GRID_ID.'").select("input");
                            inputs.each(function(element){
                                element.removeAttribute("disabled");
                            });
                            var buttons = $("'.Magestore_Campaign_Block_Adminhtml_Campaign_Edit_Tab_Countdown_Grid::GRID_ID.'").select("button");
                            buttons.each(function(element){
                                element.removeClassName("disabled");
                                var clickEvt = element.readAttribute("onclickEvt");
                                if(clickEvt){
                                    element.removeAttribute("onclickEvt");
                                    element.writeAttribute("onclick", clickEvt);
                                }
                            });
                            var onclicks = $("'.Magestore_Campaign_Block_Adminhtml_Campaign_Edit_Tab_Countdown_Grid::GRID_ID.'").select("[onclickEvt]");
                            onclicks.each(function(element){
                                element.removeClassName("disabled");
                                //Event.stopObserving(element, "click");
                                var clickEvt = element.readAttribute("onclickEvt");
                                if(clickEvt){
                                    element.removeAttribute("onclickEvt");
                                    element.writeAttribute("onclick", clickEvt);
                                }
                            });
                            var onchange = $("'.Magestore_Campaign_Block_Adminhtml_Campaign_Edit_Tab_Countdown_Grid::GRID_ID.'").select("[onchangeEvt]");
                            onchange.each(function(element){
                                element.removeClassName("disabled");
                                var onchangeEvt = element.readAttribute("onchangeEvt");
                                if(onchangeEvt){
                                    element.removeAttribute("onchangeEvt");
                                    element.writeAttribute("onchange", onchangeEvt);
                                }
                                element.removeAttribute("disabled");
                            });
                        }
                    }
                }
</script>
            '
        ));

        $priceStart = $fieldset->addField('countdown_price_start', 'text', array(
            'name'        => 'countdown_price_start',
            'label'        => Mage::helper('campaign')->__('Price Start:'),
            //'class'        => 'required-entry',
            'required'    => false,
        ));

        $downPrice = $fieldset->addField('countdown_down_price', 'text', array(
            'name'        => 'countdown_down_price',
            'label'        => Mage::helper('campaign')->__('Change per day:'),
            //'class'        => 'required-entry',
            'required'    => false,
            'note'  => '0 is no change'
        ));

//        $fieldset->addField('countdown_link_attached', 'text', array(
//            'name'        => 'countdown_link_attached',
//            'label'        => Mage::helper('campaign')->__('Link attached:'),
//            //'class'        => 'required-entry',
//            'required'    => false,
//        ));

        $fieldset->addField('countdown_showcountdown', 'multiselect', array(
            'label'     => $this->__('Location showing up'),
            'required'  => false,
            'name'      => 'countdown_showcountdown',
            'onclick'   => "return false;",
            'onchange'  => "return false;",
            'disabled'  => false,
            'readonly'  => false,
            'value'     => '4',
            'values'    => array(//fix by Tit
                array(
                    'label'=> Mage::helper('campaign')->__('in:'),
                    'value'=> array(
                        array('value'=>'2' , 'label' => 'Product'),
                        array('value'=>'1' , 'label' => 'Header'),
                        array('value'=>'3' , 'label' => 'Sidebar'),
                        array('value'=>'4' , 'label' => 'Popup'),
                    )
                )
            ),
            'tabindex'  => 10
            ));

        /*$fieldset->addField('countdown_product_id', 'select', array(
            'name'      => 'countdown_product_id',
            'label'     => $this->__('Product sale off'),
            'required'  => false,
            //'value'     => '1',
            'values'    => Mage::getSingleton('campaign/typecountdown')->getProducts(),
            'disabled'  => false,
            'readonly'  => false,
            'onclick'   => "",
            'onchange'  => "",
            //'tabindex'  => 1
        ));*/

        /*$fieldset->addField('countdown_product_id', 'label', array(
            'label'        => Mage::helper('campaign')->__('Overview'),
            'value'        => Mage::helper('campaign')->__('Overview'),
            'after_element_html'    => $this->getLayout()->createBlock('campaign/adminhtml_campaign_edit_tab_countdown_grid')->toHtml(),
        ));*/



        //$this->setChild('grid_product', $this->getLayout()->createBlock('campaign/adminhtml_campaign_edit_tab_countdown_grid'));

        // field dependencies
        $this->setChild('form_after', $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
            ->addFieldMap($countDownType->getHtmlId(), $countDownType->getName())
            ->addFieldMap($downPrice->getHtmlId(), $downPrice->getName())
            ->addFieldMap($priceStart->getHtmlId(), $priceStart->getName())
            ->addFieldDependence(
                $downPrice->getName(),
                $countDownType->getName(),
                2)
            ->addFieldDependence(
                $priceStart->getName(),
                $countDownType->getName(),
                2)
        );


        $fieldsetGrid = $form->addFieldset('campaign_countdown_grid', array(
            'legend'=>Mage::helper('campaign')->__('Select Products for Campaign')
        ));

        $fieldsetGrid->setNoContainer(true);
        $fieldsetGrid->setHtmlContent($this->getLayout()->createBlock('campaign/adminhtml_campaign_edit_tab_countdown_grid')->toHtml().'
            <script type="text/javascript">//changeCountdownType($("countdown_type_countdown"));</script>
        ');


        $howto = $form->addFieldset('campaign_howto', array(
            'legend'=>Mage::helper('campaign')->__('How to use')
        ));
        $howto->setNoContainer(true)->setHtmlContent($this->getLayout()->createBlock('campaign/countdown')->setTemplate('campaign/countdown_how_to_use.phtml')->toHtml());

        $form->setValues($data);
        return parent::_prepareForm();
    }
}