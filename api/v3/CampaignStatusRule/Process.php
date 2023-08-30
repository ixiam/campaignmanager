<?php
// phpcs:disable
use CRM_CampaignManager_ExtensionUtil as E;
// phpcs:enable

/**
 * CampaignStatusRule.Process API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/api-architecture/
 */
function _civicrm_api3_campaign_status_rule_Process_spec(&$spec) {
  $spec['only_active_campaigns']['api.default'] = TRUE;
  $spec['only_active_campaigns']['title'] = 'Exclude disabled campaigns';
  $spec['only_active_campaigns']['description'] = 'Exclude disabled campaigns from calculations (default = TRUE)';
  $spec['only_active_campaigns']['type'] = CRM_Utils_Type::T_BOOLEAN;
}

/**
 * This api checks and updates the status of all campaigns
 *
 * @param array $params
 *   Input parameters
 *
 * @return bool
 *   true if success, else false
 */
function civicrm_api3_campaign_status_rule_Process($params) {
  $lock = Civi::lockManager()->acquire('worker.campaign.UpdateStatus');

  if (!$lock->isAcquired()) {
    return civicrm_api3_create_error('Could not acquire lock, another Campaign Status Update process is running');
  }

  if ($params['only_active_campaigns']) {
    $campaigns = \Civi\Api4\Campaign::get()
      ->addWhere('is_active', '=', TRUE)
      ->execute();
  }
  else {
    $campaigns = \Civi\Api4\Campaign::get()->execute();
  }

  try {
    foreach ($campaigns as $campaign) {
      $statusId = CRM_CampaignManager_BAO_CampaignStatusRule::getCampaignStatusByDate($startDate, $endDate, 'now', (array) $campaign);
      if ($statusId) {
        $results = \Civi\Api4\Campaign::update()
          ->addValue('status_id', $statusId)
          ->addWhere('id', '=', $campaign['id'])
          ->execute();
      }
    }
  }
  catch (Exception $e) {
    return civicrm_api3_create_error($e->getMessage());
  }
  finally{
    $lock->release();
  }

  return civicrm_api3_create_success(TRUE, $params, 'CampaignStatusRule', 'Process');
}
