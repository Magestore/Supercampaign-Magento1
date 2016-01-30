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



DROP TABLE IF EXISTS {$this->getTable('campaign/banner')};
DROP TABLE IF EXISTS {$this->getTable('campaign/bannerslider')};
DROP TABLE IF EXISTS {$this->getTable('campaign/value')};
DROP TABLE IF EXISTS {$this->getTable('campaign/popup')};
DROP TABLE IF EXISTS {$this->getTable('campaign/template')};

CREATE TABLE {$this->getTable('campaign/bannerslider')} (
  `bannerslider_id` int(11) unsigned NOT NULL auto_increment,
  `campaign_id` int(11) unsigned NOT NULL,
  `title` varchar(255) NOT NULL default '',
  `position` varchar(255) NULL ,
  `show_title` tinyint(1) NULL default '0',
  `status` smallint(6) NOT NULL default '0',
  `sort_type` int(11) NULL,
  `description` text NULL,
  `category_ids` varchar(255) NULL,
  `style_content` smallint(6) NOT NULL default '0',
  `custom_code` text NULL,
  `style_slide` varchar(255) NULL,
  `width` float(10) NULL,
  `height` float(10) NULL,
  `note_color` varchar(40) NULL,
  `animationB` varchar(255) NULL,
  `caption` smallint(6) NULL,
  `position_note` int (11) NULL default '1',
  `slider_speed` float (10) NULL,
  `url_view` varchar(255) NULL,
  `start_time` datetime NULL,
  `end_time` datetime NULL,
  `min_item` int(11) NULL,
  `max_item` int(11) NULL,
  PRIMARY KEY (`bannerslider_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


CREATE TABLE {$this->getTable('campaign/banner')} (
  `banner_id` int(11) unsigned NOT NULL auto_increment,
  `campaign_id` int(11) unsigned NOT NULL,
  `name` varchar(255) NOT NULL default '',
  `order_banner` int(11) NULL default '0',
  `bannerslider_id` int(11) NULL,
  `status` smallint(6) NOT NULL default '0',
  `click_url` varchar(255) NULL default '',
  `imptotal` int(11)  NULL default '0',
  `clicktotal` int(11)  NULL default '0',
  `tartget` int(11) NULL default '0',
  `image`	varchar(255) NULL,
  `image_alt`	varchar(255) NULL,
  `width`	float(10) NULL,
  `height`	float(10) NULL,
  `start_time` datetime NULL,
  `end_time` datetime NULL,
  PRIMARY KEY (`banner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;



CREATE TABLE {$this->getTable('campaign/value')} (
  `value_id` int(10) unsigned NOT NULL auto_increment,
  `banner_id` int(11) unsigned NOT NULL,
  `store_id` smallint(5) unsigned  NOT NULL,
  `attribute_code` varchar(63) NOT NULL default '',
  `value` text NOT NULL,
  UNIQUE(`banner_id`,`store_id`,`attribute_code`),
  INDEX (`banner_id`),
  INDEX (`store_id`),
  FOREIGN KEY (`banner_id`) REFERENCES {$this->getTable('campaign/banner')} (`banner_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`store_id`) REFERENCES {$this->getTable('core/store')} (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  PRIMARY KEY (`value_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;



CREATE TABLE {$this->getTable('campaign/popup')} (
  `popup_id` int(11) unsigned NOT NULL auto_increment,
  `campaign_id` int(11) NULL,
  `template_code` varchar(20) NOT NULL default '',
  `title` text NULL default '',
  `popup_type` varchar(255) NOT NULL default '',
  `preview_image` text NULL default '',
  `width` smallint(6) NULL default 0,
  `width_unit` varchar(255) NULL default '',
  `corner_style` varchar(255) NULL default '',
  `border_radius` smallint(6) NULL DEFAULT 0,
  `border_color` varchar(255) NULL default '',
  `border_size` smallint(6) NULL default 0,
  `overlay_color` varchar(255) NULL default '',
  `popup_background` varchar(6) NULL default '',
  `padding` smallint(6) NULL default 0,
  `close_style` varchar(255) NULL default '',
  `has_shadow` smallint(6) NULL default 0,
  `appear_effect` varchar(255) NULL default '',
  `custom_css` text NULL default '',
  `horizontal_position` varchar(255) NULL default '',
  `horizontal_px` int(11) NULL default 100,
  `vertical_position` varchar(255) NULL default '',
  `vertical_px` int(11) NULL default 100,

  `store` varchar(255) NOT NULL default '',
  `show_on_page` varchar(255) NOT NULL default '',
  `categories` varchar(255) NOT NULL default '',
  `specified_url` text NULL default '',
  `exclude_url` text NULL default '',
  `products` varchar(255) NOT NULL default '',
  `show_when` varchar(255) NOT NULL default '',
  `seconds_delay` smallint(6) NULL default 0,
  `showing_frequency` varchar(255) NULL default '',
  `cookie_time` varchar(255) NULL default '',
  `max_time` varchar(255) NULL default '',
  `priority` int(11) NULL default 0,
  `country` varchar(255) NULL default '',
  `devices` varchar(255) NULL default '',
  `cookies_enabled` smallint(6) NULL default 0,
  `returning_user` varchar(255) NULL default '',
  `login_user` varchar(255) NULL default '',
  `customer_group_ids` varchar(255) NULL default '',
  `user_ip` varchar(30) NULL default '',
  `status` smallint(6) NULL default 0,
  `show_next_popup` varchar(255) NOT NULL default '',
  `connect_with` varchar(255) NOT NULL default '',
  `popup_content` text NOT NULL default '',
  PRIMARY KEY (`popup_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;



CREATE TABLE {$this->getTable('campaign/template')} (
  `template_id` int(11) unsigned NOT NULL auto_increment,
  `title` text NULL default '',
  `template_code` varchar(20) NOT NULL default '',
  `template_file` text NULL default '',
  `popup_type` varchar(255) NOT NULL default '0',
  `preview_image` text NULL default '',
  `width` smallint(6) NULL default 0,
  `width_unit` varchar(255) NULL default '',
  `corner_style` varchar(255) NULL default '',
  `border_radius` smallint(6) NULL DEFAULT 0,
  `border_color` varchar(255) NULL default '',
  `border_size` smallint(6) NULL default 0,
  `overlay_color` varchar(255) NULL default '',
  `popup_background` varchar(6) NULL default '',
  `padding` smallint(6) NULL default 0,
  `close_style` varchar(255) NULL default '',
  `has_shadow` smallint(6) NULL default 0,
  `appear_effect` varchar(255) NULL default '',
  `custom_css` text NULL default '',
  `horizontal_position` varchar(255) NULL default '',
  `horizontal_px` int(11) NULL default 100,
  `vertical_position` varchar(255) NULL default '',
  `vertical_px` int(11) NULL default 100,
  PRIMARY KEY (`template_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;



INSERT INTO `{$this->getTable('campaign/template')}` (`template_id`, `title`, `popup_type`, `template_code`, `template_file`, `preview_image`, `width`, `width_unit`, `corner_style`, `border_radius`, `border_color`, `border_size`, `overlay_color`, `popup_background`, `padding`, `close_style`, `has_shadow`, `appear_effect`, `custom_css`, `horizontal_position`, `horizontal_px`, `vertical_position`, `vertical_px`) VALUES
(1, 'Template 01 - Subscribe ', '0', '1', 'campaign/popup/subscribe/template01.phtml', 'images/campaign/popup/template_images/template01.png', 800, 'px', 'rounded', 20, '0', 0, '000000', 'FFF0F8', 0, 'simple', 1, 'top', NULL, 'center', 100, 'top', 100),
(2, 'Template 02 - Subscribe ', '0', '2', 'campaign/popup/subscribe/template02.phtml', 'images/campaign/popup/template_images/template02.png', 800, 'px', 'rounded', 20, '', 0, '000000', 'FFF5F8', 0, 'simple', 0, 'top', NULL, 'center', 100, 'top', 100),
(3, 'Template 03 - Subscribe', '0', '3', 'campaign/popup/subscribe/template03.phtml\r\n', 'images/campaign/popup/template_images/template03.png', 700, 'px', 'circle', 10, '', 0, '000000', 'ffffff', 0, '', 0, 'top', NULL, 'center', 100, 'top', 100),
(4, 'Template 04 - Subscribe', '0', '4', 'campaign/popup/subscribe/template04.phtml\r\n', 'images/campaign/popup/template_images/template04.png', 500, 'px', 'rounded', 20, '', 0, '000000', 'BBAEAE', 20, 'circle', 0, 'bottom', NULL, 'center', 100, 'bottom', 100),
(5, 'Template 05 - Subscribe', '0', '5', 'campaign/popup/subscribe/template05.phtml\r\n', 'images/campaign/popup/template_images/template05.png', 600, 'px', 'circle', 0, '', 0, '000000', '6E6E6E', 20, 'circle', 0, 'top', NULL, 'center', 100, 'top', 100),
(6, 'Template 06 - Subscribe', '0', '6', 'campaign/popup/subscribe/template06.phtml\r\n', 'images/campaign/popup/template_images/template06.png', 700, 'px', 'rounded', 20, '', 0, '000000', 'ffffff', 0, 'cricle', 0, 'top', NULL, 'center', 100, 'top', 100);

");



$installer->endSetup();

