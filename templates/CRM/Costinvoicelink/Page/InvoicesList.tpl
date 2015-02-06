<div class="crm-content-block crm-block">
  <div id="help">{$helpTxt}</div>
  <div class="action-link">
    <a class="button new-option" href="{$addUrl}">
      <span><div class="icon add-icon"></div>{$addButtonLabel}</span>
    </a>
  </div>
  {include file='CRM/common/jsortable.tpl'}
  <div id="mafinvoice_wrapper" class="dataTables_wrapper">
    <table id="mafinvoice-table" class="display">
      <thead>
        <tr>
          <th>{$invoiceIdentifierLabel}</th>
          <th>&nbsp;</th>
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
