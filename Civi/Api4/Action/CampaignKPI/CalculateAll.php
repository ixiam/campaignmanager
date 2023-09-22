<?php

namespace Civi\Api4\Action\CampaignKPI;

use Civi\CampaignManager\Utils\ClassScanner as ClassScanner;
use Civi\Api4\Generic\Result;

/**
 * @see \Civi\Api4\Generic\AbstractAction
 *
 * @package Civi\Api4\Action\CampaignKPI
 */
class CalculateAll extends \Civi\Api4\Generic\AbstractAction {
  use \Civi\Api4\Traits\CampaignKPITrait;

  /**
   * Persist result in DB
   *
   * @var bool
   */
  protected $persist = TRUE;

  /**
   * Calculate parent value
   *
   * @var bool
   */
  protected $parentValue = FALSE;

  /**
   * Calculate KPI value by campaign
   *
   * @param \Civi\Api4\Generic\Result $result
   */
  public function _run(Result $result) {
    $now = \CRM_Utils_Date::currentDBDate();
    $kpis = \Civi\Api4\CampaignKPI::get()
      ->addWhere('is_active', '=', TRUE)
      ->execute();
    $kpiClasses = ClassScanner::scanClasses('Civi\CampaignManager\KPI', 'Civi\CampaignManager\KPI\AbstractKPI', 'kpi');

    $campaignApi = \Civi\Api4\Campaign::get()
      ->addSelect('id', 'name', 'parent_id')
      ->addWhere('is_active', '=', TRUE);
    if ($this->parentValue) {
      $campaignApi->addSelect('campaign_tree.path', 'campaign_tree.depth');
      $campaignApi->addJoin('CampaignTree AS campaign_tree', 'LEFT');
      $campaignApi->addOrderBy('campaign_tree.depth', 'DESC');
    }
    $campaigns = $campaignApi->execute();

    // init KPI values array
    $campaignKPIValues = array_fill_keys(
      array_column((array) $campaigns, 'id'),
      [
        'campaign_kpi_id' => NULL,
        'campaign_id' => NULL,
        'value' => 0,
        'last_modified_date' => $now,
      ]
    );

    // Loop by KPIs
    foreach ($kpis as $kpi) {
      $kpiName = $kpi['name'] ?? NULL;
      if ($kpiName) {
        if (isset($kpiClasses[$kpiName])) {
          $className = '\\Civi\\CampaignManager\\KPI\\' . $kpiClasses[$kpiName];
          $campaignKPIValues = [];

          // Loop by campaigns
          foreach ($campaigns as $campaign) {
            $value = $className::calculate($campaign['id']);
            $campaignKPIValues[$campaign['id']] = [
              'campaign_kpi_id' => $kpi['id'],
              'campaign_id' => $campaign['id'],
              'value' => $value,
              'last_modified_date' => $now,
            ];

            // Calculate parents value
            if ($this->parentValue) {
              $campaignKPIValues[$campaign['id']]['value_parent'] += $value;
              if (isset($campaign['parent_id'])) {
                $ancestors = $this->getAncestors($campaign['campaign_tree.path']);
                // Loop by tree branch to update parent values
                foreach ($ancestors as $ancestor) {
                  $campaignKPIValues[$ancestor]['value_parent'] += $value;
                }
              }
            }
          }
        }
      }
    }

    // save values in DB
    if ($this->persist && !empty($campaignKPIValues)) {
      $params = array_values($campaignKPIValues);
      \Civi\Api4\CampaignKPIValue::save()
        ->setRecords($params)
        ->setMatch(['campaign_kpi_id', 'campaign_id'])
        ->execute();
    }

    $result[] = [
      'value' => $campaignKPIValues,
    ];

  }

  /**
   * @return array
   */
  public static function fields() {
    return [
      ['name' => 'values', 'data_type' => 'Array'],
    ];
  }

}
