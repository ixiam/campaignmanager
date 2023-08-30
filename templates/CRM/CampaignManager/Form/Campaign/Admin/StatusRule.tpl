{* this template is used for adding/editing/deleting campaign status  *}
{crmScope extensionKey='campaignmanager'}
<div class="crm-block crm-form-block crm-campaign-status-form-block" id=campaign_status>
<fieldset><legend>{if $action eq 1}{ts}New Campaign Status Rule{/ts}{elseif $action eq 2}{ts}Edit Campaign Status Rule{/ts}{else}{ts}Delete Campaign Status Rule{/ts}{/if}</legend>
 <div class="crm-submit-buttons">{include file="CRM/common/formButtons.tpl" location="top"}</div>
  {if $action eq 8}
    <div class="messages status no-popup">
      {icon icon="fa-info-circle"}{/icon}
      {$deleteMessage|escape}
    </div>
  {else}
    <table class="form-layout-compressed">
      <tr class="crm-campaign-status-form-block-status_id">
        <td class="label">{$form.status_id.label}</td>
        <td class="html-adjust">{$form.status_id.html}</td>
      </tr>
      <tr class="crm-campaign-status-form-block-start_event">
        <td class="label">{$form.start_event.label}</td>
        <td class="html-adjust">{$form.start_event.html}<br />
           <span class="description">{ts}When does this status begin?{/ts}</span>
        </td>
      </tr>
      <tr class="crm-campaign-status-form-block-start_event_unit_interval">
        <td class="label">{$form.start_event_adjust_unit.label}</td>
        <td class="html-adjust">&nbsp;{$form.start_event_adjust_interval.html}&nbsp;&nbsp;{$form.start_event_adjust_unit.html}<br />
           <span class="description">{ts}Optional adjustment period added or subtracted from the Start Event.{/ts}</span>
        </td>
      </tr>
      <tr class="crm-campaign-status-form-block-end_event">
        <td class="label">{$form.end_event.label}</td>
        <td class="html-adjust">{$form.end_event.html}<br />
           <span class="description">{ts}When does this status end?{/ts}</span>
        </td>
      </tr>
      <tr class="crm-campaign-status-form-block-end_event_unit_interval">
        <td class="label">{$form.end_event_adjust_unit.label}</td>
        <td class="html-adjust">&nbsp;{$form.end_event_adjust_interval.html}&nbsp;{$form.end_event_adjust_unit.html}<br />
           <span class="description">{ts}Optional adjustment period added or subtracted from the End Event.{/ts}</span>
        </td>
      </tr>
      <tr class="crm-campaign-status-form-block-weight">
        <td class="label">{$form.weight.label}</td>
        <td class="html-adjust">&nbsp;{$form.weight.html}<br />
           <span class="description">{ts}Weight sets the order of precedence for automatic assignment of status to a campaign. It also sets the order for status displays.{/ts}</span>
        </td>
      </tr>
      <tr class="crm-campaign-status-form-block-is_default">
        <td class="label">{$form.is_default.label}</td>
        <td class="html-adjust">{$form.is_default.html}<br />
           <span class="description">{ts}The default status is assigned when there are no matching status rules for a campaign.{/ts}</span>
        </td>
      </tr>
      <tr class="crm-campaign-status-form-block-is_active">
        <td class="label">{$form.is_active.label}</td>
        <td class="html-adjust">{$form.is_active.html}<br />
           <span class="description">{ts}Is this status enabled.{/ts}</span>
        </td>
      </tr>
    </table>
    {/if}
  <div class="crm-submit-buttons">{include file="CRM/common/formButtons.tpl" location="bottom"}</div>
  <br clear="all" />
</fieldset>
</div>
{/crmScope}
