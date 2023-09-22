<?php

require_once 'campaignmanager.civix.php';
// phpcs:disable
use CRM_CampaignManager_ExtensionUtil as E;
use Civi\CampaignManager\Utils\ClassScanner as ClassScanner;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
// phpcs:enable

function campaignmanager_civicrm_pre($op, $objectName, $id, &$params) {
  if ($objectName == "Campaign" && in_array($op, ['create', 'edit'])) {
    // Calculate and edit campaign status
    $isOverride = CRM_Utils_Request::retrieveValue('is_override', 'Boolean', 0, FALSE, 'POST') ?? 0;
    if (!$isOverride) {
      $newStatusId = CRM_CampaignManager_BAO_CampaignStatusRule::getCampaignStatusByDate($params['start_date'], $params['end_date'], 'now');
      if (!empty($newStatusId)) {
        $params['status_id'] = $newStatusId;
      }
    }
  }

}

/**
 * Implementation of hook_civicrm_buildForm:
 */
function campaignmanager_civicrm_buildForm($formName, &$form) {
  if ($formName == 'CRM_Campaign_Form_Campaign') {
    $action = $form->getAction();
    if (!isset($_GET['qfKey'])) {
      $defaults = [];
      switch ($action) {
        case CRM_Core_Action::NONE:
        case CRM_Core_Action::ADD:
        case CRM_Core_Action::UPDATE:

          $cid = $form->getVar('_campaignId');
          $campaignAPI = \Civi\Api4\Campaign::get()->addSelect('id', 'title');
          if ($cid) {
            $campaignAPI->addWhere('id', '!=', $cid);
            $defaults = \Civi\Api4\CampaignStatusOverride::get()
              ->addSelect('is_override')
              ->addWhere('campaign_id', '=', $cid)
              ->execute()
              ->single() ?? 0;
          }
          $campaigns = $campaignAPI->execute()->indexBy('id')->column('title');

          $form->addElement('select', 'parent_id', E::ts('Parent ID'),
            ['' => E::ts('- select Parent -')] + $campaigns,
            ['class' => 'crm-select2']
          );
          $form->addElement('checkbox', 'is_override', ts('Status Override?'));
          $form->setDefaults($defaults);

          CRM_Core_Region::instance('form-body')->add([
            'template' => 'CRM/CampaignManager/Form/Campaign/Edit.tpl',
          ]);
          break;
      }
    }
  }
}

/**
 * Implements hook_civicrm_validateForm().
 *
 */
function campaignmanager_civicrm_validateForm($formName, &$fields, &$files, &$form, &$errors) {
  if ($formName == 'CRM_Campaign_Form_Campaign') {
    if (in_array($form->getAction(), [CRM_Core_Action::DELETE, CRM_Core_Action::VIEW])) {
      return;
    }

    $isOverride = strtoupper(CRM_Utils_Array::value('is_override', $fields));
    $statusId = strtoupper(CRM_Utils_Array::value('status_id', $fields));
    if ($isOverride && empty($statusId)) {
      $errors['is_override'] = E::ts('You have to select a Campaign Status if you checked "Status Override"');
    }
  }
  return;
}

/**
 * Implementation of hook_civicrm_postCommit:
 */
function campaignmanager_civicrm_postCommit($op, $objectName, $objectId, &$objectRef) {
  if ($objectName === 'Campaign') {
    // Save CampaignTree row on Campaign edition
    $depth = 1;

    $path = CRM_CampaignManager_BAO_CampaignTree::SEPARATOR;
    // not sure why empty parent_id arrives as string "null"
    if (!empty($objectRef->parent_id) && ($objectRef->parent_id != 'null')) {
      $parentTree = \Civi\Api4\CampaignTree::get()
        ->addSelect('path', 'depth')
        ->addWhere('campaign_id', '=', $objectRef->parent_id)
        ->execute()
        ->single();
      $depth = $parentTree['depth'] + 1;
      $path = $parentTree['path'] . $objectRef->parent_id . CRM_CampaignManager_BAO_CampaignTree::SEPARATOR;
    }

    switch ($op) {
      case 'create':
      case 'edit':
        $paramsTree[] = [
          'campaign_id' => $objectId,
          'path' => $path,
          'depth' => $depth,
        ];
        \Civi\Api4\CampaignTree::save()
          ->setRecords($paramsTree)
          ->setMatch(['campaign_id'])
          ->execute();

        // Save status is_override
        $isOverride = CRM_Utils_Request::retrieveValue('is_override', 'Boolean', 0, FALSE, 'POST') ?? 0;
        $paramsOverride[] = [
          'is_override' => $isOverride,
          'campaign_id' => $objectId,
        ];
        \Civi\Api4\CampaignStatusOverride::save()
          ->setRecords($paramsOverride)
          ->addDefault('is_override', FALSE)
          ->setMatch(['campaign_id'])
          ->execute();
        break;

      case 'delete':
        // no need, cascade deletion
        break;

    }
  }
}

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function campaignmanager_civicrm_config(&$config): void {
  _campaignmanager_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function campaignmanager_civicrm_install(): void {
  _campaignmanager_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function campaignmanager_civicrm_enable(): void {
  _campaignmanager_civix_civicrm_enable();
}
