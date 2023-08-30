<?php
// phpcs:disable
use CRM_CampaignManager_ExtensionUtil as E;
// phpcs:enable

/**
 * This class generates form components for Campaign View
 *
 */
class CRM_CampaignManager_Form_Campaign_View extends CRM_Core_Form {

  public $useLivePageJS = TRUE;

  /**
   * Set variables up before form is built.
   *
   * @return void
   */
  public function preProcess() {
    $values = $ids = [];
    $campaignId = CRM_Utils_Request::retrieve('id', 'Positive', $this);
    if (!$campaignId) {
      CRM_Core_Error::statusBounce(E::ts('Campaign Id missing.'));
    }

    try {
      $campaign = \Civi\Api4\Campaign::get()
        ->addSelect('*', 'parent_id.title', 'campaign_type_id:label', 'status_id:label', 'campaign_status_override.is_override')
        ->addJoin('CampaignStatusOverride AS campaign_status_override', 'LEFT')
        ->addWhere('id', '=', $campaignId)
        ->execute()
        ->single();
    }
    catch (Exception $e) {
      CRM_Core_Error::statusBounce(E::ts('The requested campaign record does not exist.'));
    }

    $groupTree = CRM_Core_BAO_CustomGroup::getTree('Campaign', NULL, $campaignId, 0, NULL, NULL,
      TRUE, NULL, FALSE, CRM_Core_Permission::VIEW, NULL, TRUE);
    CRM_Core_BAO_CustomGroup::buildCustomDataView($this, $groupTree, FALSE, NULL, NULL, NULL, $campaignId);

    // fix same key names for smarty compliance
    $campaign['parent_title'] = $campaign['parent_id.title'];
    $campaign['status_label'] = $campaign['status_id:label'];
    $campaign['campaign_type_label'] = $campaign['campaign_type_id:label'];
    $campaign['is_override'] = $campaign['campaign_status_override.is_override'] ? ts('Yes') : ts('No');
    unset($campaign['parent_id.title']);
    unset($campaign['status_id:label']);
    unset($campaign['campaign_type_id:label']);
    unset($campaign['campaign_status_override.is_override']);

    $this->assign('action', CRM_Core_Action::VIEW);
    $this->assign('campaign', $campaign);
  }

  /**
   * Build the form object.
   *
   * @return void
   */
  public function buildQuickForm() {
    $this->addButtons([
      [
        'type' => 'cancel',
        'name' => ts('Done'),
        'spacing' => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
        'isDefault' => TRUE,
      ],
    ]);
  }

}
