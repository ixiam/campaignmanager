<?php

namespace Civi\Api4\Traits;

/**
 * CampaignKPI helper functions Trait
 *
 */
trait CampaignKPITrait {

  /**
   * Returns ancestors id
   *
   * @param string $path
   * @param bool $fullPath
   *
   * @return array
   */
  private function getAncestors($path, $fullPath = TRUE) {
    $pathArray = array_filter(explode(\CRM_CampaignManager_BAO_CampaignTree::SEPARATOR, $path), 'strlen');
    if (!$fullPath) {
      $pathArray = array_slice($pathArray, array_search($this->campaignId, $pathArray) - 1);
    }
    return $pathArray;

  }

}
