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
    ALTER TABLE {$this->getTable('campaign/popup')}
        ADD COLUMN `trigger_popup` VARCHAR(20) NULL;

    ALTER TABLE {$this->getTable('campaign/template')}
        ADD COLUMN `trigger_popup` VARCHAR(20) NULL;

INSERT INTO `{$this->getTable('campaign/template')}` (`template_id`, `title`, `template_code`, `template_file`, `template_file_success`, `popup_type`, `preview_image`, `width`, `width_unit`, `corner_style`, `border_radius`, `border_color`, `border_size`, `overlay_color`, `popup_background`, `padding`, `close_style`, `has_shadow`, `appear_effect`, `custom_css`, `horizontal_position`, `horizontal_px`, `vertical_position`, `vertical_px`, `trigger_popup`) VALUES
(1, 'Template 01 - Subscribe ', '1', 'campaign/popup/subscribe/template01.phtml', 'campaign/popup/success/success01.phtml', '0', 'images/campaign/popup/template_images/template01.png', 800, 'px', 'rounded', 20, '0', 0, '000000', 'FFF0F8', 0, 'simple', 1, 'top', NULL, 'center', 100, 'top', 100, NULL),
(2, 'Template 02 - Subscribe ', '2', 'campaign/popup/subscribe/template02.phtml', 'campaign/popup/success/success02.phtml', '0', 'images/campaign/popup/template_images/template02.png', 800, 'px', 'rounded', 20, '', 0, '000000', 'FFF5F8', 0, 'simple', 0, 'top', NULL, 'center', 100, 'top', 100, NULL),
(3, 'Template 03 - Subscribe', '3', 'campaign/popup/subscribe/template03.phtml\r\n', 'campaign/popup/success/success03.phtml', '0', 'images/campaign/popup/template_images/template03.png', 700, 'px', 'circle', 10, '', 0, '000000', 'ffffff', 0, '', 0, 'top', NULL, 'center', 100, 'top', 100, NULL),
(4, 'Template 04 - Subscribe', '4', 'campaign/popup/subscribe/template04.phtml\r\n', 'campaign/popup/success/success04.phtml', '0', 'images/campaign/popup/template_images/template04.png', 500, 'px', 'rounded', 20, '', 0, '000000', 'BBAEAE', 20, 'circle', 0, 'bottom', NULL, 'center', 100, 'bottom', 100, NULL),
(5, 'Template 05 - Subscribe', '5', 'campaign/popup/subscribe/template05.phtml\r\n', 'campaign/popup/success/success05.phtml', '0', 'images/campaign/popup/template_images/template05.png', 420, 'px', 'circle', 0, '', 0, '000000', '6E6E6E', 20, 'circle', 0, 'top', NULL, 'center', 100, '', 100, NULL),
(6, 'Template 06 - Subscribe', '6', 'campaign/popup/subscribe/template06.phtml\r\n', 'campaign/popup/success/success06.phtml', '0', 'images/campaign/popup/template_images/template06.png', 700, 'px', 'rounded', 20, '', 0, '000000', 'ffffff', 0, 'cricle', 0, 'top', NULL, 'center', 100, 'top', 100, NULL),
(7, 'Template 07 - Promotion in bottom', '7', 'campaign/popup/promotion/template07.phtml', 'campaign/popup/success/success07.phtml', '0', 'images/campaign/popup/template_images/template07.png', 800, '', 'rounded', 20, '', 0, 'no_bg_fix_popup', 'ffffff', 0, 'circle', 0, 'bottom', NULL, 'center', 100, 'bottom', 0, NULL),
(8, 'Template 09 - Social left bottom', '9', 'campaign/popup/social/template09.phtml', 'campaign/popup/success/success09.phtml', '0', 'images/campaign/popup/template_images/template09.png', 300, '', 'rounded', 20, '', 0, 'no_bg_fix_popup', 'ffffff', 20, 'simple', 0, 'bottom', NULL, 'left', 10, 'bottom', 0, NULL),
(9, 'Template 10 - Popup title left bottom', '10', 'campaign/popup/subscribe/template10.phtml', 'campaign/popup/success/success10.phtml', '0', 'images/campaign/popup/template_images/template10.png', 600, '', 'sharp', 0, '', 0, 'no_bg_fix_popup', 'ffffff', 0, 'cricle', 0, 'bottom', NULL, 'left', 0, 'bottom', 0, NULL),
(10, 'Template -12 ', '12', 'campaign/popup/sticker/template12.phtml', 'campaign/popup/success/success12.phtml', '0', 'images/campaign/popup/template_images/template12.png', 150, 'px', 'sharp', 2, 'FFD45E', 0, 'no_bg_fix_popup', 'EA2EFF', 20, 'simple', 0, 'bottom', NULL, 'left', 0, 'bottom', 0, NULL),
(11, 'Template 11 - Video', '', 'campaign/popup/video/template11.phtml', 'campaign/popup/success/success11.phtml', '0', 'images/campaign/popup/template_images/template11.png', 600, 'px', 'rounded', 20, '', 0, 'dark', 'ffffff', 20, 'circle', 0, 'top', NULL, 'center', 100, 'top', 100, NULL),
(12, 'Template 13 - Register', '', 'campaign/popup/register/template13.phtml', 'campaign/popup/success/success13.phtml', '0', 'skin/frontend/base/default/images/campaign/popup/template_images/template13.png', 500, '', 'rounded', 0, '20', 0, 'dark', 'ffffff', 20, 'circle', 0, 'top', NULL, 'center', 100, 'top', 100, NULL);

");

$installer->getConnection()->addColumn($this->getTable('newsletter_subscriber'), 'campaign_id', 'int(11) NULL');
$installer->getConnection()->addColumn($this->getTable('newsletter_subscriber'), 'campaign_name', 'varchar(255) NULL');

$installer->endSetup();

