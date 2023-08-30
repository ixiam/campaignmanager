<?php
// phpcs:disable
use CRM_CampaignManager_ExtensionUtil as E;
// phpcs:enable

abstract class CRM_CampaignManager_Utils_Hook extends CRM_Utils_Hook {

  /**
   * This hook is invoked when building a CiviCRM form. This hook should also
   * be used to set the default values of a form element
   *
   * @param int $statusId
   *   The calculated Status Id
   * @param array $arguments
   *   References to values used to calculate the Status
   * @param array $campaign
   *   Reference to campaign values
   *
   * @return null
   *   the return value is ignored
   */
  public static function alterCalculatedCampaignStatus(&$statusId, $arguments, $campaign) {
    $null = NULL;
    return self::singleton()->invoke(['statusId', 'arguments', 'campaign'],
      $statusId, $arguments, $campaign,
      $null, $null, $null,
      'civicrm_alterCalculatedCampaignStatus'
    );
  }

}
