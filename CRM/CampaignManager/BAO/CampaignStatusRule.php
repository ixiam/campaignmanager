<?php
// phpcs:disable
use CRM_CampaignManager_ExtensionUtil as E;
// phpcs:enable

class CRM_CampaignManager_BAO_CampaignStatusRule extends CRM_CampaignManager_DAO_CampaignStatusRule {

  /**
   * Takes an associative array and creates a campaign status object.
   *
   * @param array $params
   *   Array of name/value pairs.
   *
   * @throws CRM_Core_Exception
   * @return CRM_CampaignManager_DAO_CampaignStatusRule
   */
  public static function create($params) {
    if (empty($params['id'])) {
      //don't allow duplicate names - if id not set
      $status = new CRM_CampaignManager_DAO_CampaignStatusRule();
      $status->status_id = $params['status_id'];
      if ($status->find(TRUE)) {
        throw new CRM_Core_Exception('A campaign status rule with this status_id already exists.');
      }
    }
    return self::add($params);
  }

  /**
   * Add the campaign status.
   *
   * @param array $params
   *   Reference array contains the values submitted by the form.
   * @param array $ids
   *   Array contains the id - this param is deprecated.
   *
   * @return CRM_CampaignManager_DAO_CampaignStatusRule
   */
  public static function add(&$params, $ids = []) {
    if (!empty($ids)) {
      CRM_Core_Error::deprecatedFunctionWarning('ids is a deprecated parameter');
    }
    $id = $params['id'] ?? NULL;

    // set all other defaults to false.
    if (!empty($params['is_default'])) {
      $query = "UPDATE civicrm_campaign_status_rule SET is_default = 0";
      CRM_Core_DAO::executeQuery($query);
    }

    // action is taken depending upon the mode
    $campaignStatus = new CRM_CampaignManager_DAO_CampaignStatusRule();
    $campaignStatus->copyValues($params);
    $campaignStatus->id = $id;
    $campaignStatus->save();
    return $campaignStatus;
  }

  /**
   * @deprecated
   * @param array $params
   * @param array $defaults
   * @return self|null
   */
  public static function retrieve($params, &$defaults) {
    CRM_Core_Error::deprecatedFunctionWarning('API');
    return self::commonRetrieve(self::class, $params, $defaults);
  }

  /**
   * Various pre defined event dates.
   *
   * @return array
   */
  public static function eventDate() {
    return [
      'start_date' => E::ts('Campaign Start Date'),
      'end_date' => E::ts('Campaign End Date'),
    ];
  }

  /**
   * Find the campaign status based on start date & end date.
   *
   * Loop through all the campaign status rules, ordered by their
   * weight. For each, we loop through all possible variations of the given
   * start and end dates and adjust the starts and ends based on that
   * campaign status's rules, where the last computed set of adjusted start
   * and end becomes a candidate. Then we compare that candidate to either
   * "today" or some other given date, and if it falls between the adjusted
   * start and end we have a match and we stop looping through status
   * definitions. Then we call a hook in case that wasn't enough loops.
   *
   * @param string $startDate
   *   Start date of the member whose campaign status is to be calculated.
   * @param string $endDate
   *   End date of the member whose campaign status is to be calculated.
   * @param string $statusDate
   *   Either the string "today" or a date against which we compare the adjusted start and end based on the status rules.
   * @param array $campaign
   *   Campaign params as available to calling function - not used directly but passed to the hook.
   *
   * @return int|NULL
   */
  public static function getCampaignStatusByDate($startDate, $endDate, $statusDate = 'now', $campaign = []) {
    $statusId = NULL;
    if (!$statusDate || $statusDate === 'today') {
      $statusDate = 'now';
      CRM_Core_Error::deprecatedFunctionWarning('pass now rather than today in');
    }
    $statusDate = date('Ymd', CRM_Utils_Time::strtotime($statusDate));
    $dates = [
      'start' => ($startDate && $startDate !== 'null') ? date('Ymd', CRM_Utils_Time::strtotime($startDate)) : '',
      'end' => ($endDate && $endDate !== 'null') ? date('Ymd', CRM_Utils_Time::strtotime($endDate)) : '',
    ];

    $campaignStatusRules = \Civi\Api4\CampaignStatusRule::get(FALSE)
      ->addWhere('is_active', '=', TRUE)
      ->addOrderBy('weight', 'ASC')
      ->execute();
    foreach ($campaignStatusRules as $rule) {
      $ruleDates = [
        'start' => NULL,
        'end' => NULL,
      ];
      foreach (['start', 'end'] as $eve) {
        foreach ($dates as $dat => $date) {
          // calculate start-event/date and end-event/date
          if (($rule[$eve . '_event'] === $dat . '_date') && $date) {
            if ($rule[$eve . '_event_adjust_unit'] && $rule[$eve . '_event_adjust_interval']) {
              $month = date('m', CRM_Utils_Time::strtotime($date));
              $day = date('d', CRM_Utils_Time::strtotime($date));
              $year = date('Y', CRM_Utils_Time::strtotime($date));
              // add in months
              if ($rule[$eve . '_event_adjust_unit'] === 'month') {
                $ruleDates[$eve] = date('Ymd', mktime(0, 0, 0,
                  $month + $rule[$eve . '_event_adjust_interval'],
                  $day,
                  $year
                ));
              }
              // add in days
              if ($rule[$eve . '_event_adjust_unit'] === 'day') {
                $ruleDates[$eve] = date('Ymd', mktime(0, 0, 0,
                  $month,
                  $day + $rule[$eve . '_event_adjust_interval'],
                  $year
                ));
              }
              // add in years
              if ($rule[$eve . '_event_adjust_unit'] === 'year') {
                $ruleDates[$eve] = date('Ymd', mktime(0, 0, 0,
                  $month,
                  $day,
                  $year + $rule[$eve . '_event_adjust_interval']
                ));
              }
              // if no interval and unit, present
            }
            else {
              $ruleDates[$eve] = $date;
            }
          }
        }
      }

      // check if statusDate is in the range of start & end events.
      if ($ruleDates['start'] && $ruleDates['end']) {
        if (($statusDate >= $ruleDates['start']) && ($statusDate <= $ruleDates['end'])) {
          $statusId = $rule['status_id'];
        }
      }
      elseif ($ruleDates['start']) {
        if ($statusDate >= $ruleDates['start']) {
          $statusId = $rule['status_id'];
        }
      }
      elseif ($ruleDates['end']) {
        if ($statusDate <= $ruleDates['end']) {
          $statusId = $rule['status_id'];
        }
      }

      // returns FIRST status record for which status_date is in range.
      if ($statusId) {
        break;
      }
    }

    $arguments = [
      'start_date' => $startDate,
      'end_date' => $endDate,
      'status_date' => $statusDate,
      'start_event' => $ruleDates['start'],
      'end_event' => $ruleDates['end'],
    ];
    CRM_CampaignManager_Utils_Hook::alterCalculatedCampaignStatus($statusId, $arguments, $campaign);

    return $statusId;
  }

  /**
   * Find the campaign status based on campaign_id
   *
   * @param int $campaignId
   *   Campaign Id
   *
   * @return int|NULL
   */
  public static function getCampaignStatusById($campaignId) {
    $campaign = \Civi\Api4\Campaign::get()
      ->addWhere('id', '=', $campaignId)
      ->execute()
      ->single();
    return self::getCampaignStatusByDate($campaign['start_date'], $campaign['end_date'], 'now', $campaign);
  }

  /**
   * Create default rules for installation
   *
   * @return NULL
   */
  public static function createDefaultRules() {
    // Create default Campaign Status Rules
    // cannot use APIv4, Entity not available on ext install
    $sqlInsert = "
    INSERT INTO `civicrm_campaign_status_rule` (
      `status_id`,
      `start_event`,
      `start_event_adjust_unit`,
      `start_event_adjust_interval`,
      `end_event`,
      `end_event_adjust_unit`,
      `end_event_adjust_interval`,
      `weight`,
      `is_default`,
      `is_active`
    )";

    $optionValues = \Civi\Api4\OptionValue::get()
      ->addSelect('id', 'value', 'label', 'name')
      ->addWhere('option_group_id:name', '=', 'campaign_status')
      ->execute();
    foreach ($optionValues as $optionValue) {
      switch ($optionValue['name']) {
        case 'Planned':
          $sql = $sqlInsert . " VALUES (" . $optionValue['value'] . ", NULL, NULL, NULL, 'start_date', 'day', 0, 1, 0, 1)";
          CRM_Core_DAO::executeQuery($sql, []);
          break;

        case 'In Progress':
          $sql = $sqlInsert . " VALUES (" . $optionValue['value'] . ", 'start_date', 'day', 0, 'end_date', 'day', 1, 2, 1, 1)";
          CRM_Core_DAO::executeQuery($sql, []);
          break;

        case 'Completed':
          $sql = $sqlInsert . " VALUES (" . $optionValue['value'] . ", 'end_date', 'day', 1, NULL, NULL, NULL, 3, 0, 1)";
          CRM_Core_DAO::executeQuery($sql, []);
          break;

        case 'Cancelled':
          // code...
          break;

        default:
          // code...
          break;
      }
    }
  }

}
