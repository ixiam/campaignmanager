<?php
// phpcs:disable
use CRM_CampaignManager_ExtensionUtil as E;
// phpcs:enable

/**
 * Collection of upgrade steps.
 */
class CRM_CampaignManager_Upgrader extends CRM_Extension_Upgrader_Base {

  public function install() {
    // old campaign extension transition
    $manager = CRM_Extension_System::singleton()->getManager();
    if ($manager->getStatus('de.systopia.campaign') === $manager::STATUS_INSTALLED) {
      //ToDo
    }

    // Enable CiviCampaign
    CRM_Core_BAO_ConfigSetting::enableComponent('CiviCampaign');
    CRM_CampaignManager_BAO_CampaignStatusRule::createDefaultRules();

    return TRUE;
  }

}
