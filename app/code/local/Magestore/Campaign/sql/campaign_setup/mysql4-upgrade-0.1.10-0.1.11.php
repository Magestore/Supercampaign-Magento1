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
/*ALTER TABLE `{$this->getTable('campaign/popup_type_game_player')}`
    DROP FOREIGN KEY `popup_game_player_refer_maillist`;*/

$installer->run("

ALTER TABLE `{$this->getTable('campaign/popup_type_game_player')}`
    DROP FOREIGN KEY `popup_game_player_refer_maillist`;

ALTER TABLE `{$this->getTable('campaign/popup_type_game_player')}`
    ADD CONSTRAINT `popup_game_player_refer_maillist`
        FOREIGN KEY (`maillist_id`) REFERENCES `{$this->getTable('campaign/maillist')}` (`id`) ON UPDATE CASCADE ON DELETE CASCADE;

");



$installer->endSetup();

