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

DROP TABLE IF EXISTS {$this->getTable('campaign/countdown')};

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");


$installer->endSetup();

