<?php

require_once 'campaignmanager.civix.php';
// phpcs:disable
use CRM_CampaignManager_ExtensionUtil as E;
// phpcs:enable

/**
 * Implementation of hook_civicrm_buildForm:
 */
function campaignmanager_civicrm_buildForm($formName, &$form) {
  if ($formName == 'CRM_Campaign_Form_Campaign') {
    $action = $form->getAction();
    if ($action == CRM_Core_Action::NONE && !isset($_GET['qfKey'])) {
      if (isset($_GET['pid'])) {
        $select = $form->getElement('parent_id');
        $select->setSelected($_GET['pid']);
      }
      CRM_Core_Region::instance('form-body')->add(array(
        'template' => 'CRM/CampaignManager/Form/Campaign/Parent.tpl',
      ));
    }
    elseif (($action == CRM_Core_Action::UPDATE || $action == CRM_Core_Action::ADD) && !isset($_GET['qfKey'])) {
      $cid = $form->getVar('_campaignId');
      $campaigns = \Civi\Api4\Campaign::get()
        ->addSelect('id', 'title')
        ->addWhere('id', '!=', $cid)
        ->execute()
        ->indexBy('id')
        ->column('title');

      $form->addElement('select', 'parent_id', E::ts('Parent ID'),
        ['' => E::ts('- select Parent -')] + $campaigns,
        ['class' => 'crm-select2']
      );
      CRM_Core_Region::instance('form-body')->add([
        'template' => 'CRM/CampaignManager/Form/Campaign/Parent.tpl',
      ]);
    }
  }
}

/**
 * Implementation of hook_civicrm_postCommit:
 */
function campaignmanager_civicrm_postCommit($op, $objectName, $objectId, &$objectRef) {
  // Handles CampaignTree row on Campaign edition
  if ($objectName === 'Campaign') {
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
      $path = $parentTree['path'];
    }

    $path .= $objectId . CRM_CampaignManager_BAO_CampaignTree::SEPARATOR;

    switch ($op) {
      case 'create':
        $campaignTree = \Civi\Api4\CampaignTree::create()
          ->addValue('campaign_id', $objectId)
          ->addValue('path', $path)
          ->addValue('depth', $depth)
          ->execute();
        break;

      case 'edit':
        $results = \Civi\Api4\CampaignTree::update()
          ->addValue('path', $path)
          ->addValue('depth', $depth)
          ->addWhere('campaign_id', '=', $objectId)
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
