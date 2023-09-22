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

      $is_parent = (bool) \Civi\Api4\Campaign::get()
        ->selectRowCount()
        ->addWhere('parent_id', '=', $campaignId)
        ->execute()
        ->count();
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
    $campaign['is_parent'] = $is_parent;
    $campaign['is_child'] = (bool) $campaign['parent_id'] ?? 0;
    unset($campaign['parent_id.title']);
    unset($campaign['status_id:label']);
    unset($campaign['campaign_type_id:label']);
    unset($campaign['campaign_status_override.is_override']);

    $this->assign('action', CRM_Core_Action::VIEW);
    $this->assign('campaign', $campaign);

    // get KPIs
    $allKPIs = \Civi\CampaignManager\Utils\ClassScanner::scanClasses('Civi\CampaignManager\KPI', 'Civi\CampaignManager\KPI\AbstractKPI', 'kpi');
    $campaignKpis = \Civi\Api4\CampaignKPI::get()
      ->addSelect('name', 'title', 'campaign_kpi_value.value', 'campaign_kpi_value.value_parent', 'campaign_kpi_value.last_modified_date')
      ->addJoin('CampaignKPIValue AS campaign_kpi_value', 'LEFT',
          ['campaign_kpi_value.campaign_kpi_id', '=', 'id'],
          ['campaign_kpi_value.campaign_id', '=', $campaignId]
        )
      ->addWhere('is_active', '=', TRUE)
      ->execute();

    $kpis = [];
    foreach ($campaignKpis as $key => $kpi) {
      if (isset($allKPIs[$kpi['name']])) {
        $className = '\\Civi\\CampaignManager\\KPI\\' . $allKPIs[$kpi['name']];
        $dataType = $className::getDataType();
        $kpis[] = [
          'id' => $kpi['id'],
          'name' => $kpi['name'],
          'title' => $kpi['title'],
          'value' => CRM_CampaignManager_BAO_CampaignKPIValue::formatDisplayValue($kpi['campaign_kpi_value.value'], $dataType),
          'value_parent' => CRM_CampaignManager_BAO_CampaignKPIValue::formatDisplayValue($kpi['campaign_kpi_value.value_parent'], $dataType),
          'last_modified_date' => $kpi['campaign_kpi_value.last_modified_date'],
        ];
      }
    }

    $this->assign('kpis', $kpis);

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
