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

/** @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->run("

INSERT INTO `{$this->getTable('campaign/template')}` (`template_id`, `title`, `template_code`, `template_file`, `template_file_success`, `popup_type`, `preview_image`, `width`, `width_unit`, `corner_style`, `border_radius`, `border_color`, `border_size`, `overlay_color`, `popup_background`, `padding`, `close_style`, `has_shadow`, `appear_effect`, `custom_css`, `horizontal_position`, `horizontal_px`, `vertical_position`, `vertical_px`, `trigger_popup`) VALUES
(13, 'Template 15 - Subscribe ', '15', 'campaign/popup/subscribe/template15.phtml', 'campaign/popup/success/success15.phtml', '0', 'images/campaign/popup/template_images/template15.png', 800, 'px', 'rounded', 20, '', 0, '000000', 'FFF5F8', 0, 'simple', 0, 'top', NULL, 'center', 100, 'top', 100, NULL),
(14, 'Template 16 - Subscribe', '16', 'campaign/popup/subscribe/template16.phtml\r\n', 'campaign/popup/success/success16.phtml', '0', 'images/campaign/popup/template_images/template16.png', 700, 'px', 'rounded', 10, '', 0, '000000', 'ffffff', 0, '', 0, 'top', NULL, 'center', 100, 'top', 100, NULL),
(15, 'Template 17 - Subscribe', '17', 'campaign/popup/subscribe/template17.phtml\r\n', 'campaign/popup/success/success17.phtml', '0', 'images/campaign/popup/template_images/template17.jpg', 700, 'px', 'rounded', 10, '', 0, '000000', 'ffffff', 0, '', 0, 'top', NULL, 'center', 100, 'top', 100, NULL);
");

$installer->endSetup();

