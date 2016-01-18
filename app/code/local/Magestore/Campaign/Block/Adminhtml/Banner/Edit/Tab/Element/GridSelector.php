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
class Magestore_Campaign_Block_Adminhtml_Banner_Edit_Tab_Element_GridSelector extends Varien_Data_Form_Element_Abstract
{
    public function __construct($attributes=array())
    {
        parent::__construct($attributes);
        $this->setType('widget_grid');
        $this->setExtType('widget_grid');
    }

    public function getHtml()
    {
        $this->addClass('widget-grid');
        return parent::getHtml();
    }

    public function getElementHtml()
    {
        $grid = Mage::getModel('core/layout')->createBlock('campaign/adminhtml_banner_edit_tab_widgetgrid');
        $serialer = Mage::getModel('core/layout')->createBlock('adminhtml/widget_grid_serializer');
        $grid->setSerialName($this->getName());
        $serialer->initSerializerBlock($grid, 'getSerializeData', $this->getName(), 'widget_reloaded_ids');
        $html = '<div id="'.$this->getHtmlId().'"'.$this->serialize($this->getHtmlAttributes()).'>'."\n";
        $required = '';
        if($this->getRequired()){
            $required = '<input id="grid-select-'.$serialer->getInputElementName().'" name="grid_serialize" value="" type="hidden" class="input-text required-entry"/>';
            $required .= '<script type="text/javascript">
    Event.observe($$("input[name='.$serialer->getInputElementName().']")[0], "change", function(e){
        $("grid-select-'.$serialer->getInputElementName().'").value(e.value);
    });
</script>';
        }
        $html .= $grid->toHtml().$serialer->toHtml().'</div>'."\n";
        $html .= $this->getAfterElementHtml();
        return $html;
    }
}
