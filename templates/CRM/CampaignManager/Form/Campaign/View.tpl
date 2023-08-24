{* View existing campaign record. *}
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
      <td>
        <strong>{$campaign.title}</a></strong>
      </td>
    </tr>
    <tr class="crm-event-campaignview-form-block-parent">
      <td class="label">{ts}Parent{/ts}</td>
      <td>
        {assign var=parentTitle value="parent_id.title"}
        {$campaign.$parentTitle}
      </td>
    </tr>
    <tr class="crm-event-campaignview-form-block-type">
      <td class="label">{ts}Type{/ts}</td>
      <td>
        {assign var=typeLabel value="campaign_type_id:label"}
        {$campaign.$typeLabel}
      </td>
    </tr>
    <tr class="crm-event-campaignview-form-block-description">
      <td class="label">{ts}Description{/ts}</td>
      <td>
        {$campaign.description}
      </td>
    </tr>
  </table>
</div>
