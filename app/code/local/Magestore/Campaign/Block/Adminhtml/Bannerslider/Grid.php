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
 * @package    Magestore_Bannerslider
 * @copyright    Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license    http://www.magestore.com/license-agreement.html
 */

/**
 * Bannerslider Grid Block
 *
 * @category    Magestore
 * @package    Magestore_Bannerslider
 * @author    Magestore Developer
 */
class Magestore_Campaign_Block_Adminhtml_Bannerslider_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {

        parent::__construct();
        $this->setId('bannersliderGrid');
        $this->setDefaultSort('bannerslider_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * prepare collection for block to display
     *
     * @return Magestore_Bannerslider_Block_Adminhtml_Bannerslider_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('campaign/bannerslider')->getCollection();
        //Zend_debug::dump($collection);die();

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare columns for this grid
     *
     * @return Magestore_Bannerslider_Block_Adminhtml_Bannerslider_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('bannerslider_id', array(
            'header' => Mage::helper('campaign')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'bannerslider_id',
        ));

        $this->addColumn('title', array(
            'header' => Mage::helper('campaign')->__('Title'),
            'align' => 'left',
            'index' => 'title',
        ));

        $this->addColumn('position', array(
            'header' => Mage::helper('campaign')->__('Position'),
            'align' => 'left',
            'index' => 'position',
            'type' => 'options',
            'options' => Mage::helper('campaign')->getOptionGridSlider()

        ));

        $this->addColumn('style_content', array(
            'header' => Mage::helper('campaign')->__('Slider\'s Mode'),
            'width' => '150px',
            'index' => 'style_content',
            'type' => 'options',
            'options' => array(
                0 => 'Standard Slider',
                1 => 'Custom Slider',
            ),

        ));

        $this->addColumn('status', array(
            'header' => Mage::helper('campaign')->__('Status'),
            'align' => 'left',
            'width' => '80px',
            'index' => 'status',
            'type' => 'options',
            'options' => array(
                0 => 'Enabled',
                1 => 'Disabled',
            ),
        ));

        $this->addColumn('action',
            array(
                'header' => Mage::helper('campaign')->__('Action'),
                'width' => '100',
                'type' => 'action',
                'getter' => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('campaign')->__('Edit'),
                        'url' => array('base' => '*/*/edit'),
                        'field' => 'id'
                    )),
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'is_system' => true,
            ));

        //zeus add generate code
        $this->addColumn('generate', array(
            'header' => Mage::helper('campaign')->__('Generate Code'),
            'width' => '100',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('campaign')->__('Generate'),
                    'onclick' => 'return showGenerateCode(this);',
                    'field' => 'id'
                )),
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
            'is_system' => true,
        ));
        //end zeus add generate code

		$this->addExportType('*/*/exportCsv', Mage::helper('campaign')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('campaign')->__('XML'));

		return parent::_prepareColumns();
	}


    protected function _afterToHtml($html) {
        $url = $this->getUrl('*/*/showGenerateCode');
        $html .= "<script>
            var hasFlash = false;
            try {
                hasFlash = Boolean(new ActiveXObject('ShockwaveFlash.ShockwaveFlash'));
            } catch(exception) {
                hasFlash = ('undefined' != typeof navigator.mimeTypes['application/x-shockwave-flash']);
            }
            var showGenerateCode = function(e){
                var id = e.up('tr').down().down().value;
                new Ajax.Request('$url',{
                    method : 'post',
                    parameters : {
                        form_key : FORM_KEY,
                        id : id
                    },
                    onComplete: function(xhr){
                        TINY.box.show('');
                        $('loading-mask').hide();
                        $('tinycontent').innerHTML = xhr.responseText;
                        // clipboard text, click to save, click save. copy button.
                        $('copy-button').dataset.clipboardText= $('clipboard-text').value;
                        var client = new ZeroClipboard(document.getElementById('copy-button'));
                        client.on('ready',function(){
                            client.on('aftercopy',function(){
                                j('#copied').fadeIn();
                                j('#copy-button').text('Copied to clipboard');
                            });
                        });
                        if(!hasFlash)
                            j('#copy-button').hide();
                    }
                });
            };
        </script>";
        return parent::_afterToHtml($html);
    }
	
	/**
	 * prepare mass action for this grid
	 *
	 * @return Magestore_Bannerslider_Block_Adminhtml_Bannerslider_Grid
	 */
	protected function _prepareMassaction(){
		$this->setMassactionIdField('bannerslider_id');
		$this->getMassactionBlock()->setFormFieldName('campaign');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('campaign')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('campaign')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('campaign/status')->getOptionArray();
        array_unshift($statuses, array('label' => '', 'value' => ''));
        $this->getMassactionBlock()->addItem('status', array(
            'label' => Mage::helper('campaign')->__('Change status'),
            'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('campaign')->__('Status'),
                    'values' => array(
                        0 => 'Enabled',
                        1 => 'Disabled',
                    )
                ))
        ));
        return $this;
    }

    /**
     * get url for each row in grid
     *
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}