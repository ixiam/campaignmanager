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
        $campaignKPIValues = [];

        // Get all children if needs to calculate parent value
        if ($this->parentValue) {
          $treePath = CampaignTree::SEPARATOR . $this->campaignId . CampaignTree::SEPARATOR;
          $campaignTrees = \Civi\Api4\CampaignTree::get()
            ->addSelect('campaign_id.parent_id', 'id', 'campaign_id', 'path', 'depth')
            ->addClause('OR', ['id', '=', 4], ['path', 'LIKE', "%{$treePath}%"])
            ->addOrderBy('depth', 'DESC')
            ->execute();
          // get tree depth limits to simplify parents calculations
          $treeLimits = \Civi\Api4\CampaignTree::get()
            ->addSelect('MIN(depth) AS min', 'MAX(depth) AS max')
            ->addClause('OR', ['id', '=', 4], ['path', 'LIKE', "%{$treePath}%"])
            ->execute()
            ->single();

          $campaignKPIValues = array_fill_keys(
            array_column((array) $campaignTrees, 'id'),
            [
              'campaign_kpi_id' => NULL,
              'campaign_id' => NULL,
              'value' => 0,
              'value_parent' => 0,
              'last_modified_date' => \CRM_Utils_Date::currentDBDate(),
            ]
          );
          // Calculate children values and update their parent's values with bottom-up strategy
          $depth = $treeLimits['max'];
          foreach ($campaignTrees as $campaignTree) {
            if ($depth != $campaignTree['depth']) {
              $depth = $campaignTree['depth'];
            }
            $value = $className::calculate($campaignTree['campaign_id']);
            $campaignKPIValues[$campaignTree['campaign_id']]['campaign_kpi_id'] = $this->kpiId;
            $campaignKPIValues[$campaignTree['campaign_id']]['campaign_id'] = $campaignTree['campaign_id'];
            $campaignKPIValues[$campaignTree['campaign_id']]['value'] = $value;
            $campaignKPIValues[$campaignTree['campaign_id']]['value_parent'] += $value;

            // add to parent totals, propagate upwards
            if (isset($campaignTree['campaign_id.parent_id'])) {
              // only propagates upward until campaign selected
              if ($depth > $treeLimits['min']) {
                $ancestors = $this->getAncestors($campaignTree['path'], FALSE);
                foreach ($ancestors as $ancestor) {
                  $campaignKPIValues[$ancestor]['value_parent'] += $value;
                }
              }
            }
          }
          $returnValue = \CRM_CampaignManager_BAO_CampaignKPIValue::formatDisplayValue(
            $campaignKPIValues[$this->campaignId]['value_parent'],
            $className::getDataType()
          );
        }
        else {
          $value = $className::calculate($this->campaignId);
          $campaignKPIValues[] = [
            'campaign_kpi_id' => $this->kpiId,
            'campaign_id' => $this->campaignId,
            'value' => $value,
            'last_modified_date' => \CRM_Utils_Date::currentDBDate(),
          ];
          $returnValue = \CRM_CampaignManager_BAO_CampaignKPIValue::formatDisplayValue($value, $className::getDataType());
        }

        if ($this->persist && !empty($campaignKPIValues)) {
          $params = array_values($campaignKPIValues);
          \Civi\Api4\CampaignKPIValue::save()
            ->setRecords($params)
            ->setMatch(['campaign_kpi_id', 'campaign_id'])
            ->execute();
        }
      }
    }
    else {
      throw new Exception('KPI was not found');
    }

    $result[] = [
      'value' => $returnValue,
    ];
  }

  /**
   * @return array
   */
  public static function fields() {
    return [
      ['name' => 'value', 'data_type' => 'String'],
    ];
  }

  /**
   * Returns ancestors id
   *
   * @param string $path
   * @param bool $fullPath
   *
   * @return array
   */
  private function getAncestors($path, $fullPath = TRUE) {
    $pathArray = array_filter(explode(CampaignTree::SEPARATOR, $path), 'strlen');
    if (!$fullPath) {
      $pathArray = array_slice($pathArray, array_search($this->campaignId, $pathArray) - 1);
    }
    return $pathArray;

  }

}
