<?php

namespace Civi\Api4\Action\CampaignKPI;

use CRM_CampaignManager_BAO_CampaignTree as CampaignTree;
use Civi\CampaignManager\Utils\ClassScanner as ClassScanner;
use Civi\Api4\Generic\Result;

/**
 * @see \Civi\Api4\Generic\AbstractAction
 *
 * @package Civi\Api4\Action\CampaignKPI
 */
class Calculate extends \Civi\Api4\Generic\AbstractAction {

  /**
   * ID of KPI
   *
   * @var int
   * @required
   */
  protected $kpiId;

  /**
   * ID of campaign
   *
   * @var int
   * @required
   */
  protected $campaignId;

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
    $campaignKpi = \Civi\Api4\CampaignKPI::get()
      ->addWhere('id', '=', $this->kpiId)
      ->execute()
      ->single();

    $kpiName = $campaignKpi['name'] ?? NULL;
    if ($kpiName) {
      $kpis = ClassScanner::scanClasses('Civi\CampaignManager\KPI', 'Civi\CampaignManager\KPI\AbstractKPI', 'kpi');
      if (isset($kpis[$kpiName])) {
        $className = '\\Civi\\CampaignManager\\KPI\\' . $kpis[$kpiName];
        $treePath = CampaignTree::SEPARATOR . $this->campaignId . CampaignTree::SEPARATOR;

        // Get all children if needs to calculate parent value
        if ($this->parentValue) {
          $treePathOperator = 'CONTAINS';
        }
        else {
          // Or only the campaign
          $treePathOperator = '=';
        }

        $campaignTrees = \Civi\Api4\CampaignTree::get()
          ->addSelect('campaign_id.parent_id', 'id', 'campaign_id', 'path', 'depth')
          ->addWhere('path', $treePathOperator, $treePath)
          ->addOrderBy('depth', 'DESC')
          ->execute();
        // get tree depth limits to simplify parents calculations
        $treeLimits = \Civi\Api4\CampaignTree::get()
          ->addSelect('MIN(depth) AS min', 'MAX(depth) AS max')
          ->addWhere('path', $treePathOperator, $treePath)
          ->execute()
          ->single();

        // We calculate first all CampaignTree values in arrays
        $treeValues = [];
        $depth = $treeLimits['max'];
        foreach ($campaignTrees as $campaignTree) {
          if ($depth != $campaignTree['depth']) {
            $depth = $campaignTree['depth'];
          }
          $value = $className::calculate($campaignTree['campaign_id']);
          $treeValues[$campaignTree['campaign_id']]['campaign_kpi_id'] = $this->kpiId;
          $treeValues[$campaignTree['campaign_id']]['campaign_id'] = $campaignTree['campaign_id'];
          $treeValues[$campaignTree['campaign_id']]['value'] = $value;
          $treeValues[$campaignTree['campaign_id']]['last_modified_date'] = \CRM_Utils_Date::currentDBDate();

          // add to parent totals, propagate upwards
          if (isset($campaignTree['campaign_id.parent_id'])) {
            $ancestors = array_filter(explode(CampaignTree::SEPARATOR, $campaignTree['path']), 'strlen');
            foreach ($ancestors as $ancestor) {
              $treeValues[$ancestor]['value_parent'] += $value;
            }
          }

        }

        if ($this->persist) {
          $params = array_values($treeValues);
          \Civi\Api4\CampaignKPIValue::save()
            ->setRecords($params)
            ->setMatch(['campaign_kpi_id', 'campaign_id'])
            ->execute();
        }
      }
    }

    $indexToReturn = $this->parentValue ? 'value_parent' : 'value';
    $result[] = [
      'value' => $treeValues[$this->campaignId][$indexToReturn],
    ];
  }

  /**
   * @return array
   */
  public static function fields() {
    return [
      ['name' => 'value', 'data_type' => 'String']
    ];
  }

}
