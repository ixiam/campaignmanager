<?php

namespace Civi\CampaignManager\KPI;

/**
 * MembershipCountKPI
 * @package Civi\CampaignManager\KPI
 */
class MembershipCountKPI extends AbstractKPI {

  protected static $name = "membership_count";
  protected static $title = "Active Members Count";

  /**
   * Calculate total Active Memberships for campaign
   *
   * @param int $campaign_id
   *   Campaign Id
   * @return string
   *   KPI value
   */
  public static function calculate($campaign_id) {
    $member = \Civi\Api4\Membership::get()
      ->addSelect('COUNT(id) AS count')
      ->addWhere('status_id.is_active', '=', TRUE)
      ->addWhere('campaign_id', '=', $campaign_id)
      ->execute()
      ->single();

    return $member['count'] ?? 0;
  }

}
