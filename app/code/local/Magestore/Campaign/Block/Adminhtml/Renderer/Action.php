<?php


class Magestore_Campaign_Block_Adminhtml_Renderer_Action
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
        $html = '';
        $values = $this->getColumn()->getValues();
        foreach($values as $option){
            $valueIndex = $row->getData($this->getColumn()->getIndex());
            $url = isset($option['url']) ? $option['url'] : '';
            if(is_array($url)){
                $params1 = isset($url[0]) ? $url[0] : '';
                $params2 = isset($url[1]) ? $url[1] : array();
                if(isset($option['name'])){
                    $params2[$option['name']] = $valueIndex;
                }
                $href = $this->getUrl($params1, $params2);
            }else{
                $href = $url;
                if(isset($option['name'])){
                    $href .= $option['name'].'='.$valueIndex;
                }
            }
            $onclick = (isset($option['onclick']))? $option['onclick']:'';
            $caption = (isset($option['caption']))? $option['caption']:'';
            $callback = (isset($option['callback']))? $option['callback']:'';
            if($callback){
                $html .= '<a href="#" onclick="'.$onclick.'; return '.$callback.'('.$valueIndex.');">'.$caption.'</a> | ';
            }elseif($onclick){
                $html .= '<a href="#" onclick="'.$onclick.'">'.$caption.'</a> | ';
            }else{
                $html .= '<a href="'.$href.'">'.$caption.'</a> | ';
            }
        }
        $html = trim($html, ' | ');
        return $html;
    }


}
