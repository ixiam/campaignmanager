<?php
// phpcs:disable
use CRM_CampaignManager_ExtensionUtil as E;
// phpcs:enable

/**
 * Collection of upgrade steps.
 */
class CRM_CampaignManager_Upgrader extends CRM_Extension_Upgrader_Base {

  public function install() {
    // Enable CiviCampaign
    CRM_Core_BAO_ConfigSetting::enableComponent('CiviCampaign');
    CRM_CampaignManager_BAO_CampaignStatusRule::createDefaultRules();
    CRM_CampaignManager_BAO_CampaignTree::updateCampaignTreeRecur();
    $this->updateCampaignOverride();

    return TRUE;
  }

  private function updateCampaignOverride() {
    $campaigns = \Civi\Api4\Campaign::get()
      ->addSelect('id', 'parent_id')
      ->addOrderBy('parent_id', 'ASC')
      ->execute();
    foreach ($campaigns as $campaign) {
      CRM_Core_DAO::executeQuery("INSERT INTO civicrm_campaign_status_override (`campaign_id`, `is_override`) VALUES (%1, %2)",
        [
          1 => [$campaign['id'], 'Integer'],
          2 => [0, 'Integer'],
        ]
      );
    }
  }

}
