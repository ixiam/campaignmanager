<?php

namespace Civi\CampaignManager\KPI;

/**
 * ContributionCountKPI
 * @package Civi\CampaignManager\KPI
 */
class ContributionCountKPI extends AbstractKPI {

  protected static $name = "contribution_count";
  protected static $title = "Contribution Count";

  /**
   * Calculate total contributions for campaign
   *
   * @param int $campaign_id
   *   Campaign Id
   * @return string
   *   KPI value
   */
  public static function calculate($campaign_id) {
    $contrib = \Civi\Api4\Contribution::get()
      ->addSelect('COUNT(id) AS count')
      ->addWhere('contribution_status_id:name', '=', 'Completed')
      ->addWhere('campaign_id', '=', $campaign_id)
      ->execute()
      ->single();

    return $contrib['count'] ?? 0;
  }

}
