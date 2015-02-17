<div class="crm-activity-selector-activity">
  <div class="crm-accordion-wrapper crm-search_filters-accordion">
    <div class="crm-accordion-header">{$activityFilterLabel}</div>
    <div class="crm-accordion-body">
      <div id="searchOptions" class="border form-layout-compressed">
        <div class="crm-contact-form-block-activity_range_filter crm-inline-edit-field">
          <label>{$form.activitiesTypesFilter.label}</label>
          {$form.activitiesTypesFilter.html}
          &nbsp;&nbsp;&nbsp;
          <label>{$activitiesDateFromLabel}</label>
          {include file="CRM/common/jcalendar.tpl" elementName=activity_date_from}
          &nbsp;&nbsp;&nbsp;
          <label>{$activitiesDateToLabel}</label>
          {include file="CRM/common/jcalendar.tpl" elementName=activity_date_to}
        </div>
      </div>
    </div>
  </div>
</div>
<div class="crm-submit-buttons">
  <span class="crm-button">
    <input id="_qf_Invoice_submit-bottom" class="validate form-submit default" type="submit" value="Search" name="_qf_Invoice_submit2">
  </span>
</div>
