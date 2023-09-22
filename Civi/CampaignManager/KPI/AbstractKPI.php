<?php

namespace Civi\CampaignManager\KPI;

/**
 * AbstractKPI
 * @package Civi\CampaignManager\KPI
 */
abstract class AbstractKPI {

  /**
   * override these attributes in class implementation
   */


  /**
   * KPI name
   *
   * @var string
   */
  protected static $name = "kpi_name";

  /**
   * KPI display Title
   *
   * @var string
   */
  protected static $title = "KPI Title";

  /**
   * KPI data type
   * available type so far T_FLOAT / T_INT / T_STRING / T_DATE / T_BOOLEAN / T_MONEY
   *
   * @var int
   */
  protected static $dataType = \CRM_Utils_Type::T_INT;

  /**
   * Calculate KPI
   *
   * @param string $campaign_id
   *   Input string
   * @return string
   *   Output string
   */
  abstract public static function calculate($campaign_id);

  /**
   * Return class::name
   *
   * @return string
   */
  public static function getName() {
    return static::$name;
  }

  /**
   * Return class::title
   *
   * @return string
   */
  public static function getTitle() {
    return static::$title;
  }

  /**
   * Return class::dataType
   *
   * @return string
   */
  public static function getDataType() {
    return static::$dataType;
  }

}
