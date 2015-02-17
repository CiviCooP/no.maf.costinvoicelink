{include file='CRM/Costinvoicelink/Form/ActivityFilter.tpl'}
{include file='CRM/common/jsortable.tpl'}
<div id="mafactivity_wrapper" class="dataTables_wrapper">
  <table id="mafactivity-table" class="display">
    <thead>
      <tr>
        <th id="nosort">{ts}Select{/ts}</th>
        <th>{ts}Activity Type{/ts}</th>
        <th>{ts}Subject{/ts}</th>
        <th>{ts}Target(s){/ts}</th>
        <th>{ts}Activity Date{/ts}</th>
      </tr>
    </thead>
    <tbody>
      {assign var="rowClass" value="odd-row"}
      {assign var="rowCount" value=0}
      {foreach from=$mafActivities key=activityId item=mafActivity}
        {assign var="rowCount" value=$rowCount+1}
        <tr id="row{$rowCount}" class={$rowClass}>
          <td><input type="checkbox" id="select{$rowCount}"></td>
          <td>
            <span>
              {foreach from=$mafInvoice.actions item=actionLink}
                {$actionLink}
              {/foreach}
            </span>
          </td>
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

