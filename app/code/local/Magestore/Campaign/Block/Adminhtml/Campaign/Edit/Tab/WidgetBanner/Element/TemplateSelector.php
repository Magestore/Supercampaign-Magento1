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
 * @category   Varien
 * @package    Varien_Data
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Form text element
 *
 * @category   Varien
 * @package    Varien_Data
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Magestore_Campaign_Block_Adminhtml_Campaign_Edit_Tab_WidgetBanner_Element_TemplateSelector extends Varien_Data_Form_Element_Abstract
{
    public function __construct($attributes=array())
    {
        parent::__construct($attributes);
        $this->setType('template_selector');
        $this->setExtType('template_selector');
    }

    public function getHtml()
    {
        $this->addClass('template-selector');
        return parent::getHtml();
    }

    public function getElementHtml()
    {

        $html = '<div id="'.$this->getHtmlId().'"'.$this->serialize($this->getHtmlAttributes()).'>'."\n";
        $html .= '<div id="template-carousel-'.$this->getHtmlId().'" class="owl-carousel">'."\n";

        $value = $this->getValue();
        if (!is_array($value)) {
            $value = array($value);
        }
        $active = '';
        if ($values = $this->getValues()) {
            foreach ($values as $key => $option) {
                if($value == $option['value']){
                    $active = 'active';
                }
                $html.='<div id="item-'.$option['value'].'" class="item '.$active.'" onclick="'
                    .$this->getCallbackFunc().'(\''.$option['value'].'\');'
                    .'"><img src="'.$option['image'].'" alt="'.$option['label'].'" /><span>'
                    .$option['label'].'</span></div>'."\n";
            }
        }
        $html .= '</div>'."\n";

        $html .= $this->getAfterElementHtml();

        $html .= '<script>
    var $j = jQuery.noConflict();
    $j(document).ready(function() {
        $j("#template-carousel-'.$this->getHtmlId().'").owlCarousel({
            navigation : true, // Show next and prev buttons
            slideSpeed : 500,
            paginationSpeed : 500,
            items: 4,
            autoPlay: false
        });
        if(!$j(".template-selector .owl-theme .owl-controls").is(":visible")){
            $j(".template-selector .owl-theme").addClass("owl-no-control");
        }
    });
</script>';

        return $html;
    }
}
