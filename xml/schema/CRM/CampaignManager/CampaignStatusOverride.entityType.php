<?php
// This file declares a new entity type. For more details, see "hook_civicrm_entityTypes" at:
// https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
return [
  [
    'name' => 'CampaignStatusOverride',
    'class' => 'CRM_CampaignManager_DAO_CampaignStatusOverride',
    'table' => 'civicrm_campaign_status_override',
  ],
];
