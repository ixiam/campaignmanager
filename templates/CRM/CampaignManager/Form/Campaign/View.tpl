{* View existing campaign record. *}
{crmScope extensionKey='campaignmanager'}
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
</div>
{/crmScope}
