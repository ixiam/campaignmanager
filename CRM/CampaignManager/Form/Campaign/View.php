<?php

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
    $campaignId = CRM_Utils_Request::retrieve('id', 'Positive', $this, TRUE);

    try {
      $campaign = \Civi\Api4\Campaign::get()
        ->addSelect('*', 'parent_id.id', 'parent_id.title', 'campaign_type_id:label')
        ->addWhere('id', '=', $campaignId)
        ->execute()
        ->single();
    }
    catch (Exception $e) {
      CRM_Core_Error::statusBounce(E::ts('The requested campaign record does not exist.'));
    }

    $finalTree = [];
    CRM_Core_BAO_CustomGroup::buildCustomDataView($this, $finalTree, FALSE, NULL, NULL, NULL, $participantID);
    $this->assign('campaign', $campaign);

    // add viewed participant to recent items list
    $url = CRM_Utils_System::url('civicrm/contact/view/participant',
      "action=view&reset=1&id={$values[$participantID]['id']}&cid={$values[$participantID]['contact_id']}&context=home"
    );
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
