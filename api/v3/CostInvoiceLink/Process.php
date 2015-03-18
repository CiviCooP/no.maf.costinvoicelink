<?php
set_time_limit(0);
/**
 * CostInvoiceLink.Process API
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 */
function civicrm_api3_cost_invoice_link_process($params) {
  /*
   * process all cost invoices and store in returnValues
   */
  $returnValues = _processCostInvoices();
  return civicrm_api3_create_success($returnValues, $params, 'CostInvoiceLink', 'Process');
}

/**
 * Function to read all invoices and apply each to contact and/or activity based on data
 *
 * @return array $returnValues
 */
function _processCostInvoices() {
  $returnValues = array();
  $mafInvoices = CRM_Costinvoicelink_BAO_Invoice::getValues(array());
  foreach ($mafInvoices as $mafInvoiceId => $mafInvoice) {
    $returnValues[] = 'invoice '.$mafInvoice['external_id'];
    if (isset($mafInvoice['contact_source']) && !empty($mafInvoice['contact_source'])) {
      _applyInvoiceToContacts($mafInvoice);
    }
    if (isset($mafInvoice['activity_type_id']) && !empty($mafInvoice['activity_type_id'])) {
      _applyInvoiceToActivities($mafInvoice);
    }
  }
  return $returnValues;
}

/**
 * Function to apply an invoice to contacts that fit the selection criteria
 *
 * @param array $mafInvoice
 */
function _applyInvoiceToContacts($mafInvoice) {
  $extensionConfig = CRM_Costinvoicelink_Config::singleton();
  $query = 'SELECT entity_id FROM '.$extensionConfig->getSourceCustomGroupTable()
    .' WHERE '.$extensionConfig->getSourceSourceCustomFieldColumn().' = %1 AND '
    .$extensionConfig->getSourceDateCustomFieldColumn().' BETWEEN %2 AND %3';
  $queryParams = array(
    1 => array($mafInvoice['contact_source'], 'String'),
    2 => array(date('Ymd', strtotime($mafInvoice['contact_from_date'])), 'Date'),
    3 => array(date('Ymd', strtotime($mafInvoice['contact_to_date'])), 'Date'));

  $dao = CRM_Core_DAO::executeQuery($query, $queryParams);

  while ($dao->fetch()) {
    $contactParams = array(
      'invoice_id' => $mafInvoice['id'],
      'entity' => 'Contact',
      'entity_id' => $dao->entity_id);
    if (CRM_Costinvoicelink_BAO_InvoiceEntity::invoiceEntityExists($contactParams) == FALSE) {
      $contactParams['linked_date'] = date('Ymd');
      CRM_Costinvoicelink_BAO_InvoiceEntity::add($contactParams);
    }
  }
}

/**
 * Function to apply an invoice to activities that fit the selection criteria
 *
 * @param $mafInvoice
 */
function _applyInvoiceToActivities($mafInvoice) {
  /*
   * first retrieve all subjects for invoice
   */
  $activitySubjects = _getActivitySubjects($mafInvoice['id']);
  $query = 'SELECT id, subject FROM civicrm_activity WHERE is_current_revision = %1 AND
    activity_type_id = %2 AND activity_date_time BETWEEN %3 AND %4';
  $queryParams = array(
    1 => array(1, 'Integer'),
    2 => array($mafInvoice['activity_type_id'], 'Integer'),
    3 => array(date('Ymd', strtotime($mafInvoice['activity_from_date'])), 'Date'),
    4 => array(date('Ymd', strtotime($mafInvoice['activity_to_date'])), 'Date'));
  $dao = CRM_Core_DAO::executeQuery($query, $queryParams);
  while ($dao->fetch()) {
    if (in_array($dao->subject, $activitySubjects)) {
      $activityParams = array(
        'invoice_id' => $mafInvoice['id'],
        'entity' => 'Activity',
        'entity_id' => $dao->id);
      if (CRM_Costinvoicelink_BAO_InvoiceEntity::invoiceEntityExists($activityParams) == FALSE) {
        $activityParams['linked_date'] = date('Ymd');
        CRM_Costinvoicelink_BAO_InvoiceEntity::add($activityParams);
      }
    }
  }
}

/**
 * Function to get activity subjects for an invoice
 *
 * @param int $mafInvoiceId
 * @return array $subjects
 */
function _getActivitySubjects($mafInvoiceId) {
  $subjects = array();
  $query = 'SELECT activity_subject FROM civicrm_maf_invoice_activity_subject
    WHERE invoice_id = %1';
  $params = array(1 => array($mafInvoiceId, 'Integer'));
  $dao = CRM_Core_DAO::executeQuery($query, $params);
  while ($dao->fetch()) {
    $subjects[] = $dao->activity_subject;
  }
  return $subjects;
}