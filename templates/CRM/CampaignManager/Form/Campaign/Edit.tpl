<div id="pid_label_src">{$form.parent_id.label}</div>
<div id="pid_html_src">{$form.parent_id.html}</div>

<div id="is_override_label_src">{$form.is_override.label}</div>
<div id="is_override_html_src">{$form.is_override.html}</div>

{literal}
<script type="text/javascript">
CRM.$(function($) {
  var target = CRM.$('tr.crm-campaign-form-block-is_active').parent();
  target.prepend('<tr class="crm-campaign-form-block-parentId"><td id="pid_label" class="label"></td><td id="pid_html" class="view-value"></td></tr>');

  target = CRM.$('tr.crm-campaign-form-block-status_id');
  target.before('<tr class="crm-campaign-form-block-override"><td id="is_override_label" class="label"></td><td id="is_override_html" class="view-value"></td></tr>');

  CRM.$('#pid_label_src').appendTo('#pid_label');
  CRM.$('#pid_html_src').appendTo('#pid_html');
  CRM.$('#is_override_label_src').appendTo('#is_override_label');
  CRM.$('#is_override_html_src').appendTo('#is_override_html');

  CRM.$("#is_override").change(function() {
    showHideCampaignStatus();
  });
  showHideCampaignStatus();

  function showHideCampaignStatus() {
    var isOverride = CRM.$("#is_override" ).prop("checked");
    var elStatusId = CRM.$('tr.crm-campaign-form-block-status_id');

    if(isOverride){
      CRM.$(elStatusId).show();
    }
    else{
      CRM.$(elStatusId).hide();
    }
  }
});
</script>
{/literal}
