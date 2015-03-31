<div class="crm-content-block crm-block">
  {include file='CRM/common/jsortable.tpl'}
  <div id="mafactivity_wrapper" class="dataTables_wrapper">
    <table id="mafactivity-table" class="display">
      <thead>
        <tr id="headerRow">
          <th id="nosort">&nbsp;<input type="checkbox" id="selectAllSubjects">&nbsp;(select all)</th>
          <th>{$actListSubjectLabel}</th>
          <th>{$actListTargetLabel}</th>
        </tr>
      </thead>
      <tbody>
        {assign var="rowClass" value="odd-row"}
        {assign var="rowCount" value=0}
        {foreach from=$mafSubjects key=rowId item=mafSubject}
          {assign var="rowCount" value=$rowCount+1}
          <tr id="row{$rowCount}" class={$rowClass}>
            <td><input type="checkbox" id="select{$rowCount}" name="selectedSubjects[]" value="{$mafSubject.subject}"></td>
            <td>{$mafSubject.subject}</td>
            <td>{$mafSubject.targets}</td>
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
{literal}
  <script type="text/javascript">
    cj('#selectAllSubjects').click(function() {
      if(this.checked) {
        cj(':checkbox').each(function() {
          this.checked = true;
        });
      } else {
        cj(':checkbox').each(function () {
          this.checked = false;
        });
      }
    });
  </script>
{/literal}
