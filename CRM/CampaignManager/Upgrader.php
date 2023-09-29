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
    $this->updateCampaignTreeRecur();
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

  private function updateCampaignTreeRecur($parent_id = NULL, $depth = 1, $path = CRM_CampaignManager_BAO_CampaignTree::SEPARATOR) {
    $campaignsApi = \Civi\Api4\Campaign::get()
      ->addSelect('id', 'parent_id')
      ->addOrderBy('parent_id', 'ASC');
    if (is_null($parent_id)) {
      $campaignsApi->addWhere('parent_id', 'IS NULL');
    }
    else {
      $campaignsApi->addWhere('parent_id', '=', $parent_id);
    }
    $campaigns = $campaignsApi->execute();

    foreach ($campaigns as $campaign) {
      CRM_Core_DAO::executeQuery("INSERT INTO civicrm_campaign_tree (`campaign_id`, `path`, `depth`) VALUES (%1, %2, %3)",
        [
          1 => [$campaign['id'], 'Integer'],
          2 => [$path, 'String'],
          3 => [$depth, 'Integer'],
        ]
      );

      $this->updateCampaignTreeRecur(
        $campaign['id'],
        $depth + 1,
        $path . $campaign['id'] . CRM_CampaignManager_BAO_CampaignTree::SEPARATOR
      );
    }

  }

}
