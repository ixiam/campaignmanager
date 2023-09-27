<?php
use CRM_CampaignManager_ExtensionUtil as E;

/**
 * CampaignKPI.Calculateall API
 *
 * @param array $params
 *
 * @return array
 *   API result descriptor
 *
 * @see civicrm_api3_create_success
 *
 * @throws API_Exception
 */
function civicrm_api3_campaign_k_p_i_Calculateall($params) {
  $lock = Civi::lockManager()->acquire('worker.campaign.CalculateAllKPIs');

  if (!$lock->isAcquired()) {
    return civicrm_api3_create_error('Could not acquire lock, another Campaign KPI Calculate process is running');
  }

  try {
    $return = \Civi\Api4\CampaignKPI::calculateAll()->setParentValue(TRUE)->execute();
  }
  catch (Exception $e) {
    return civicrm_api3_create_error($e->getMessage());
  }
  finally{
    $lock->release();
  }

  return civicrm_api3_create_success($return, $params, 'CampaignKPI', 'Calculateall');
}
