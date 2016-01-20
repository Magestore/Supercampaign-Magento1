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
  `min_item` int(11) NULL,
  `max_item` int(11) NULL,
  INDEX(`campaign_id`),
  CONSTRAINT `bannerslider_refer_campaign` FOREIGN KEY (`campaign_id`)
    REFERENCES {$this->getTable('campaign/campaign')} (`campaign_id`) ON UPDATE CASCADE ON DELETE CASCADE,
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
  `campaign_id` int(11) unsigned NOT NULL,
  `title` varchar(255) NOT NULL default '',
  `popup_type` smallint(6) NULL default '0',
  `width` smallint(6) NULL,
  `width_unit` smallint(6) NULL default '0',
  `height` smallint(6) NULL,
  `max_width` smallint(6) NULL default '0',
  `close_effect` smallint(6) NULL default '0',
  `cookie_time` float(10) NULL default '0',
  `max_count_time` int(10) NULL default '0',
  `cookie_id` varchar(55) NULL default '',
  `showing_frequency` smallint(6) NULL default '0',
  `popup_content` text NOT NULL default '',
  `popup_content_parse`  text NULL default '',
  `image`  text NULL default '',
  `url`  text NULL default '',
  `show_when` smallint(6) NULL default '0',
  `close_on_hoverout` smallint(6) NULL default '0',
  `scroll_px` int(11) NULL,
  `hover_selector` varchar (100) NULL default '',
  `click_selector` varchar(100) NULL default '',
  `seconds_delay` int(11) NULL,
  `specified_url` varchar(255) NULL default '',
  `if_referral` varchar(255) NULL default '',
  `if_returning` int(11) NULL,
  `num_visited_pages` int(11) NULL,
  `specified_not_url` varchar(255) NULL default '',
  `border_color` varchar(255) NULL default '',
  `border_radius` smallint(6) NULL default '0',
  `border_size` smallint(6) NULL default '0',
  `padding` smallint(6) NULL default '0',
  `background_color` smallint(6) NULL default '0',
  `popup_background` varchar(255) NULL default '',
  `status` smallint(6) NULL default '0',
  `views` int(11) NULL,
  `popup_closed` int(11) NULL,
  `click_inside` int(11) NULL,
  `goal_complition` int(11) NULL,
  `window_closed` int(11) NULL,
  `last_rand_id` varchar(255) NULL default '',
  `page_reloaded` int(11) NULL,
  `total_time` int(11) NULL,
  `horizontal_position` smallint(6) NULL default '0',
  `horizontal_position_px` int(11) NULL,
  `vertical_position` smallint(6) NULL default '0',
  `vertical_position_px` int(11) NULL,
  `element_id_position` varchar(255) NULL default '',
  `close_style` smallint(6) NULL default '0',
  `close_on_overlayclick` smallint(6) NULL default '0',
  `close_on_timeout` smallint(6) NULL default '0',
  `custom_css` text NULL default '',
  `custom_script` text NULL default '',
  `devices` smallint(6) NULL default '0',
  `user_login` smallint(6) NULL default '0',
  `cookies_enabled` smallint(6) NULL default '0',
  `priority` smallint(6) NULL default '0',
  `stop_further` smallint(6) NULL default '0',
  `if_pending_order` smallint(6) NULL default '0',
  `product_attribute` varchar(200) NULL default '',
  `product_cart_attr` varchar(200) NULL default '',
  `not_product_cart_attr` varchar(200) NULL default '',
  `product_in_cart` smallint(6) NULL default '0',
  `cart_subtotal_min` int(11) NULL,
  `cart_subtotal_max` int(11) NULL,
  `user_ip` varchar(100) NULL default '',
  `category_id` int(11) NULL,
  `page_id` smallint(6) NULL default '0',
  `customer_group_id` smallint(6) NULL default '0',
  `country_id` varchar(200) NULL default '',
  `product_id` int(11) NULL,
  `referral` varchar(200) NULL default '',
  `store_id` smallint(6) NULL default '0',
  `corner_style` smallint(6) NULL default '0',
  `border_style` smallint(6) NULL default '0',
  `overlay_background` smallint(6) NULL default '0',
  `popup_content_backgroup_color` int(11) NULL,
  `padding_size` int(11) NULL,
  `popup_shadow` smallint(6) NULL default '0',
  `appear_effect` smallint(6) NULL default '0',
  `customer_css_style` smallint(6) NULL default '0',
  `close_icon_style` varchar(200) NULL default '',
  `border_size_in_px` smallint(6) NULL default '0',
  `custom_javascript` smallint(6) NULL default '0',
  `width_option` smallint(6) NULL default '0',
  `from_date` datetime NULL,
  `to_date` datetime NULL,
  INDEX(`campaign_id`),
  CONSTRAINT `popup_refer_campaign` FOREIGN KEY (`campaign_id`)
    REFERENCES {$this->getTable('campaign/campaign')} (`campaign_id`) ON UPDATE CASCADE ON DELETE CASCADE,
  PRIMARY KEY (`popup_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;



CREATE TABLE {$this->getTable('campaign/template')} (
  `template_id` int(11) unsigned NOT NULL auto_increment,
  `campaign_id` int(11) unsigned NOT NULL,
  `popup_id` int(11) unsigned NOT NULL,
  `position_show` int(11)  NULL,
  `template_type` smallint(6) unsigned NOT NULL,
  `title` varchar(255) NULL default '',
  `description` varchar(255) NULL default '',
  `popup_type` smallint(6) NULL default '0',
  `width` smallint(6) NULL,
  `height` smallint(6) NULL,
  `width_unit` smallint(6) NULL default '0',
  `max_width` smallint(6) NULL default '0',
  `close_effect` smallint(6) NULL default '0',
  `popup_content` text NULL default '',
  `popup_content_parse` text NULL default '',
  `image` text NULL default '',
  `preview_image` text NULL default '',
  `url` text NULL default '',
  `show_when` smallint(6) NULL default '0',
  `scroll_px` int(11)  NULL,
  `hover_selector` varchar(100) NULL default '',
  `click_selector` varchar(100) NULL default '',
  `seconds_delay` int(11)  NULL,
  `border_color` varchar(25) NULL default '',
  `border_radius` smallint(6) NULL default '0',
  `border_size` smallint(6) NULL default '0',
  `padding` smallint(6) NULL default '0',
  `corner_style` smallint(6) NULL default '0',
  `background_color` smallint(6) NULL default '0',
  `popup_background` varchar(100) NULL default '',
  `horizontal_position` smallint(6) NULL default '0',
  `vertical_position` smallint(6) NULL default '0',
  `horizontal_position_px` int(11)  NULL,
  `vertical_position_px` int(11)  NULL,
  `close_style` smallint(6) NULL default '0',
  `close_on_overlayclick` smallint(6) NULL default '0',
  `close_on_timeout` smallint(6) NULL default '0',
  `custom_css` text NULL default '',
  `custom_script` text NULL default '',
  `border_style` smallint(6) NULL default '0',
  `overlay_background` smallint(6) NULL default '0',
  `popup_content_backgroup_color` int(11) NULL,
  `padding_size` int(11) NULL,
  `popup_shadow` smallint(6) NULL default '0',
  `appear_effect` smallint(6) NULL default '0',
  `customer_css_style` smallint(6) NULL default '0',
  `close_icon_style` varchar(200) NULL default '',
  `border_size_in_px` smallint(6) NULL default '0',
  `custom_javascript` smallint(6) NULL default '0',
  `width_option` smallint(6) NULL default '0',
  INDEX(`template_id`),
  CONSTRAINT `template_refer_popup` FOREIGN KEY (`popup_id`)
    REFERENCES {$this->getTable('campaign/popup')} (`popup_id`) ON UPDATE CASCADE ON DELETE CASCADE,
  PRIMARY KEY (`template_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

");



$installer->endSetup();

