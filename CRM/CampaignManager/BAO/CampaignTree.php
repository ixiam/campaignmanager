<?php
use CRM_CampaignManager_ExtensionUtil as E;

class CRM_CampaignManager_BAO_CampaignTree extends CRM_CampaignManager_DAO_CampaignTree {

  /**
   * Create a new CampaignTree based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Campaignmanager_DAO_CampaignTree|NULL
   *
   * public static function create($params) {
   * $className = 'CRM_Campaignmanager_DAO_CampaignTree';
   * $entityName = 'CampaignTree';
   * $hook = empty($params['id']) ? 'create' : 'edit';
   *
   * CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
   * $instance = new $className();
   * $instance->copyValues($params);
   * $instance->save();
   * CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);
   *
   * return $instance;
   * } */

}
