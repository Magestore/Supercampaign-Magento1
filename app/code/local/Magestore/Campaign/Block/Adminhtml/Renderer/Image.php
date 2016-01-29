<?php


class Magestore_Campaign_Block_Adminhtml_Renderer_Image
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * Renders grid column
     *
     * @param   Varien_Object $row
     * @return  string
     */
    public function render(Varien_Object $row)
    {
        $html = '<img src="'.$this->getSkinUrl($row->getData($this->getColumn()->getIndex()),
                array('_area'=>'frontend')).'" alt="'.$row->getData($this->getColumn()->getAlt()).'"
                width="'.$this->getColumn()->getWidth().'" />';
        return $html;
    }


}
