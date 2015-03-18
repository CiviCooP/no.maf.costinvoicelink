<h3>{$activityFormHeader}</h3>

{* filter activities *}
<div class="crm-section">
  <div hidden="1" class="content">{$form.invoice_id.html}</div>
  <div class="clear"></div>
</div>

{include file="CRM/Costinvoicelink/Form/ActivityFilter.tpl"}

{* list of selected activities *}
{include file="CRM/Costinvoicelink/Page/ActivityList.tpl"}

<div class="crm-submit-buttons">
  {include file="CRM/common/formButtons.tpl" location="bottom"}
</div>
