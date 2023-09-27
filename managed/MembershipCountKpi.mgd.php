<?php

// phpcs:disable
use Civi\CampaignManager\KPI\MembershipCountKPI as KPI;
// phpcs:enable

return [
  [
    'name' => 'CampaignKPI_membership_count',
    'entity' => 'CampaignKPI',
    'cleanup' => 'always',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => KPI::getName(),
        'title' => KPI::getTitle(),
        'is_active' => TRUE,
      ],
      'match' => [
        'name',
      ],
    ],
  ],
];
