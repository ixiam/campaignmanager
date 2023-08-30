<?php

// phpcs:disable
use CRM_CampaignManager_ExtensionUtil as E;
// phpcs:enable

return [
  [
    'name' => 'SavedSearch_Campaign_List',
    'entity' => 'SavedSearch',
    'cleanup' => 'always',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'Campaign_List',
        'label' => E::ts('Campaign List'),
        'form_values' => NULL,
        'mapping_id' => NULL,
        'search_custom_id' => NULL,
        'api_entity' => 'Campaign',
        'api_params' => [
          'version' => 4,
          'select' => [
            'id',
            'title',
            'parent_id.title',
            'description',
            'start_date',
            'end_date',
            'campaign_type_id:label',
            'status_id:label',
            'is_active',
            'Campaign_CampaignStatusOverride_campaign_id_01.is_override',
          ],
          'orderBy' => [],
          'where' => [],
          'groupBy' => [],
          'join' => [
            [
              'CampaignStatusOverride AS Campaign_CampaignStatusOverride_campaign_id_01',
              'LEFT',
              [
                'id',
                '=',
                'Campaign_CampaignStatusOverride_campaign_id_01.campaign_id',
              ],
            ],
          ],
          'having' => [],
        ],
        'expires_date' => NULL,
        'description' => NULL,
      ],
      'match' => [
        'name',
      ],
    ],
  ],
  [
    'name' => 'SavedSearch_Campaign_List_SearchDisplay_Campaign_List_Table_1',
    'entity' => 'SearchDisplay',
    'cleanup' => 'always',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'Campaign_List_Table_1',
        'label' => E::ts('Campaign List Table 1'),
        'saved_search_id.name' => 'Campaign_List',
        'type' => 'table',
        'settings' => [
          'description' => NULL,
          'sort' => [],
          'limit' => 50,
          'pager' => [
            'show_count' => TRUE,
          ],
          'placeholder' => 5,
          'columns' => [
            [
              'type' => 'field',
              'key' => 'id',
              'dataType' => 'Integer',
              'label' => E::ts('ID'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'title',
              'dataType' => 'String',
              'label' => E::ts('Title'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'parent_id.title',
              'dataType' => 'String',
              'label' => E::ts('Parent Campaign'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'description',
              'dataType' => 'Text',
              'label' => E::ts('Description'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'start_date',
              'dataType' => 'Timestamp',
              'label' => E::ts('Start Date'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'end_date',
              'dataType' => 'Timestamp',
              'label' => E::ts('End Date'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'campaign_type_id:label',
              'dataType' => 'Integer',
              'label' => E::ts('Type'),
              'sortable' => TRUE,
            ],
            [
              'type' => 'field',
              'key' => 'status_id:label',
              'dataType' => 'Integer',
              'label' => E::ts('Status'),
              'sortable' => TRUE,
              'icons' => [
                [
                  'icon' => 'fa-lock',
                  'side' => 'left',
                  'if' => [
                    'Campaign_CampaignStatusOverride_campaign_id_01.is_override',
                    '=',
                    TRUE,
                  ],
                ],
              ],
            ],
            [
              'type' => 'field',
              'key' => 'is_active',
              'dataType' => 'Boolean',
              'label' => E::ts('Enabled'),
              'sortable' => TRUE,
            ],
            [
              'size' => 'btn-xs',
              'links' => [
                [
                  'entity' => '',
                  'action' => '',
                  'join' => '',
                  'target' => '',
                  'icon' => 'fa-external-link',
                  'text' => E::ts('View'),
                  'style' => 'default',
                  'path' => 'civicrm/campaign/view?id=[id]',
                  'condition' => [],
                ],
              ],
              'type' => 'buttons',
              'alignment' => 'text-right',
            ],
            [
              'text' => '',
              'style' => 'default',
              'size' => 'btn-xs',
              'icon' => 'fa-bars',
              'links' => [
                [
                  'entity' => 'Campaign',
                  'action' => 'update',
                  'join' => '',
                  'target' => 'crm-popup',
                  'icon' => 'fa-pencil',
                  'text' => E::ts('Edit Campaign'),
                  'style' => 'default',
                  'path' => '',
                  'condition' => [],
                ],
                [
                  'entity' => 'Campaign',
                  'action' => 'delete',
                  'join' => '',
                  'target' => 'crm-popup',
                  'icon' => 'fa-trash',
                  'text' => E::ts('Delete Campaign'),
                  'style' => 'danger',
                  'path' => '',
                  'condition' => [],
                ],
              ],
              'type' => 'menu',
              'alignment' => 'text-right',
            ],
          ],
          'actions' => TRUE,
          'classes' => [
            'table',
            'table-striped',
          ],
          'headerCount' => TRUE,
          'addButton' => [
            'path' => 'civicrm/campaign/add?reset=1&action=add',
            'text' => E::ts('Add Campaign'),
            'icon' => 'fa-plus',
          ],
        ],
        'acl_bypass' => FALSE,
      ],
      'match' => [
        'name',
        'saved_search_id',
      ],
    ],
  ],
];
