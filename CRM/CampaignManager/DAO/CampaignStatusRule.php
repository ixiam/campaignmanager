<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 *
 * Generated from campaignmanager/xml/schema/CRM/CampaignManager/CampaignStatusRule.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:181b7a8c0d190be5ab60086d4a190092)
 */
use CRM_CampaignManager_ExtensionUtil as E;

/**
 * Database access object for the CampaignStatusRule entity.
 */
class CRM_CampaignManager_DAO_CampaignStatusRule extends CRM_Core_DAO {
  const EXT = E::LONG_NAME;
  const TABLE_ADDED = '';

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_campaign_status_rule';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  public static $_log = FALSE;

  /**
   * Paths for accessing this entity in the UI.
   *
   * @var string[]
   */
  protected static $_paths = [
    'add' => 'civicrm/admin/campaign/status/edit?reset=1&action=add',
    'view' => 'civicrm/admin/campaign/status/edit?reset=1&action=view&id=[id]',
    'update' => 'civicrm/admin/campaign/status/edit?reset=1&action=update&id=[id]',
    'delete' => 'civicrm/admin/campaign/status/edit?reset=1&action=delete&id=[id]',
  ];

  /**
   * Campaign ID
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $id;

  /**
   * Campaign status ID.Implicit FK to civicrm_option_value where option_group = campaign_status
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $status_id;

  /**
   * Event when this status starts.
   *
   * @var string|null
   *   (SQL type: varchar(12))
   *   Note that values will be retrieved from the database as a string.
   */
  public $start_event;

  /**
   * Unit used for adjusting from start_event.
   *
   * @var string|null
   *   (SQL type: varchar(8))
   *   Note that values will be retrieved from the database as a string.
   */
  public $start_event_adjust_unit;

  /**
   * Status range begins this many units from start_event.
   *
   * @var int|string|null
   *   (SQL type: int)
   *   Note that values will be retrieved from the database as a string.
   */
  public $start_event_adjust_interval;

  /**
   * Event after which this status ends.
   *
   * @var string|null
   *   (SQL type: varchar(12))
   *   Note that values will be retrieved from the database as a string.
   */
  public $end_event;

  /**
   * Unit used for adjusting from the ending event.
   *
   * @var string|null
   *   (SQL type: varchar(8))
   *   Note that values will be retrieved from the database as a string.
   */
  public $end_event_adjust_unit;

  /**
   * Status range ends this many units from end_event.
   *
   * @var int|string|null
   *   (SQL type: int)
   *   Note that values will be retrieved from the database as a string.
   */
  public $end_event_adjust_interval;

  /**
   * @var int|string|null
   *   (SQL type: int)
   *   Note that values will be retrieved from the database as a string.
   */
  public $weight;

  /**
   * Assign this status to a membership record if no other status match is found.
   *
   * @var bool|string
   *   (SQL type: tinyint)
   *   Note that values will be retrieved from the database as a string.
   */
  public $is_default;

  /**
   * Is this membership_status enabled.
   *
   * @var bool|string
   *   (SQL type: tinyint)
   *   Note that values will be retrieved from the database as a string.
   */
  public $is_active;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'civicrm_campaign_status_rule';
    parent::__construct();
  }

  /**
   * Returns localized title of this entity.
   *
   * @param bool $plural
   *   Whether to return the plural version of the title.
   */
  public static function getEntityTitle($plural = FALSE) {
    return $plural ? E::ts('Campaign Status Rules') : E::ts('Campaign Status Rule');
  }

  /**
   * Returns all the column names of this table
   *
   * @return array
   */
  public static function &fields() {
    if (!isset(Civi::$statics[__CLASS__]['fields'])) {
      Civi::$statics[__CLASS__]['fields'] = [
        'id' => [
          'name' => 'id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Campaign Status Rule ID'),
          'description' => E::ts('Campaign ID'),
          'required' => TRUE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_campaign_status_rule.id',
          'table_name' => 'civicrm_campaign_status_rule',
          'entity' => 'CampaignStatusRule',
          'bao' => 'CRM_CampaignManager_DAO_CampaignStatusRule',
          'localizable' => 0,
          'html' => [
            'type' => 'Number',
          ],
          'readonly' => TRUE,
          'add' => NULL,
        ],
        'status_id' => [
          'name' => 'status_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Campaign Status'),
          'description' => E::ts('Campaign status ID.Implicit FK to civicrm_option_value where option_group = campaign_status'),
          'usage' => [
            'import' => TRUE,
            'export' => TRUE,
            'duplicate_matching' => TRUE,
            'token' => FALSE,
          ],
          'import' => TRUE,
          'where' => 'civicrm_campaign_status_rule.status_id',
          'export' => TRUE,
          'default' => NULL,
          'table_name' => 'civicrm_campaign_status_rule',
          'entity' => 'CampaignStatusRule',
          'bao' => 'CRM_CampaignManager_DAO_CampaignStatusRule',
          'localizable' => 0,
          'html' => [
            'type' => 'Select',
            'label' => E::ts("Status"),
          ],
          'pseudoconstant' => [
            'optionGroupName' => 'campaign_status',
            'optionEditPath' => 'civicrm/admin/options/campaign_status',
          ],
          'add' => '3.3',
        ],
        'start_event' => [
          'name' => 'start_event',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Start Event'),
          'description' => E::ts('Event when this status starts.'),
          'maxlength' => 12,
          'size' => CRM_Utils_Type::TWELVE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_campaign_status_rule.start_event',
          'table_name' => 'civicrm_campaign_status_rule',
          'entity' => 'CampaignStatusRule',
          'bao' => 'CRM_CampaignManager_DAO_CampaignStatusRule',
          'localizable' => 0,
          'html' => [
            'type' => 'Select',
            'label' => E::ts("Start Event"),
          ],
          'pseudoconstant' => [
            'callback' => 'CRM_CampaignManager_BAO_CampaignStatusRule::eventDate',
          ],
          'add' => NULL,
        ],
        'start_event_adjust_unit' => [
          'name' => 'start_event_adjust_unit',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Start Event Adjust Unit'),
          'description' => E::ts('Unit used for adjusting from start_event.'),
          'maxlength' => 8,
          'size' => CRM_Utils_Type::EIGHT,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_campaign_status_rule.start_event_adjust_unit',
          'table_name' => 'civicrm_campaign_status_rule',
          'entity' => 'CampaignStatusRule',
          'bao' => 'CRM_CampaignManager_DAO_CampaignStatusRule',
          'localizable' => 0,
          'html' => [
            'type' => 'Select',
            'label' => E::ts("Start Event Adjust Unit"),
          ],
          'pseudoconstant' => [
            'callback' => 'CRM_Core_SelectValues::unitList',
          ],
          'add' => NULL,
        ],
        'start_event_adjust_interval' => [
          'name' => 'start_event_adjust_interval',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Start Event Adjust Interval'),
          'description' => E::ts('Status range begins this many units from start_event.'),
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_campaign_status_rule.start_event_adjust_interval',
          'table_name' => 'civicrm_campaign_status_rule',
          'entity' => 'CampaignStatusRule',
          'bao' => 'CRM_CampaignManager_DAO_CampaignStatusRule',
          'localizable' => 0,
          'html' => [
            'label' => E::ts("Start Event Adjust Interval"),
          ],
          'add' => NULL,
        ],
        'end_event' => [
          'name' => 'end_event',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('End Event'),
          'description' => E::ts('Event after which this status ends.'),
          'maxlength' => 12,
          'size' => CRM_Utils_Type::TWELVE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_campaign_status_rule.end_event',
          'table_name' => 'civicrm_campaign_status_rule',
          'entity' => 'CampaignStatusRule',
          'bao' => 'CRM_CampaignManager_DAO_CampaignStatusRule',
          'localizable' => 0,
          'html' => [
            'type' => 'Select',
            'label' => E::ts("End Event"),
          ],
          'pseudoconstant' => [
            'callback' => 'CRM_Core_SelectValues::eventDate',
          ],
          'add' => NULL,
        ],
        'end_event_adjust_unit' => [
          'name' => 'end_event_adjust_unit',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('End Event Adjust Unit'),
          'description' => E::ts('Unit used for adjusting from the ending event.'),
          'maxlength' => 8,
          'size' => CRM_Utils_Type::EIGHT,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_campaign_status_rule.end_event_adjust_unit',
          'table_name' => 'civicrm_campaign_status_rule',
          'entity' => 'CampaignStatusRule',
          'bao' => 'CRM_CampaignManager_DAO_CampaignStatusRule',
          'localizable' => 0,
          'html' => [
            'type' => 'Select',
            'label' => E::ts("End Event Adjust Unit"),
          ],
          'pseudoconstant' => [
            'callback' => 'CRM_Core_SelectValues::unitList',
          ],
          'add' => NULL,
        ],
        'end_event_adjust_interval' => [
          'name' => 'end_event_adjust_interval',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('End Event Adjust Interval'),
          'description' => E::ts('Status range ends this many units from end_event.'),
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_campaign_status_rule.end_event_adjust_interval',
          'table_name' => 'civicrm_campaign_status_rule',
          'entity' => 'CampaignStatusRule',
          'bao' => 'CRM_CampaignManager_DAO_CampaignStatusRule',
          'localizable' => 0,
          'html' => [
            'label' => E::ts("End Event Adjust Interval"),
          ],
          'add' => NULL,
        ],
        'weight' => [
          'name' => 'weight',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Order'),
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_campaign_status_rule.weight',
          'table_name' => 'civicrm_campaign_status_rule',
          'entity' => 'CampaignStatusRule',
          'bao' => 'CRM_CampaignManager_DAO_CampaignStatusRule',
          'localizable' => 0,
          'add' => NULL,
        ],
        'is_default' => [
          'name' => 'is_default',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => E::ts('Default Status?'),
          'description' => E::ts('Assign this status to a membership record if no other status match is found.'),
          'required' => TRUE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_campaign_status_rule.is_default',
          'default' => '0',
          'table_name' => 'civicrm_campaign_status_rule',
          'entity' => 'CampaignStatusRule',
          'bao' => 'CRM_CampaignManager_DAO_CampaignStatusRule',
          'localizable' => 0,
          'html' => [
            'type' => 'CheckBox',
            'label' => E::ts("Default"),
          ],
          'add' => NULL,
        ],
        'is_active' => [
          'name' => 'is_active',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => E::ts('Is Active'),
          'description' => E::ts('Is this membership_status enabled.'),
          'required' => TRUE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_campaign_status_rule.is_active',
          'default' => '1',
          'table_name' => 'civicrm_campaign_status_rule',
          'entity' => 'CampaignStatusRule',
          'bao' => 'CRM_CampaignManager_DAO_CampaignStatusRule',
          'localizable' => 0,
          'html' => [
            'type' => 'CheckBox',
            'label' => E::ts("Enabled"),
          ],
          'add' => NULL,
        ],
      ];
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'fields_callback', Civi::$statics[__CLASS__]['fields']);
    }
    return Civi::$statics[__CLASS__]['fields'];
  }

  /**
   * Return a mapping from field-name to the corresponding key (as used in fields()).
   *
   * @return array
   *   Array(string $name => string $uniqueName).
   */
  public static function &fieldKeys() {
    if (!isset(Civi::$statics[__CLASS__]['fieldKeys'])) {
      Civi::$statics[__CLASS__]['fieldKeys'] = array_flip(CRM_Utils_Array::collect('name', self::fields()));
    }
    return Civi::$statics[__CLASS__]['fieldKeys'];
  }

  /**
   * Returns the names of this table
   *
   * @return string
   */
  public static function getTableName() {
    return self::$_tableName;
  }

  /**
   * Returns if this table needs to be logged
   *
   * @return bool
   */
  public function getLog() {
    return self::$_log;
  }

  /**
   * Returns the list of fields that can be imported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &import($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'campaign_status_rule', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of fields that can be exported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &export($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'campaign_status_rule', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of indices
   *
   * @param bool $localize
   *
   * @return array
   */
  public static function indices($localize = TRUE) {
    $indices = [];
    return ($localize && !empty($indices)) ? CRM_Core_DAO_AllCoreTables::multilingualize(__CLASS__, $indices) : $indices;
  }

}
