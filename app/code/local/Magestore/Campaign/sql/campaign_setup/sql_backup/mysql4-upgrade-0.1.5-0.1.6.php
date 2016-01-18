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

DROP TABLE IF EXISTS {$this->getTable('campaign/bannerhomepage')};

CREATE TABLE {$this->getTable('campaign/bannerhomepage')} (
  `id` int(11) unsigned NOT NULL auto_increment,
  `campaign_id` int(11) unsigned NOT NULL,
  `input_bannerhomepage` varchar(255) NOT NULL default '' COMMENT 'Input banner image',
  `path_bannerhomepage` varchar(255) NOT NULL default '' COMMENT 'Path banner menu',
  `link_attached` text NOT NULL default '',
  `status` smallint(6) NOT NULL default '0',
  INDEX(`campaign_id`),
  CONSTRAINT `bannerhomepage_refer_campaign` FOREIGN KEY (`campaign_id`)
    REFERENCES {$this->getTable('campaign/campaign')} (`campaign_id`) ON UPDATE CASCADE ON DELETE CASCADE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");


$installer->endSetup();

