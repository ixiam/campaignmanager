<?php
// phpcs:disable
use CRM_CampaignManager_ExtensionUtil as E;
// phpcs:enable

class CRM_CampaignManager_BAO_CampaignTree extends CRM_CampaignManager_DAO_CampaignTree {

  const SEPARATOR = ".";

  public static function updateCampaignTreeRecur($parent_id = NULL, $depth = 1, $path = self::SEPARATOR) {
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
      CRM_Core_DAO::executeQuery("REPLACE INTO civicrm_campaign_tree (`campaign_id`, `path`, `depth`) VALUES (%1, %2, %3)",
        [
          1 => [$campaign['id'], 'Integer'],
          2 => [$path, 'String'],
          3 => [$depth, 'Integer'],
        ]
      );

      self::updateCampaignTreeRecur(
        $campaign['id'],
        $depth + 1,
        $path . $campaign['id'] . self::SEPARATOR
      );
    }

  }

}
