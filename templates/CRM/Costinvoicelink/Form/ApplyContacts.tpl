{* filter contacts *}
<div class="crm-section">
  <div hidden="1" class="content">{$form.invoice_id.html}</div>
  <div class="clear"></div>
</div>

{include file="CRM/Costinvoicelink/Form/ContactFilter.tpl"}

{* list of selected contacts *}
{include file="CRM/Costinvoicelink/Page/ContactList.tpl"}

<div class="crm-submit-buttons">
{include file="CRM/common/formButtons.tpl" location="bottom"}
</div>
