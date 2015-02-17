<div class="crm-section">
  <div class="label">{$form.contact_source.label}</div>
  <div class="content">{$form.contact_source.html}</div>
  <div id="clearSource" class="clear"></div>
</div>

<div class="crm-section">
  <div class="label">{$form.source_dates_from.label}</div>
  {if $action eq 1}
    <div class="content">{include file="CRM/common/jcalendar.tpl" elementName=source_date_from}</div>
  {else}
    <div class="content">{$form.source_dates_from.value|crmDate}</div>
  {/if}
  <div id="clearSourceDateFrom" class="clear"></div>
</div>
<div class="crm-section">
  <div class="label">{$form.source_dates_to.label}</div>
  {if $action eq 1}
    <div class="content">{include file="CRM/common/jcalendar.tpl" elementName=source_date_to}</div>
  {else}
    <div class="content">{$form.source_dates_to.value|crmDate}</div>
  {/if}
  <div id="clearSourceDateTo" class="clear"></div>
</div>
