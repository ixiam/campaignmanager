<?php
// phpcs:disable
use CRM_CampaignManager_ExtensionUtil as E;
// phpcs:enable

class CRM_CampaignManager_BAO_CampaignKPIValue extends CRM_CampaignManager_DAO_CampaignKPIValue {

  /**
   * Logic for rendering a kpi value
   *
   * @param string $value
   * @param int $dataType
   *
   * @return string
   * @throws \Brick\Money\Exception\UnknownCurrencyException
   */
  public static function formatDisplayValue($value, $dataType) {

    switch ($dataType) {
      case CRM_Utils_Type::T_STRING:
        $display = $value;
        break;

      case CRM_Utils_Type::T_DATE:
        $display = CRM_Utils_Date::processDate($value, NULL, FALSE, NULL);
        break;

      case CRM_Utils_Type::T_BOOLEAN:
        $booleanYesNo = CRM_Core_SelectValues::boolean();
        $display = $booleanYesNo[$value] ?? $value;
        break;

      case CRM_Utils_Type::T_MONEY:
        $display = CRM_Utils_Money::format($value);
        break;

      case CRM_Utils_Type::T_INT:
      case CRM_Utils_Type::T_FLOAT:
        $display = CRM_Utils_Number::formatLocaleNumeric($value);
        break;

      default:
        $display = $value;
        break;
    }
    return (string) $display;
  }

}
