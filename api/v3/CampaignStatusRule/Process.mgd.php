<?php
// This file declares a managed database record of type "Job".
// The record will be automatically inserted, updated, or deleted from the
// database as appropriate. For more details, see "hook_civicrm_managed" at:
// https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
return [
  [
    'name' => 'Cron:CampaignStatusRule.Process',
    'entity' => 'Job',
    'params' => [
      'version' => 3,
      'name' => 'Update Campaign Status',
      'description' => 'Process and update Campaign Status based on defined Rules',
      'run_frequency' => 'Daily',
      'api_entity' => 'CampaignStatusRule',
      'api_action' => 'Process',
      'parameters' => '',
    ],
  ],
];
