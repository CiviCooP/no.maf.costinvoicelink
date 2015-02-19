<div class="crm-activity-selector-contact">
  <div class="crm-accordion-wrapper crm-search_filters-accordion">
    <div class="crm-accordion-header">{$contactFilterLabel}</div>
    <div class="crm-accordion-body">
      <div id="searchOptions" class="border form-layout-compressed">
        <div class="crm-contact-form-block-contact_range_filter crm-inline-edit-field">
          <label>{$form.contactSourceFilter.label}</label>
          {$form.contactSourceFilter.html}
          &nbsp;&nbsp;&nbsp;
          <label>{$sourceDateFromLabel}</label>
          {include file="CRM/common/jcalendar.tpl" elementName=sourceDateFrom}
          &nbsp;&nbsp;&nbsp;
          <label>{$sourceDateToLabel}</label>
          {include file="CRM/common/jcalendar.tpl" elementName=sourceDateTo}
        </div>
      </div>
      <input id="_qf_Search_refresh" class="form-submit" type="submit" value="{$contactSearchButtonLabel}" name="_qf_ApplyContacts_submit">
    </div>
  </div>
</div>
