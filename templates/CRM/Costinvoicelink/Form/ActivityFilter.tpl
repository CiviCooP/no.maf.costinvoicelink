<div class="crm-activity-selector-contact">
  <div class="crm-accordion-wrapper crm-search_filters-accordion">
    <div class="crm-accordion-header">{$activityFilterLabel}</div>
    <div class="crm-accordion-body">
      <div id="searchOptions" class="border form-layout-compressed">
        <div class="crm-contact-form-block-contact_range_filter crm-inline-edit-field">
          <label>{$form.activityTypeFilter.label}</label>
          {$form.activityTypeFilter.html}
          &nbsp;&nbsp;&nbsp;
          <label>{$activityDateDateFromLabel}</label>
          {include file="CRM/common/jcalendar.tpl" elementName=activityDateFrom}
          &nbsp;&nbsp;&nbsp;
          <label>{$activityDateToLabel}</label>
          {include file="CRM/common/jcalendar.tpl" elementName=activityDateTo}
        </div>
      </div>
      <input id="_qf_Search_refresh" class="form-submit" type="submit" value="{$activitySearchButtonLabel}" name="_qf_ApplyActivities_submit">
    </div>
  </div>
</div>
