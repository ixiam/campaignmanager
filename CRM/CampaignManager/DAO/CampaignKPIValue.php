<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 *
 * Generated from campaignmanager/xml/schema/CRM/CampaignManager/CampaignKPIValue.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:e8dd4d430e090f1f52c79fd140353795)
 */
use CRM_CampaignManager_ExtensionUtil as E;

/**
 * Database access object for the CampaignKPIValue entity.
 */
class CRM_CampaignManager_DAO_CampaignKPIValue extends CRM_Core_DAO {
  const EXT = E::LONG_NAME;
  const TABLE_ADDED = '';

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_campaign_kpi_value';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  public static $_log = TRUE;

  /**
   * Unique CampaignKPIValue ID
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $id;

  /**
   * Campaign KPI ID
   *
   * @var int|string
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $campaign_kpi_id;

  /**
   * Campaign ID
   *
   * @var int|string
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $campaign_id;

  /**
   * KPI value.
   *
   * @var string|null
   *   (SQL type: varchar(255))
   *   Note that values will be retrieved from the database as a string.
   */
  public $value;

  /**
   * KPI Parent value.
   *
   * @var string|null
   *   (SQL type: varchar(255))
   *   Note that values will be retrieved from the database as a string.
   */
  public $value_parent;

  /**
   * Date and time that Campaign was edited last time.
   *
   * @var string|null
   *   (SQL type: datetime)
   *   Note that values will be retrieved from the database as a string.
   */
  public $last_modified_date;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'civicrm_campaign_kpi_value';
    parent::__construct();
  }

  /**
   * Returns localized title of this entity.
   *
   * @param bool $plural
   *   Whether to return the plural version of the title.
   */
  public static function getEntityTitle($plural = FALSE) {
    return $plural ? E::ts('Campaign KPIValues') : E::ts('Campaign KPIValue');
  }

  /**
   * Returns foreign keys and entity references.
   *
   * @return array
   *   [CRM_Core_Reference_Interface]
   */
  public static function getReferenceColumns() {
    if (!isset(Civi::$statics[__CLASS__]['links'])) {
      Civi::$statics[__CLASS__]['links'] = static::createReferenceColumns(__CLASS__);
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'campaign_kpi_id', 'civicrm_campaign_kpi', 'id');
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'campaign_id', 'civicrm_campaign', 'id');
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'links_callback', Civi::$statics[__CLASS__]['links']);
    }
    return Civi::$statics[__CLASS__]['links'];
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
          'title' => E::ts('ID'),
          'description' => E::ts('Unique CampaignKPIValue ID'),
          'required' => TRUE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_campaign_kpi_value.id',
          'table_name' => 'civicrm_campaign_kpi_value',
          'entity' => 'CampaignKPIValue',
          'bao' => 'CRM_CampaignManager_DAO_CampaignKPIValue',
          'localizable' => 0,
          'html' => [
            'type' => 'Number',
          ],
          'readonly' => TRUE,
          'add' => NULL,
        ],
        'campaign_kpi_id' => [
          'name' => 'campaign_kpi_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Campaign Kpi ID'),
          'description' => E::ts('Campaign KPI ID'),
          'required' => TRUE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_campaign_kpi_value.campaign_kpi_id',
          'table_name' => 'civicrm_campaign_kpi_value',
          'entity' => 'CampaignKPIValue',
          'bao' => 'CRM_CampaignManager_DAO_CampaignKPIValue',
          'localizable' => 0,
          'FKClassName' => 'CRM_CampaignManager_DAO_CampaignKPI',
          'add' => NULL,
        ],
        'campaign_id' => [
          'name' => 'campaign_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Campaign ID'),
          'description' => E::ts('Campaign ID'),
          'required' => TRUE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_campaign_kpi_value.campaign_id',
          'table_name' => 'civicrm_campaign_kpi_value',
          'entity' => 'CampaignKPIValue',
          'bao' => 'CRM_CampaignManager_DAO_CampaignKPIValue',
          'localizable' => 0,
          'FKClassName' => 'CRM_Campaign_DAO_Campaign',
          'html' => [
            'type' => 'Number',
          ],
          'add' => NULL,
        ],
        'value' => [
          'name' => 'value',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Value'),
          'description' => E::ts('KPI value.'),
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_campaign_kpi_value.value',
          'table_name' => 'civicrm_campaign_kpi_value',
          'entity' => 'CampaignKPIValue',
          'bao' => 'CRM_CampaignManager_DAO_CampaignKPIValue',
          'localizable' => 0,
          'add' => NULL,
        ],
        'value_parent' => [
          'name' => 'value_parent',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Value Parent'),
          'description' => E::ts('KPI Parent value.'),
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_campaign_kpi_value.value_parent',
          'table_name' => 'civicrm_campaign_kpi_value',
          'entity' => 'CampaignKPIValue',
          'bao' => 'CRM_CampaignManager_DAO_CampaignKPIValue',
          'localizable' => 0,
          'add' => NULL,
        ],
        'last_modified_date' => [
          'name' => 'last_modified_date',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => E::ts('Campaign KPI value Modified Date'),
          'description' => E::ts('Date and time that Campaign was edited last time.'),
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_campaign_kpi_value.last_modified_date',
          'table_name' => 'civicrm_campaign_kpi_value',
          'entity' => 'CampaignKPIValue',
          'bao' => 'CRM_CampaignManager_DAO_CampaignKPIValue',
          'localizable' => 0,
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
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'campaign_kpi_value', $prefix, []);
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
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'campaign_kpi_value', $prefix, []);
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
    $indices = [
      'UI_campaign_kpi_id' => [
        'name' => 'UI_campaign_kpi_id',
        'field' => [
          0 => 'campaign_kpi_id',
          1 => 'campaign_id',
        ],
        'localizable' => FALSE,
        'unique' => TRUE,
        'sig' => 'civicrm_campaign_kpi_value::1::campaign_kpi_id::campaign_id',
      ],
    ];
    return ($localize && !empty($indices)) ? CRM_Core_DAO_AllCoreTables::multilingualize(__CLASS__, $indices) : $indices;
  }

}
