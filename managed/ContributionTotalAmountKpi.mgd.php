<?php

// phpcs:disable
use Civi\CampaignManager\KPI\ContributionTotalAmountKPI as KPI;
// phpcs:enable

return [
  [
    'name' => 'CampaignKPI_contribution_total_amount',
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
