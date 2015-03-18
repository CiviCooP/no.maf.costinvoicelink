
<h3>{$contactFormHeader}</h3>

<div class="crm-block crm-form-block">
  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="top"}
  </div>
  <div class="crm-section">
    <div hidden="1" class="content">{$form.invoice_id.html}</div>
    <div class="clear"></div>
  </div>

  <div class="crm-section">
    <div class="label">{$form.contactSourceFilter.label}</div>
    <div class="content">{$form.contactSourceFilter.html}</div>
    <div class="clear"></div>
  </div>

  <div class="crm-section">
    <div class="label">{$form.sourceDateFrom.label}</div>
    <div class="content">{include file="CRM/common/jcalendar.tpl" elementName=sourceDateFrom}</div>
    <div class="clear"></div>
  </div>

  <div class="crm-section">
    <div class="label">{$form.sourceDateTo.label}</div>
    <div class="content">{include file="CRM/common/jcalendar.tpl" elementName=sourceDateTo}</div>
    <div class="clear"></div>
  </div>

  <div class="crm-submit-buttons">
  {include file="CRM/common/formButtons.tpl" location="bottom"}
  </div>
</div>
