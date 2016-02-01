<?php
$installer = $this;

$installer->startSetup();

$installer->run("
  ALTER TABLE {$this->getTable('newsletter_subscriber')}
    add `campaign_id` int(11) NULL default 0,
    add `campain_name` varchar(255) NULL default ''
");
$installer->endSetup();