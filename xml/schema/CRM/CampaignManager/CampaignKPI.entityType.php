<?php
// This file declares a new entity type. For more details, see "hook_civicrm_entityTypes" at:
// https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
return [
  [
    'name' => 'CampaignKPI',
    'class' => 'CRM_CampaignManager_DAO_CampaignKPI',
    'table' => 'civicrm_campaign_kpi',
  ],
];
