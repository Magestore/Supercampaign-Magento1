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

/**
 * create campaign table
 */


$installer->run("

DROP TABLE IF EXISTS {$this->getTable('campaign/campaign')};

CREATE TABLE {$this->getTable('campaign/campaign')} (
  `campaign_id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  `url` text NOT NULL default '',
  `store` varchar(255) NOT NULL default '',
  `status` smallint(6) NOT NULL default '0',
  `priority` int(6) NOT NULL DEFAULT '0',
  `use_coupon` int(6) NOT NULL DEFAULT '0',
  `coupon_code_type` VARCHAR(255) NOT NULL default '',
  `coupon_code` VARCHAR(255) NOT NULL default '',
  `promo_rule_id` int(10) unsigned NOT NULL DEFAULT 0,
  `start_time` datetime NULL,
  `end_time` datetime NULL,
  `countdown_type` VARCHAR(255) NOT NULL default '',
  `countdown_products` VARCHAR(255) NOT NULL default '',
  `countdown_onoff` smallint(6) NOT NULL DEFAULT 0,
  PRIMARY KEY (`campaign_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*
DROP TABLE IF EXISTS {$this->getTable('campaign/sidebar')};

CREATE TABLE {$this->getTable('campaign/sidebar')} (
  `id` int(11) unsigned NOT NULL auto_increment,
  `campaign_id` int(11) unsigned NOT NULL,
  `template_id` int(11) unsigned NULL default NULL COMMENT 'id magestore_campaign_template',
  `content` text NOT NULL default '',
  `ga_code_close` VARCHAR(255) NOT NULL default '',
  `ga_code_click` VARCHAR(255) NOT NULL default '',
  `type` VARCHAR(255) NOT NULL default '' COMMENT 'link|popup',
  `url` VARCHAR(255) NOT NULL default '',
  `include_page` text NOT NULL default '',
  `exclude_page` text NOT NULL default '',
  `status` smallint(6) NOT NULL default '0',
  INDEX(`campaign_id`),
  CONSTRAINT `sidebar_campaign` FOREIGN KEY (`campaign_id`)
    REFERENCES {$this->getTable('campaign/campaign')} (`campaign_id`) ON UPDATE CASCADE ON DELETE CASCADE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS {$this->getTable('campaign/popup_type_static')};

CREATE TABLE {$this->getTable('campaign/popup_type_static')} (
  `id` int(11) unsigned NOT NULL auto_increment,
  `popup_id` int(11) unsigned NOT NULL COMMENT 'reference to popup.popup_id',
  `status` smallint(6) NOT NULL default '0',
  `is_show_coupon` smallint(6) NOT NULL default '0',
  `template_id` VARCHAR(255) NOT NULL default '' COMMENT 'Template id applied',
  `content` text NOT NULL default '',
  `include_page` text NOT NULL default '',
  `exclude_page` text NOT NULL default '',
  `ga_code_button` VARCHAR(255) NOT NULL default '',
  `ga_code_close` VARCHAR(255) NOT NULL default '',
  INDEX(`popup_id`),
  CONSTRAINT `popup_static` FOREIGN KEY (`popup_id`)
    REFERENCES {$this->getTable('campaign/popup')} (`popup_id`) ON UPDATE CASCADE ON DELETE CASCADE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS {$this->getTable('campaign/popup_type_form')};

CREATE TABLE {$this->getTable('campaign/popup_type_form')} (
  `id` int(11) unsigned NOT NULL auto_increment,
  `popup_id` int(11) unsigned NOT NULL COMMENT 'reference to popup.popup_id',
  `status` smallint(6) NOT NULL default '0',
  `mailchimp_list` VARCHAR(255) NOT NULL default '',
  `template_id_one` VARCHAR(255) NOT NULL default '',
  `template_id_two` VARCHAR(255) NOT NULL default '',
  `content_step_one` text NOT NULL default '',
  `content_step_two` text NOT NULL default '',
  `ga_close_form` VARCHAR(255) NOT NULL default '',
  `ga_button_form` VARCHAR(255) NOT NULL default '',
  `ga_close_thanks` VARCHAR(255) NOT NULL default '',
  `ga_button_thanks` VARCHAR(255) NOT NULL default '',
  `ga_background` VARCHAR(255) NOT NULL default '',
  INDEX(`popup_id`),
  CONSTRAINT `popup_form` FOREIGN KEY (`popup_id`)
    REFERENCES {$this->getTable('campaign/popup')} (`popup_id`) ON UPDATE CASCADE ON DELETE CASCADE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS {$this->getTable('campaign/popup_type_game')};

CREATE TABLE {$this->getTable('campaign/popup_type_game')} (
  `id` int(11) unsigned NOT NULL auto_increment,
  `popup_id` int(11) unsigned NOT NULL COMMENT 'reference to popup.popup_id',
  `status` smallint(6) NOT NULL default '0',
  `is_show_coupon` smallint(6) NOT NULL default '0',
  `static_coupon` VARCHAR(255) NOT NULL default '',
  `get_coupon_from` VARCHAR(255) NOT NULL default '',
  `include_page` text NOT NULL default '',
  `exclude_page` text NOT NULL default '',
  `static_block` VARCHAR(255) NOT NULL default '',
  `ga_code_close` VARCHAR(255) NOT NULL default '',
  `ga_code_cancel` VARCHAR(255) NOT NULL default '',
  `ga_code_submit` VARCHAR(255) NOT NULL default '',
  INDEX(`popup_id`),
  CONSTRAINT `popup_game` FOREIGN KEY (`popup_id`)
    REFERENCES {$this->getTable('campaign/popup')} (`popup_id`) ON UPDATE CASCADE ON DELETE CASCADE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;*/


/*DROP TABLE IF EXISTS {$this->getTable('campaign/template')};

CREATE TABLE {$this->getTable('campaign/template')} (
  `id` int(11) unsigned NOT NULL auto_increment,
  `group` VARCHAR(255) NOT NULL default '',
  `type` VARCHAR(255) NOT NULL default '',
  `title` VARCHAR(255) NOT NULL default '',
  `content` text NOT NULL default '',
  `overview` VARCHAR(255) NOT NULL default '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;*/


/*DROP TABLE IF EXISTS {$this->getTable('campaign/headertext')};

CREATE TABLE {$this->getTable('campaign/headertext')} (
  `id` int(11) unsigned NOT NULL auto_increment,
  `campaign_id` int(11) unsigned NOT NULL,
  `template_id` int(11) unsigned NULL default NULL COMMENT 'Header text template id magestore_campaign_headertext_template',
  `content` text NOT NULL default '',
  `cms_block` VARCHAR(255) NOT NULL default '' COMMENT 'CMS Block Id',
  `cms_identifier` VARCHAR(255) NOT NULL default '' COMMENT 'CMS Block Identifier',
  `include_page` text NOT NULL default '',
  `exclude_page` text NOT NULL default '',
  `link_attached` text NOT NULL default '',
  `is_countdown` smallint(6) NOT NULL default '0',
  `status` smallint(6) NOT NULL default '0',
  INDEX(`campaign_id`),
  CONSTRAINT `headertext_refer_campaign` FOREIGN KEY (`campaign_id`)
    REFERENCES {$this->getTable('campaign/campaign')} (`campaign_id`) ON UPDATE CASCADE ON DELETE CASCADE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;*/


DROP TABLE IF EXISTS {$this->getTable('campaign/maillist')};

CREATE TABLE {$this->getTable('campaign/maillist')} (
  `id` int(11) unsigned NOT NULL auto_increment,
  `campaign_id` int(11) unsigned NOT NULL,
  `email` varchar(255) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `used_coupon` smallint(6) NOT NULL default '0',
  `coupon_code` varchar(255) NOT NULL default '',
  `start_time` datetime NULL,
  `expired_time` datetime NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*DROP TABLE IF EXISTS {$this->getTable('campaign/countdown')};

CREATE TABLE {$this->getTable('campaign/countdown')} (
  `id` int(11) unsigned NOT NULL auto_increment,
  `campaign_id` int(11) unsigned NOT NULL,
  `showcountdown` varchar(255) NOT NULL default '' COMMENT 'show countdown',
  `link_attached` text NOT NULL default '',
  `timezone` varchar(255) NOT NULL,
  `product_id` int(11) unsigned NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `day_countdown` int(11) unsigned NOT NULL,
  `month_countdown` int(11) unsigned NOT NULL,
  `hour_countdown` int(11) unsigned NOT NULL,
  `down_countdown` int(11) unsigned NOT NULL,
  `price_start` FLOAT(12,2) NULL,
  `down_price` FLOAT(12,2) NULL,
  `status` smallint(6) NOT NULL default '0',
  `type_countdown` smallint(6) NOT NULL default '0',
  INDEX(`campaign_id`),
  CONSTRAINT `countdown_refer_campaign` FOREIGN KEY (`campaign_id`)
    REFERENCES {$this->getTable('campaign/campaign')} (`campaign_id`) ON UPDATE CASCADE ON DELETE CASCADE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;*/


");


$installer->endSetup();

