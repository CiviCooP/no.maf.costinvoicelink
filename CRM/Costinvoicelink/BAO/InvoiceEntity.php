<?php

/**
 * BAO InvoiceEntity for dealing with cost invoice entity
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @license AGPL-3.0
 */
class CRM_Costinvoicelink_BAO_InvoiceEntity extends CRM_Costinvoicelink_DAO_InvoiceEntity {

  /**
   * Function to get values
   *
   * @return array $result found rows with data
   * @access public
   * @static
   */
  public static function getValues($params) {
    $result = array();
    $mafInvoiceEntity = new CRM_Costinvoicelink_BAO_InvoiceEntity();
    if (!empty($params)) {
      $fields = self::fields();
      foreach ($params as $key => $value) {
        if (isset($fields[$key])) {
          $mafInvoiceEntity->$key = $value;
        }
      }
    }
    $mafInvoiceEntity->find();
    while ($mafInvoiceEntity->fetch()) {
      $row = array();
      self::storeValues($mafInvoiceEntity, $row);
      $result[$row['id']] = $row;
    }
    return $result;
  }

  /**
   * Function to add or update cost invoice entity
   *
   * @param array $params
   * @return array $result
   * @throws Exception when params is empty
   * @access public
   * @static
   */
  public static function add($params) {
    $result = array();
    if (empty($params)) {
      throw new Exception('Params can not be empty when adding or updating a cost invoice entity record');
    }
    $mafInvoiceEntity = new CRM_Costinvoicelink_BAO_InvoiceEntity();
    $fields = self::fields();
    foreach ($params as $key => $value) {
      if (isset($fields[$key])) {
        $mafInvoiceEntity->$key = $value;
      }
    }
    $mafInvoiceEntity->save();
    self::storeValues($mafInvoiceEntity, $result);
    return $result;
  }

  /**
   * Function to delete a cost invoice entity record by invoiceId
   *
   * @param int $invoiceId
   * @throws Exception when $invoiceId is empty
   */
  public static function deleteByInvoiceId($invoiceId) {
    if (empty($invoiceId)) {
      throw new Exception('invoiceId can not be empty when attempting to delete a cost invoice entity');
    }
    $mafInvoiceEntity = new CRM_Costinvoicelink_BAO_InvoiceEntity();
    $mafInvoiceEntity->invoice_id = $invoiceId;
    $mafInvoiceEntity->delete();
    return;
  }

  /**
   * Method to check if invoice and entity are already linked
   *
   * @param array $values
   * @return bool
   * @access public
   * @static
   */
  public static function invoiceEntityExists($values) {
    $invoiceEntity = new CRM_Costinvoicelink_BAO_InvoiceEntity();
    if (isset($values['invoice_id'])) {
      $invoiceEntity->invoice_id = $values['invoice_id'];
    }
    if (isset($values['entity'])) {
      $invoiceEntity->entity = $values['entity'];
    }
    if (isset($values['entity_id'])) {
      $invoiceEntity->entity_id = $values['entity_id'];
    }
    if ($invoiceEntity->count() > 0) {
      return TRUE;
    } else {
      return FALSE;
    }
  }
}