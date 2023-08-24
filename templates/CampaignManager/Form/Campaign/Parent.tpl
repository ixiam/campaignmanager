<div id="pid_label_src">{$form.parent_id.label}</div>
<div id="pid_html_src">{$form.parent_id.html}</div>

{literal}
<script type="text/javascript">
CRM.$(function($) {
  var target = CRM.$('tr.crm-campaign-form-block-is_active').parent();

  target.prepend(CRM.$('<tr>')
    .append(CRM.$('<td>').attr('id', 'pid_label').addClass('label'))
    .append(CRM.$('<td>').attr('id', 'pid_html').addClass('view-value'))
  );

  CRM.$('#pid_label_src').appendTo('#pid_label');
  CRM.$('#pid_html_src').appendTo('#pid_html');
});
</script>
{/literal}
