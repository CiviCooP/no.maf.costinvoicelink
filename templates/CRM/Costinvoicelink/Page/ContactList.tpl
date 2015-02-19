<div class="crm-content-block crm-block">
  {include file='CRM/common/jsortable.tpl'}
  <div id="mafcontact_wrapper" class="dataTables_wrapper">
    <table id="mafcontact-table" class="display">
      <thead>
        <tr>
          <th id="nosort">{$selectLabel}</th>
          <th>{$contactDisplayNameLabel}</th>
          <th>{$contactContactTypeLabel}</th>
          <th>{$contactSourceLabel}</th>
          <th>{$contactSourceDateLabel}</th>
          <th>{$contactSourceMotivationLabel}</th>
        </tr>
      </thead>
      <tbody>
        {assign var="rowClass" value="odd-row"}
        {assign var="rowCount" value=0}
        {foreach from=$mafContacts key=contactId item=mafContact}
          {assign var="rowCount" value=$rowCount+1}
          <tr id="row{$rowCount}" class={$rowClass}>
            <td><input type="checkbox" id="select{$rowCount}" name="selectedContacts[]" value="{$contactId}"></td>
            <td><a href="{crmURL p="civicrm/contact/view" q="reset=1&cid=$contactId"}">{$mafContact.display_name}</a></td>
            <td>{$mafContact.contact_type}</td>
            <td>{$mafContact.source}</td>
            <td>{$mafContact.source_date|crmDate}</td>
            <td>{$mafContact.source_motivation}</td>
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

