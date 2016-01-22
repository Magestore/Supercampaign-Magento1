<?php

class Magestore_Campaign_Model_Select extends Varien_Object {

    static public function getOptionArray() {
        $block = Mage::getSingleton('campaign/bannerslider')->getCollection();

        $data = array();
        foreach ($block as $b) {
            $data[] = array(
                'value' => $b->getId(),
                'label' => $b->getTitle(),
            );
        }
        return $data;
    }

    static public function getOptionHash() {
        $block = Mage::getSingleton('campaign/bannerslider')->getCollection();
        $data = array();
        foreach ($block as $b) {
            $data[$b->getId()] = $b->getTitle();
        }
        return $data;
    }

}
