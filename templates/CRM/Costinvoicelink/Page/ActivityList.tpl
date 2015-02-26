<div class="crm-content-block crm-block">
  {include file='CRM/common/jsortable.tpl'}
  <div id="mafactivity_wrapper" class="dataTables_wrapper">
    <table id="mafactivity-table" class="display">
      <thead>
        <tr>
          <th id="nosort">{$selectLabel}</th>
          <th>{$actListTypeLabel}</th>
          <th>{$actListSubjectLabel}</th>
          <th>{$actListTargetLabel}</th>
          <th>{$actListDateLabel}</th>
        </tr>
      </thead>
      <tbody>
        {assign var="rowClass" value="odd-row"}
        {assign var="rowCount" value=0}
        {foreach from=$mafActivities key=activityId item=mafActivity}
          {assign var="rowCount" value=$rowCount+1}
          <tr id="row{$rowCount}" class={$rowClass}>
            <td><input type="checkbox" id="select{$rowCount}" name="selectedActivities[]" value="{$activityId}"></td>
            <td>{$mafActivity.activity_type}</td>
            <td>{$mafActivity.subject}</a></td>
            <td>{$mafActivity.targets}</td>
            <td>{$mafActivity.activity_date|crmDate}</td>
          </tr>
          {if $rowClass eq "odd-row"}
            {assign var="rowClass" value="even-row"}
          {else}
            {assign var="rowClass" value="odd-row"}
          {/if}
        {/foreach}
      </tbody>
    </table>
  </div>
</div>

