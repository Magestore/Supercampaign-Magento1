<?php
$installer = $this;

$installer->startSetup();

$installer->getConnection()->addColumn($this->getTable('newsletter_subscriber'), 'campaign_id', 'int(11) NULL');
$installer->getConnection()->addColumn($this->getTable('newsletter_subscriber'), 'campaign_name', 'varchar(255) NULL');

$installer->endSetup();