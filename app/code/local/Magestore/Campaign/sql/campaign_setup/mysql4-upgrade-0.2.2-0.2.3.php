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
  `title` varchar(255) NOT NULL default '',
  `popup_type` varchar(255) NOT NULL default '',
  `show_next_popup` varchar(255) NOT NULL default '',
  `connect_with` varchar(255) NOT NULL default '',
  `popup_content` text NOT NULL default '',
  `width` smallint(6) NULL default 0,
  `width_unit` smallint(6) NULL default 0,
  `status` smallint(6) NULL default 0,
  `store` varchar(255) NOT NULL default '',
  `show_on_page` varchar(255) NOT NULL default '',
  `categories` varchar(255) NOT NULL default '',
  `specified_url` text NULL default '',
  `exclude_url` text NULL default '',
  `products` varchar(255) NOT NULL default '',
  `show_when` varchar(255) NOT NULL default '',
  `seconds_delay` smallint(6) NULL default 0,
  `corner_style` varchar(255) NOT NULL default '',
  `corner_radius` smallint(6) NULL DEFAULT 0,
  `border_color` varchar(255) NULL default '',
  `border_radius` smallint(6) NULL default 0,
  `border_size` smallint(6) NULL default 0,
  `overlay_color` varchar(255) NULL default '',
  `popup_background` varchar(255) NULL default '',
  `padding` smallint(6) NULL default 0,
  `close_style` varchar(255) NULL default '',
  `has_shadow` smallint(6) NULL default 0,
  `appear_effect` varchar(255) NULL default '',
  `custom_css` text NULL default '',
  `multipopup` smallint(6) NULL default 0,
  `showing_frequency` varchar(255) NULL default '',
  `cookie_time` varchar(255) NULL default '',
  `max_time` varchar(255) NULL default '',
  `priority` int(11) NULL default 0,
  `horizontal_position` varchar(255) NULL default '',
  `horizontal_px` int(11) NULL default 100,
  `vertical_position` varchar(255) NULL default '',
  `vertical_px` int(11) NULL default 100,
  `country` varchar(255) NULL default '',
  `devices` varchar(255) NULL default '',
  `cookies_enabled` smallint(6) NULL default 0,
  `returning_user` varchar(255) NULL default '',
  `login_user` varchar(255) NULL default '',
  `customer_group_ids` varchar(255) NULL default '',
  `user_ip` varchar(30) NULL default '',
  `template_code` varchar(20) NOT NULL default '',
  PRIMARY KEY (`popup_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;



CREATE TABLE {$this->getTable('campaign/template')} (
  `template_id` int(11) unsigned NOT NULL auto_increment,
  `title` text NULL default '',
  `popup_type` varchar(6) NULL default '0',
  `template_code` varchar(20) NOT NULL default '',
  `template_file` text NULL default '',
  `preview_image` text NULL default '',
  `width` smallint(6) NULL default 0,
  `width_unit` varchar(255) NULL default '',
  `corner_style` varchar(255) NULL default '',
  `corner_radius` smallint(6) NULL DEFAULT 0,
  `border_color` varchar(255) NULL default '',
  `border_size` smallint(6) NULL default 0,
  `overlay_color` varchar(255) NULL default '',
  `popup_background` varchar(6) NULL default '',
  `padding` smallint(6) NULL default 0,
  `close_style` varchar(255) NULL default '',
  `has_shadow` smallint(6) NULL default 0,
  `appear_effect` varchar(255) NULL default '',
  `custom_css` text NULL default '',
  `position_to` varchar(255) NULL default '',
  `position_px` varchar(255) NULL default '',
  `horizontal_position` varchar(255) NULL default '',
  `horizontal_px` int(11) NULL default 100,
  `vertical_position` varchar(255) NULL default '',
  `vertical_px` int(11) NULL default 100,
  `position_element_id` varchar(255) NULL default '',
  PRIMARY KEY (`template_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

");



$installer->endSetup();

