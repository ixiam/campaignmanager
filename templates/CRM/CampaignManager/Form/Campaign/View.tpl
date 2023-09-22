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

  {include file="CRM/Custom/Page/CustomDataView.tpl"}

  {* KPIS table *}
  <table class="row-highlight">
    <tr class="columnheader">
      <th>{ts}KPI{/ts}</th>
      <th>{ts}Value{/ts}</th>
      {if !empty($campaign.is_parent)}
      <th>{ts}Parent Value{/ts}</th>
      {/if}
    </tr>
    {foreach from=$kpis item=kpi}
      <tr>
        <td>{$kpi.title}</td>
        <td id='kpi-value-{$kpi.id}'>
          {$kpi.value}&nbsp;
          <a href="#" class="crm-hover-button" id="swap_target_assignee" title="Sep 2nd 2023" style="position:relative; bottom: 1em;">
           <i class="crm-i fa-question-circle-o" aria-hidden="true"></i>
          </a>
          <a onclick="refreshKpiValue('{$kpi.id}', '{$campaign.id}')" title="{ts}Refresh{/ts}" class="button">
           <i class="crm-i fa-refresh" aria-hidden="true"></i>
          </a>
        </td>
        {if !empty($campaign.is_parent)}
        <td id='kpi-value_parent-{$kpi.id}'>
          {$kpi.value_parent}&nbsp;
          <a href="#" class="crm-hover-button" id="swap_target_assignee" title="Sep 2nd 2023" style="position:relative; bottom: 1em;">
           <i class="crm-i fa-question-circle-o" aria-hidden="true"></i>
          </a>
          <a onclick="refreshKpiValue('{$kpi.id}', '{$campaign.id}', true)" title="{ts}Refresh{/ts}" class="button">
           <i class="crm-i fa-refresh" aria-hidden="true"></i>
          </a>
        </td>
        {/if}
      </tr>
    {/foreach}
  </table>
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
