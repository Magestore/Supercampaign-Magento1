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
/*
DROP TABLE IF EXISTS {$this->getTable('campaign/popup_type_game_halloween')};

CREATE TABLE {$this->getTable('campaign/popup_type_game_halloween')} (
  `id` int(11) unsigned NOT NULL auto_increment,
  `popup_id` int(11) unsigned NOT NULL COMMENT 'reference to popup.popup_id',
  `template_step_1` VARCHAR(255) NOT NULL default '' COMMENT 'Template id applied',
  `content_step_1` text NOT NULL default '',
  `template_step_2` VARCHAR(255) NOT NULL default '',
  `content_step_2` text NOT NULL default '',
  `template_step_3` VARCHAR(255) NOT NULL default '',
  `content_step_3` text NOT NULL default '',
  `ga_code_close` VARCHAR(255) NOT NULL default '',
  INDEX(`popup_id`),
  CONSTRAINT `popup_game_halloween` FOREIGN KEY (`popup_id`)
    REFERENCES {$this->getTable('campaign/popup')} (`popup_id`) ON UPDATE CASCADE ON DELETE CASCADE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;*/
/*
DROP TABLE IF EXISTS {$this->getTable('campaign/popup_type_game_player')};

CREATE TABLE {$this->getTable('campaign/popup_type_game_player')} (
  `id` int(11) unsigned NOT NULL auto_increment,
  `maillist_id` int(11) unsigned NOT NULL COMMENT 'reference to maillist.id',
  `points` int(11) NOT NULL DEFAULT 0,
  `character_name` VARCHAR(255) NOT NULL DEFAULT '',
  INDEX(`maillist_id`),
  CONSTRAINT `popup_game_player_refer_maillist` FOREIGN KEY (`id`)
    REFERENCES {$this->getTable('campaign/maillist')} (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;*/

");

/*
$template = array(
    array(
        'title' => 'Game Halloween',
        'group' => Magestore_Campaign_Model_Popup_Type_Game_Halloween::TEMPLATE_GROUP,
        'type' => Magestore_Campaign_Model_Popup_Type_Game_Halloween::TEMPLATE_TYPE_STEP_1,
        'content' => '<div class="content step1">
<div class="popup-treat-game">
<div class="game-program"><img src="{{skin url=\'images/campaign/halloween/treat-game.png\'}}" alt="" width="100%" height="100%" /></div>
<h2>Knock the right door to get "sweet" reward points this Halloween</h2>
<h2>(Up to <span style="color: #f6a81f;">$30-valued</span> points for discount in next order)</h2>
<p>Now, click one of the doors to try your luck:</p>
<div class="treat-game-program">
<ul>
<li class="door-1 charactor">&nbsp;</li>
<li class="door-2 charactor">&nbsp;</li>
<li class="door-3 charactor">&nbsp;</li>
<li class="door-4 charactor">&nbsp;</li>
<li class="door-5 charactor last">&nbsp;</li>
</ul>
</div>
</div>
</div>',
        'overview' => 'images/magestore/campaign/halloween_step1.png'
    ),
    array(
        'title' => 'Game Halloween',
        'group' => Magestore_Campaign_Model_Popup_Type_Game_Halloween::TEMPLATE_GROUP,
        'type' => Magestore_Campaign_Model_Popup_Type_Game_Halloween::TEMPLATE_TYPE_STEP_2,
        'content' => '<div class="content step2">
<div class="popup-treat-game">
<h2>To check &amp; use points, please enter email used for purchasing:</h2>
<form id="halloween-email" class="form-inline" action="" method="">
<div class="form-group"><input class="form-control required-entry validate-email" type="text" name="email" /> <button class="btn btn-submit">Ok, let\'s check my result</button><p class="error-message">:(</p></div>
</form></div>
</div>',
        'overview' => 'images/magestore/campaign/halloween_step2.png'
    ),
    array(
        'title' => 'Game Halloween',
        'group' => Magestore_Campaign_Model_Popup_Type_Game_Halloween::TEMPLATE_GROUP,
        'type' => Magestore_Campaign_Model_Popup_Type_Game_Halloween::TEMPLATE_TYPE_STEP_3,
        'content' => '<div class="content step3">
<div class="popup-treat-game">
<div class="game-program"><img src="{{skin url=\'images/campaign/halloween/step3-game.png\'}}" alt="" width="100%" height="100%" /></div>
<div class="btn-spend">
<h2>You have received <span class="points-value">10 points</span> which equals to <span class="money-value">$10</span> discount in your next order</h2>
<button id="spend-my-points" class="btn btn-submit" type="button">Yes, spend my points now</button></div>
<div class="treat-game-program">
<ul>
<li class="miss-door-1 active">
<div class="door">&nbsp;</div>
<p class="points-value">10 Points</p>
</li>
<li class="miss-door-2">
<div class="door">&nbsp;</div>
<p class="points-value">10 Points</p>
</li>
<li class="miss-door-3">
<div class="door">&nbsp;</div>
<p class="points-value">10 Points</p>
</li>
<li class="miss-door-4">
<div class="door">&nbsp;</div>
<p class="points-value">10 Points</p>
</li>
<li class="miss-door-5 last">
<div class="door">&nbsp;</div>
<p class="points-value">10 Points</p>
</li>
</ul>
</div>
</div>
</div>',
        'overview' => 'images/magestore/campaign/halloween_step3.png'
    )
);

foreach($template as $row){
    Mage::getModel('campaign/template')
        ->setData($row)
        ->save();
}*/

$installer->endSetup();

