<?php

namespace Civi\CampaignManager\KPI;

/**
 * ContributionTotalAmountKPI
 * @package Civi\CampaignManager\KPI
 */
class ContributionTotalAmountKPI extends AbstractKPI {

  protected static $name = "contribution_total_amount";
  protected static $title = "Contribution Total Amount";
  protected static $dataType = \CRM_Utils_Type::T_MONEY;

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
      ->addSelect('SUM(total_amount) AS total')
      ->addWhere('contribution_status_id:name', '=', 'Completed')
      ->addWhere('campaign_id', '=', $campaign_id)
      ->execute()
      ->single();

    return $contrib['total'] ?? 0;
  }

}
