<?php

class Magestore_Campaign_Block_Adminhtml_Renderer_Imagebanner extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {
    /* Render Grid Column */

    public function render(Varien_Object $row) {
        $image_id = $row->getId();
        $image = Mage::getModel('campaign/banner')->load($image_id)->getImage();
        $imagename = Mage::helper('campaign')->getBannerImage($image);
        return
                '<img id="image_banner' . $image_id . '" src="' . $imagename . '" width="50px" height="50px"/>'.
                '<script type="text/javascript">
                    new Tooltip("image_banner'.$image_id.'", "'.$imagename.'");
                    $$(".tooltip img").each(function(item){
                        item.style.width="300px";
                         item.style.height="300px";
                    });
                </script>'
                
        ;
    }

}