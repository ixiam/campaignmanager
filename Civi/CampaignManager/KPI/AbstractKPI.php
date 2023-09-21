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
  protected static $name = "kpi_name";
  protected static $title = "KPI Title";

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

}
