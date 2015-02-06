<div class="crm-content-block crm-block">
  {include file='CRM/common/jsortable.tpl'}
  <h2>Filtered activities:</h2>
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
        {foreach from=$mafInvoices key=mafInvoiceId item=mafInvoice}
          {assign var="rowCount" value=$rowCount+1}
          <tr id="row{$rowCount}" class={$rowClass}>
            <td>{$mafInvoice.external_id}</td>
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
  <div class="action-link">
    <a class="button new-option" href="{$addUrl}">
      <span><div class="icon add-icon"></div>{ts}New Cost Invoice{/ts}</span>
    </a>
  </div>
</div>
