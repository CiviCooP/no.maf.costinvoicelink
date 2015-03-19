<?php
set_time_limit(0);
/**
 * ContactSource.Migrate API
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 */
function civicrm_api3_contact_source_migrate($params) {
  $oldCustomGroupId = 1577;
  $newCustomGroupId = 1597;

  $oldSourceCustomFieldId = 83;
  $oldDateCustomFieldId = 31;
  $oldNoteCustomFieldId = 88;
  $oldMotivationCustomFieldId = 100;

  $newSourceCustomFieldId = 134;
  $newDateCustomFieldId = 135;
  $newNoteCustomFieldId = 137;
  $newMotivationCustomFieldId = 136;

  $oldCustomGroupParams = array('id' => $oldCustomGroupId, 'return' => 'table_name');
  $oldCustomGroupTable = civicrm_api3('CustomGroup', 'Getvalue', $oldCustomGroupParams);

  $newCustomGroupParams = array('id' => $newCustomGroupId, 'return' => 'table_name');
  $newCustomGroupTable = civicrm_api3('CustomGroup', 'Getvalue', $newCustomGroupParams);

  $oldSourceParams = array('id' => $oldSourceCustomFieldId, 'return' => 'column_name');
  $oldSourceColumn = civicrm_api3('CustomField', 'Getvalue', $oldSourceParams);
  $oldDateParams = array('id' => $oldDateCustomFieldId, 'return' => 'column_name');
  $oldDateColumn = civicrm_api3('CustomField', 'Getvalue', $oldDateParams);
  $oldNoteParams = array('id' => $oldNoteCustomFieldId, 'return' => 'column_name');
  $oldNoteColumn = civicrm_api3('CustomField', 'Getvalue', $oldNoteParams);
  $oldMotivationParams = array('id' => $oldMotivationCustomFieldId, 'return' => 'column_name');
  $oldMotivationColumn = civicrm_api3('CustomField', 'Getvalue', $oldMotivationParams);


  $newSourceParams = array('id' => $newSourceCustomFieldId, 'return' => 'column_name');
  $newSourceColumn = civicrm_api3('CustomField', 'Getvalue', $newSourceParams);
  $newDateParams = array('id' => $newDateCustomFieldId, 'return' => 'column_name');
  $newDateColumn = civicrm_api3('CustomField', 'Getvalue', $newDateParams);
  $newNoteParams = array('id' => $newNoteCustomFieldId, 'return' => 'column_name');
  $newNoteColumn = civicrm_api3('CustomField', 'Getvalue', $newNoteParams);
  $newMotivationParams = array('id' => $newMotivationCustomFieldId, 'return' => 'column_name');
  $newMotivationColumn = civicrm_api3('CustomField', 'Getvalue', $newMotivationParams);

  $oldQuery = 'SELECT * FROM '.$oldCustomGroupTable;
  $oldDao = CRM_Core_DAO::executeQuery($oldQuery);
  while ($oldDao->fetch()) {
    if (!empty($oldDao->$oldSourceColumn) || !empty($oldDao->$oldDateColumn) || !empty($oldDao->$oldNoteColumn) || !empty($oldDao->$oldMotivationColumn)) {
      $replaceClauses = array();
      $replaceParams = array();
      $count = 1;
      $replaceParams[1] = array($oldDao->entity_id, 'Integer');

      if (!empty($oldDao->$oldSourceColumn)) {
        $count++;
        $replaceClauses[] = $newSourceColumn.' = %'.$count;
        $replaceParams[$count] = array($oldDao->$oldSourceColumn, 'String');
      }

      if (!empty($oldDao->$oldDateColumn)) {
        $count++;
        $replaceClauses[] = $newDateColumn.' = %'.$count;
        $replaceParams[$count] = array(date('Ymd', strtotime($oldDao->$oldDateColumn)), 'Date');
      }

      if (!empty($oldDao->$oldNoteColumn)) {
        $count++;
        $replaceClauses[] = $newNoteColumn.' = %'.$count;
        $replaceParams[$count] = array($oldDao->$oldNoteColumn, 'String');
      }

      if (!empty($oldDao->$oldMotivationColumn)) {
        $count++;
        $replaceClauses[] = $newMotivationColumn.' = %'.$count;
        $replaceParams[$count] = array($oldDao->$oldMotivationColumn, 'String');
      }
      if (!empty($replaceClauses)) {
        $newQuery = 'REPLACE INTO ' . $newCustomGroupTable . ' SET entity_id = %1,' . implode(', ', $replaceClauses);
        CRM_Core_DAO::executeQuery($newQuery, $replaceParams);
      }
    }
  }
  $returnValues = array('Migration OK');
  return civicrm_api3_create_success($returnValues, $params, 'ContactSource', 'Migrate');
}
