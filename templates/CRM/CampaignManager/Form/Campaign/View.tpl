{* View existing campaign record. *}
{crmScope extensionKey='campaignmanager'}

{literal}
<style type="text/css">
  .modal {
    display:    none;
    position:   fixed;
    z-index:    1000;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 )
                url('{/literal}{$config->resourceBase}i/loading.gif{literal}')
                50% 50%
                no-repeat;
  }
  body.loading {
    overflow: hidden;
  }
  body.loading .modal {
    display: block;
  }
  .CRM_CampaignManager_Form_Campaign_View  .row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
  }
  .d-none {display: none }
  .CRM_CampaignManager_Form_Campaign_View .col-lg-7,
  .CRM_CampaignManager_Form_Campaign_View .col-lg-5 {
    position: relative;
    width: 100%;
    padding-right: 15px;
    padding-left: 15px;
  }

  .kpi-wrap > div {
    margin-bottom: 1em;
  }

  @media (min-width: 992px) {
    .CRM_CampaignManager_Form_Campaign_View  .col-lg-7{
      flex: 0 0 58.33333%;
      max-width: 58.33333%
    }
    .CRM_CampaignManager_Form_Campaign_View  .col-lg-5 {
      flex: 0 0 41.66667%;
      max-width: 41.66667%;
    }
    .kpi-wrap > div {
      max-height: 457px;
      overflow-y: scroll;
    }

  }
  @media (min-width: 1248px) {
    .kpi-wrap > div {
      max-height: 438px;
      overflow-y: scroll;
    }
  }

  .CRM_CampaignManager_Form_Campaign_View .kpi-table tbody tr:nth-child(2n) td{
    background-color: #f9f9f9;
  }
  .CRM_CampaignManager_Form_Campaign_View .kpi-table tbody tr td{
    background-color: #ffffff;
  }
  .sticky{
    position: relative;
  }
  .sticky tr {
    position: sticky;top: 0;
    z-index: 1;
  }
  .CRM_CampaignManager_Form_Campaign_View h3 {
    margin-bottom: 1rem
  }
  .crm-container table.display thead th {
    border-color: var(--main-border-color);
  }
</style>
{/literal}


<div class="crm-block crm-content-block crm-event-campaign-view-form-block">
  <div class="action-link">
    <div class="crm-submit-buttons">
     {assign var='urlParams' value="reset=1&id=`$campaign.id`&action=update"}
     <a class="button" href="{crmURL p='civicrm/campaign/add' q=$urlParams}" accesskey="e"><span><i class="crm-i fa-pencil" aria-hidden="true"></i> {ts}Edit{/ts}</span></a>
     {assign var='urlParams' value="reset=1&id=`$campaign.id`&action=delete"}
     <a class="button" href="{crmURL p='civicrm/campaign/add' q=$urlParams}"><span><i class="crm-i fa-trash" aria-hidden="true"></i> {ts}Delete{/ts}</span></a>
     {include file="CRM/common/formButtons.tpl" location="top"}
    </div>
  </div>
  <div class="row">
    <div class="col-lg-5">
     <h3>{ts}Campaign Details{/ts}</h3>
      <table class="crm-info-panel">
        <tr class="crm-event-campaignview-form-block-title">
          <td class="label">{ts}Title{/ts}</td>
          <td><strong>{$campaign.title}</strong></td>
        </tr>
        <tr class="crm-event-campaignview-form-block-id">
          <td class="label">{ts}ID{/ts}</td>
          <td>{$campaign.id}</td>
        </tr>
        <tr class="crm-event-campaignview-form-block-parent">
          <td class="label">{ts}Parent{/ts}</td>
          <td>
            {if !empty($campaign.parent_id)}
            {assign var='urlParams' value="id=`$campaign.parent_id`"}
            <a href="{crmURL p='civicrm/campaign/view' q=$urlParams}">
              {$campaign.parent_title}
            </a>
            {/if}
          </td>
        </tr>
        <tr class="crm-event-campaignview-form-block-type">
          <td class="label">{ts}Type{/ts}</td>
          <td>
            {$campaign.campaign_type_label}
          </td>
        </tr>
        <tr class="crm-event-campaignview-form-block-description">
          <td class="label">{ts}Description{/ts}</td>
          <td>{$campaign.description}</td>
        </tr>
        <tr class="crm-event-campaignview-form-block-external_identifier">
          <td class="label">{ts}External Identifier{/ts}</td>
          <td>{$campaign.external_identifier}</td>
        </tr>
        <tr class="crm-event-campaignview-form-block-start_date">
          <td class="label">{ts}Start Date{/ts}</td>
          <td>{$campaign.start_date|crmDate}</td>
        </tr>
        <tr class="crm-event-campaignview-form-block-end_date">
          <td class="label">{ts}End Date{/ts}</td>
          <td>{$campaign.end_date|crmDate}</td>
        </tr>
        <tr class="crm-event-campaignview-form-block-is_override">
          <td class="label">{ts}Status Override?{/ts}</td>
          <td>{$campaign.is_override}</td>
        </tr>
        <tr class="crm-event-campaignview-form-block-status_id">
          <td class="label">{ts}Status{/ts}</td>
          <td>{$campaign.status_label}</td>
        </tr>
        <tr class="crm-event-campaignview-form-block-goal_general">
          <td class="label">{ts}Goal General{/ts}</td>
          <td>{$campaign.goal_general}</td>
        </tr>
        <tr class="crm-event-campaignview-form-block-goal_revenue">
          <td class="label">{ts}Goal Revenue{/ts}</td>
          <td>{$campaign.goal_revenue}</td>
        </tr>
      </table>
    </div>
    <div class="col-lg-7 kpi-wrap">
      <h3>{ts}Key Performance Indicators{/ts}</h3>
      {* KPIS table *}
      <table class="row-highlight display kpi-table">
        <thead class="sticky">
          <tr class="columnheader">
            <th>{ts}KPI{/ts}</th>
            <th>{ts}Value{/ts}</th>
            {if !empty($campaign.is_parent)}
            <th>{ts}Parent Value{/ts}</th>
            {/if}
          </tr>
        </thead>
        <tbody>
          {foreach from=$kpis item=kpi}
            <tr>
              <td>{$kpi.title}</td>
              <td id='kpi-value-{$kpi.id}' style="text-align: right">
                {$kpi.value}&nbsp;
                <span style="margin-left: 1rem">
                  <a href="#" class="crm-hover-button" id="swap_target_assignee" data-toggle="tooltip" title="Sep 2nd 2023">
                   <i class="crm-i fa-question-circle-o" aria-hidden="true"></i>
                  </a>
                  <a href="" onclick="refreshKpiValue('{$kpi.id}', '{$campaign.id}')" title="{ts}Refresh{/ts}" class="crm-i fa-refresh crm-hover-button"> <span class="d-none"> {ts}Refresh{/ts}</span>
                   <!--<i class="crm-i fa-refresh" aria-hidden="true"></i>-->
                  </a>
                </span>
              </td>
              {if !empty($campaign.is_parent)}
              <td id='kpi-value_parent-{$kpi.id}' style="text-align: right">
                {$kpi.value_parent}&nbsp;
                <span style="margin-left: 1rem">
                  <a href="#" class="crm-hover-button" id="swap_target_assignee" title="Sep 2nd 2023">
                   <i class="crm-i fa-question-circle-o" aria-hidden="true"></i>
                  </a>
                  <a href="" onclick="refreshKpiValue('{$kpi.id}', '{$campaign.id}', true)" title="{ts}Refresh{/ts}" class="crm-i fa-refresh crm-hover-button"><span class="d-none"> {ts}Refresh{/ts}</span>
                   <!--<i class="crm-i fa-refresh" aria-hidden="true"></i>-->
                  </a>
                </span>
              </td>
              {/if}
            </tr>
          {/foreach}
        </tbody>
      </table>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-5">
      {include file="CRM/Custom/Page/CustomDataView.tpl"}
    </div>
  </div>

</div>

<div class="modal"><!-- Place at bottom of page --></div>

{literal}
<script type="text/javascript">
(function($) {

  CRM.$(document).bind("ajaxStart", function(){
    CRM.$('body').addClass("loading");
  }).bind("ajaxStop", function(){
    CRM.$('body').removeClass("loading");
  });
})(CRM.$);

function refreshKpiValue(kpiId, campaignId, parentValue = false){
  CRM.api4('CampaignKPI', 'calculate', {
    'kpiId': kpiId,
    'campaignId': campaignId,
    'parentValue': parentValue
  }).then(function(results) {
    if(parentValue){
      CRM.$('td[id="kpi-value_parent-' + kpiId + '"]').html(results[0].value);
    }
    else{
      CRM.$('td[id="kpi-value-' + kpiId + '"]').html(results[0].value);
    }
  }, function(failure) {
    console.error(failure);
    CRM.alert('There\'s been an error calculating this KPI.', 'error', 'error');
  });
}
</script>
{/literal}

{/crmScope}
