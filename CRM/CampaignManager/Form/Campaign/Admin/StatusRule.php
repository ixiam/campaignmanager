<?php
// phpcs:disable
use CRM_CampaignManager_ExtensionUtil as E;
// phpcs:enable

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_CampaignManager_Form_Campaign_Admin_StatusRule extends CRM_Core_Form {

  use CRM_Core_Form_EntityFormTrait;

  /**
   * Explicitly declare the entity api name.
   */
  public function getDefaultEntity() {
    return 'CampaignStatusRule';
  }

  /**
   * Explicitly declare the form context.
   */
  public function getDefaultContext() {
    return 'create';
  }

  /**
   * Set the delete message.
   *
   */
  public function setDeleteMessage() {
    $this->deleteMessage = E::ts('You  are going to delete this Campaign Status Rule. Do you want to continue?');
  }

  /**
   * Set entity fields to be assigned to the form.
   */
  protected function setEntityFields() {
    $this->entityFields = [];
  }

  public function preProcess() {
    // Lookup id from URL or stored value in controller
    $this->_id = CRM_Utils_Request::retrieve('id', 'Positive', $this);
    $this->_BAOName = 'CRM_CampaignManager_BAO_CampaignStatusRule';
  }

  /**
   * Set default values for the form. MobileProvider that in edit/view mode
   * the default values are retrieved from the database
   *
   * @return array
   */
  public function setDefaultValues() {
    $defaults = $this->getEntityDefaults();

    if ($this->_action & CRM_Core_Action::ADD) {
      $defaults['is_active'] = 1;
    }

    //finding default weight to be put
    if (empty($defaults['weight'])) {
      $defaults['weight'] = CRM_Utils_Weight::getDefaultWeight('CRM_CampaignManager_DAO_CampaignStatusRule');
    }
    return $defaults;
  }

  /**
   * Build the form object.
   */
  public function buildQuickForm() {
    self::buildQuickEntityForm();
    parent::buildQuickForm();

    if ($this->_action & CRM_Core_Action::DELETE) {
      return;
    }

    $optionValues = \Civi\Api4\OptionValue::get()
      ->addSelect('label', 'value')
      ->addWhere('option_group_id:name', '=', 'campaign_status')
      ->execute()
      ->indexBy('value')
      ->column('label');
    $statusId = $this->addElement('select', 'status_id', E::ts('Campaign Status'),
      ['' => E::ts('- select Status -')] + $optionValues,
      ['class' => 'crm-select2']
    );
    if ($this->_id) {
      $statusId->freeze();
      $this->assign('id', $this->_id);
    }
    else {
      $this->addRule('status_id', E::ts('A campaign status rule with this status already exists. Please select another status.'),
        'objectExists', [
          'CRM_CampaignManager_DAO_CampaignStatusRule',
          $this->_id,
          'status_id',
        ]
      );
    }

    $this->add('select', 'start_event', ts('Start Event'), ['' => ts('- select -')] + CRM_CampaignManager_BAO_CampaignStatusRule::eventDate());
    $this->add('select', 'start_event_adjust_unit', ts('Start Event Adjustment'), ['' => ts('- select -')] + CRM_Core_SelectValues::unitList());
    $this->add('text', 'start_event_adjust_interval', ts('Start Event Adjust Interval'),
      CRM_Core_DAO::getAttribute('CRM_CampaignManager_DAO_CampaignStatusRule', 'start_event_adjust_interval')
    );
    $this->add('select', 'end_event', ts('End Event'), ['' => ts('- select -')] + CRM_CampaignManager_BAO_CampaignStatusRule::eventDate());
    $this->add('select', 'end_event_adjust_unit', ts('End Event Adjustment'), ['' => ts('- select -')] + CRM_Core_SelectValues::unitList());
    $this->add('text', 'end_event_adjust_interval', ts('End Event Adjust Interval'),
      CRM_Core_DAO::getAttribute('CRM_CampaignManager_DAO_CampaignStatusRule', 'end_event_adjust_interval')
    );
    $this->add('number', 'weight', ts('Order'),
      CRM_Core_DAO::getAttribute('CRM_CampaignManager_DAO_CampaignStatusRule', 'weight')
    );
    $this->add('checkbox', 'is_default', ts('Default?'));
    $this->add('checkbox', 'is_active', ts('Enabled?'));
  }

  /**
   * Process the form submission.
   */
  public function postProcess() {
    if ($this->_action & CRM_Core_Action::DELETE) {
      try {
        CRM_CampaignManager_BAO_CampaignStatusRule::deleteRecord(['id' => $this->_id]);
      }
      catch (CRM_Core_Exception $e) {
        CRM_Core_Error::statusBounce($e->getMessage(), NULL, ts('Delete Failed'));
      }
      CRM_Core_Session::setStatus(ts('Selected membership status has been deleted.'), ts('Record Deleted'), 'success');
    }
    else {
      // store the submitted values in an array
      $params = $this->exportValues();
      $params['is_active'] = CRM_Utils_Array::value('is_active', $params, FALSE);
      $params['is_default'] = CRM_Utils_Array::value('is_default', $params, FALSE);

      if ($this->_action & CRM_Core_Action::UPDATE) {
        $params['id'] = $this->getEntityId();
      }
      $oldWeight = NULL;
      if ($this->_id) {
        $oldWeight = CRM_Core_DAO::getFieldValue('CRM_CampaignManager_DAO_CampaignStatusRule', $this->_id, 'weight', 'id');
      }
      $params['weight'] = CRM_Utils_Weight::updateOtherWeights('CRM_CampaignManager_DAO_CampaignStatusRule', $oldWeight, $params['weight']);

      CRM_CampaignManager_BAO_CampaignStatusRule::add($params);
      CRM_Core_Session::setStatus(E::ts('The campaign status rule has been saved.', []), ts('Saved'), 'success');
    }
  }

}
