<?php
// This file declares a managed database record of type "Job".
// The record will be automatically inserted, updated, or deleted from the
// database as appropriate. For more details, see "hook_civicrm_managed" at:
// https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
return [
  [
    'name' => 'Cron:CampaignKPI.Calculateall',
    'entity' => 'Job',
    'params' => [
      'version' => 3,
      'name' => 'Calculate All Campaign KPIs',
      'description' => 'Calculate All Campaign KPIs',
      'run_frequency' => 'Daily',
      'api_entity' => 'CampaignKPI',
      'api_action' => 'Calculateall',
      'parameters' => '',
    ],
  ],
];
