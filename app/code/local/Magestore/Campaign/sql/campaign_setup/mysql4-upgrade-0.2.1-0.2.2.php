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
    DROP TABLE IF EXISTS {$this->getTable('campaign/widget_banner')};
    CREATE TABLE {$this->getTable('campaign/widget_banner')} (
      `widget_banner_id` int(11) unsigned NOT NULL auto_increment,
      `campaign_id` int(11) unsigned NULL,
      `widget_selected_ids` varchar(255) NOT NULL,
      `title` varchar(255) NOT NULL DEFAULT '',
      `type` varchar(255) NOT NULL DEFAULT '',
      `include` text NOT NULL DEFAULT '',
      `exclude` text NOT NULL DEFAULT '',
      `content` text NOT NULL DEFAULT '',
      `css` text NOT NULL DEFAULT '',
      `status` smallint(6) NOT NULL default '0',
      PRIMARY KEY (`widget_banner_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");


$installer->endSetup();

