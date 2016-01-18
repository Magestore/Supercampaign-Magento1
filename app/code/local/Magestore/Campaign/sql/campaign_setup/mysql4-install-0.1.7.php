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
  `gift_code_type` VARCHAR(255) NOT NULL default '',
  `coupon_code` VARCHAR(255) NOT NULL default '',
  `promo_quote_id` int(10) unsigned NOT NULL DEFAULT 0,
  `start_time` datetime NULL,
  `end_time` datetime NULL,
  PRIMARY KEY (`campaign_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


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


DROP TABLE IF EXISTS {$this->getTable('campaign/popup')};

CREATE TABLE {$this->getTable('campaign/popup')} (
  `popup_id` int(11) unsigned NOT NULL auto_increment,
  `campaign_id` int(11) unsigned NOT NULL,
  `popup_type` VARCHAR(255) NOT NULL default '',
  `is_show_coupon` smallint(6) NOT NULL default '0',
  `is_show_first_time` smallint(6) NOT NULL default '0',
  `include_page` text NOT NULL default '',
  `exclude_page` text NOT NULL default '',
  `status` smallint(6) NOT NULL default '0',
  INDEX(`campaign_id`),
  CONSTRAINT `popup_refer_campaign` FOREIGN KEY (`campaign_id`)
    REFERENCES {$this->getTable('campaign/campaign')} (`campaign_id`) ON UPDATE CASCADE ON DELETE CASCADE,
  PRIMARY KEY (`popup_id`)
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS {$this->getTable('campaign/template')};

CREATE TABLE {$this->getTable('campaign/template')} (
  `id` int(11) unsigned NOT NULL auto_increment,
  `group` VARCHAR(255) NOT NULL default '',
  `type` VARCHAR(255) NOT NULL default '',
  `title` VARCHAR(255) NOT NULL default '',
  `content` text NOT NULL default '',
  `overview` VARCHAR(255) NOT NULL default '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


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


DROP TABLE IF EXISTS {$this->getTable('campaign/bannerlistpage')};

CREATE TABLE {$this->getTable('campaign/bannerlistpage')} (
  `id` int(11) unsigned NOT NULL auto_increment,
  `campaign_id` int(11) unsigned NOT NULL,
  `input_banner` varchar(255) NOT NULL default '' COMMENT 'Input banner image',
  `link_attached` text NOT NULL default '',
  `path_bannerlistpage` varchar(255) NOT NULL default '',
  `status` smallint(6) NOT NULL default '0',
  INDEX(`campaign_id`),
  CONSTRAINT `bnlistpage_refer_campaign` FOREIGN KEY (`campaign_id`)
    REFERENCES {$this->getTable('campaign/campaign')} (`campaign_id`) ON UPDATE CASCADE ON DELETE CASCADE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


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


DROP TABLE IF EXISTS {$this->getTable('campaign/bannermenu')};

CREATE TABLE {$this->getTable('campaign/bannermenu')} (
  `id` int(11) unsigned NOT NULL auto_increment,
  `campaign_id` int(11) unsigned NOT NULL,
  `input_bannermenu` varchar(255) NOT NULL default '' COMMENT 'Input banner image',
  `path_bannermenu` varchar(255) NOT NULL default '' COMMENT 'Path banner menu',
  `link_attached` text NOT NULL default '',
  `status` smallint(6) NOT NULL default '0',
  INDEX(`campaign_id`),
  CONSTRAINT `bannermenu_refer_campaign` FOREIGN KEY (`campaign_id`)
    REFERENCES {$this->getTable('campaign/campaign')} (`campaign_id`) ON UPDATE CASCADE ON DELETE CASCADE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



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


/**
 * add template data
 */
$template = array(
    array(
        'title' => 'Normal Form',
        'group' => Magestore_Campaign_Model_Popup_Type_Form::TEMPLATE_GROUP,
        'type' => Magestore_Campaign_Model_Popup_Type_Form::TEMPLATE_TYPE_STEP_1,
        'content' => '<div class="row">
<div class="image-left col-lg-6 col-md-6 col-sm-6 col-xs-12"><img src="{{skin url=\'images/campaign/img-left.png\'}}" alt="Summer holiday" width="100%" height="100%" /></div>
<div class="content-right col-lg-6 col-md-6 col-sm-6 col-xs-12">
<h3 class="heading-title">BIG SALE</h3>
<div class="sub-title"><span class="number-percent">10%</span><span class="text-percent">COUPON code now!</span></div>
<span>{{block type="campaign/countdown" locate="popup"}}</span>
<form id="email-campaign-summer" class="email-campaign" action="" method="post">
<div id="demo_img" class="top-demo">
<div class="input-box"><input id="name-customer" class="input-text required-entry" title="* Enter your name to join" type="text" name="name" value="" /></div>
<div class="input-box"><input id="email-customer" class="input-text required-entry validate-email" title="* Enter your email to join" type="text" name="email" value="" /></div>
<div class="actions"><button id="submit-info" class="button button-submit" type="submit"><span><span>Get Coupon Code</span></span></button> <span class="loading" style="display: none;"> <img src="{{skin url=\'images/campaign/loading.gif\'}}" alt=" alt=" width="20" height="20" /> </span></div>
<div class="error-message" style="color: red; display: none; font-weight: normal; font-size: 12px;">&nbsp;</div>
</div>
</form></div>
</div>',
        'overview' => 'images/magestore/campaign/normal-form.png'
    ),
    array(
        'title' => 'Normal Form',
        'group' => Magestore_Campaign_Model_Popup_Type_Form::TEMPLATE_GROUP,
        'type' => Magestore_Campaign_Model_Popup_Type_Form::TEMPLATE_TYPE_STEP_2,
        'content' => '<div class="row">
	<div class="image-left col-lg-6 col-md-6 col-sm-6 col-xs-12"><img src="{{skin url=\'images/campaign/img-left.png\'}}" alt="Summer holiday" width="100%" height="100%" /></div>
	<div class="content-right col-lg-6 col-md-6 col-sm-6 col-xs-12">
		<h3 class="heading-title-ss">Splash! Here is your coupon code</h3>
		<div>{{block type="campaign/popup" template="campaign/coupon-popup.phtml"}}</div>
		<div class="note-campaign"><span>Paste this code on the checkout page to get <strong>10% OFF</strong> on your favourite extensions</span></div>
		<p>{{block type="campaign/countdown" locate="popup"}}</p>
		<div class="actions"><a {{block type="campaign/popup" template="campaign/gapopup/ga_btnsuccess.phtml"}} id="submit-info" class="button button-submit" type="submit"><span><span>View all updates</span></span></a> <span class="loading-result" style="display: none;"> <img src="{{skin url=\'images/campaign/loading.gif\'}}" alt="" width="20" height="20" /> </span></div>
	</div>
</div>',
        'overview' => 'images/magestore/campaign/normal-result.png'
    ),
    array(
        'title' => 'Normal Static',
        'group' => Magestore_Campaign_Model_Popup_Type_Static::TEMPLATE_GROUP,
        'type' => Magestore_Campaign_Model_Popup_Type_Static::TEMPLATE_TYPE,
        'content' => '<div class="row"><div class="wrap-static-popup">
<div class="image-left col-md-5 col-sm-5"><img src="{{skin url=\'images/campaign/img-left.png\'}}" alt="Summer holiday" width="100%" height="100%" /></div>
<div class="col-md-7 col-sm-7">
	<div class="title">
		<h4>SimiCart &amp; Magento co-host</h4>
		<span class="line">&nbsp;</span></div>
	<div class="title-small">
		<h3>FREE training Webinar</h3>
		<span class="clock">{{block type="campaign/countdown" locate="popup"}}</span></div>
	<div class="description">
		<h5>How to build mobile apps for Magento sites</h5>
		<ul>
			<li>Learn to build your Magento mobile apps. Without coding</li>
			<li>Directly discuss with our experts in real-time Q&amp;A session</li>
		</ul>
	</div>
	<div class="actions">
		<div class="button button1"><a rel="nofollow" target="_blank" {{block type="campaign/popup" template="campaign/gapopup/ga_button_static.phtml"}}><span>Gimme more info</span></a></div>
		<div class="button button2"><a data-dismiss="modal"><span>I\'m not interested</span></a></div>
	</div>
</div>
</div></div>',
        'overview' => 'images/magestore/campaign/normal-text.png'
    ),
    array(
        'group'=>'sidebar', 'type' => 'popup', 'title' => 'Popup',
        'content' => '<div class="wrap-content-sidebar" data-toggle="modal" data-target="#campaign-popup"><div class="title-campaign"><span>Title CAMPAIGN</span> <span id="coundown-sidebar" style="line-height: 39px; margin-left: 0px; font-size: 12px; font-weight: 600; color: #fbd50c;"><span style="color: white;">{{block type="campaign/countdown" style="short" locate="sidebar"}}</span></span></div>
        <div class="icon-campaign"><img src="{{skin url=\'images/campaign/icon-campaign.png\'}}" alt="" width="34" height="36" /></div></div>',
        'overview' => 'images/magestore/campaign/sidebar_popup.png'
    ),
    array(
        'group'=>'sidebar', 'type' => 'link', 'title' => 'Link',
        'content' => '<div onclick="window.location.href=\'{{block type=\'campaign/sidebar\' template=\'campaign/link.phtml\'}}\'">
<div class="title-campaign"><a class="wrap-content-sidebar" href="{{block type=\'campaign/sidebar\' template=\'campaign/link.phtml\'}}"><span>Title CAMPAIGN</span> </a><span id="coundown-sidebar" style="line-height: 39px; margin-left: 0px; font-size: 12px; font-weight: 600; color: #fbd50c;"><span style="color: white;">{{block type="campaign/countdown" style="short" locate="sidebar"}}</span></span></div>
<div class="icon-campaign"><a class="wrap-content-sidebar" href="{{block type=\'campaign/sidebar\' template=\'campaign/link.phtml\'}}"><img src="{{skin url=\'images/campaign/icon-campaign.png\'}}" alt="" width="34" height="36" /></a></div>
</div>',
        'overview' => 'images/magestore/campaign/sidebar_link.png'
    ),
    array(
        'title' => 'Default',
        'group'=>Magestore_Campaign_Model_Headertext::TEMPLATE_GROUP,
        'type' => Magestore_Campaign_Model_Headertext::TEMPLATE_TYPE_POPUP,
        'content' => '<div style="color: yellow;"><span style="color: white; text-transform: uppercase;"> Headertext {{block type="campaign/countdown" locate="header"}}</span><a href="http://www.magestore.com/" target="_blank" style="text-transform:uppercase">Input Link</a></div>',
        'overview' => 'images/magestore/campaign/headertext_1.png'
    )
);

foreach($template as $row){
    Mage::getModel('campaign/template')
        ->setData($row)
        ->save();
}


$installer->endSetup();

