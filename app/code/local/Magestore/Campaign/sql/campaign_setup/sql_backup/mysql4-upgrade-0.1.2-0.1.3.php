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

DROP TABLE IF EXISTS {$this->getTable('campaign/headertext')};

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


");



$installer->endSetup();

