<?php

/**
 * BAO Inovice for dealing with cost invoice records
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @license AGPL-3.0
 */
class CRM_Costinvoicelink_BAO_Invoice extends CRM_Costinvoicelink_DAO_Invoice {

  /**
   * Function to get values
   *
   * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
   * @return array $result found rows with data
   * @access public
   * @static
   */
  public static function getValues($params) {
    $result = array();
    $mafInvoice = new CRM_Costinvoicelink_BAO_Invoice();
    if (!empty($params)) {
      $fields = self::fields();
      foreach ($params as $key => $value) {
        if (isset($fields[$key])) {
          $mafInvoice->$key = $value;
        }
      }
    }
    $mafInvoice->find();
    while ($mafInvoice->fetch()) {
      $row = array();
      self::storeValues($mafInvoice, $row);
      $result[$row['id']] = $row;
    }
    return $result;
  }

  /**
   * Function to add or update cost invoice
   *
   * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
   * @param array $params
   * @return array $result
   * @throws Exception when params is empty
   * @access public
   * @static
   */
  public static function add($params) {
    $result = array();
    if (empty($params)) {
      throw new Exception('Params can not be empty when adding or updating a cost invoice record');
    }
    $mafInvoice = new CRM_Costinvoicelink_BAO_Invoice();
    $fields = self::fields();
    foreach ($params as $key => $value) {
      if (isset($fields[$key])) {
        $mafInvoice->$key = $value;
      }
    }
    $mafInvoice->save();
    self::storeValues($mafInvoice, $result);
    return $result;
  }

  /**
   * Function to delete a cost invoice record by id
   *
   * @param int $invoiceId
   * @throws Exception when $invoiceId is empty
   */
  public static function deleteById($invoiceId) {
    if (empty($invoiceId)) {
      throw new Exception('invoiceId can not be empty when attempting to delete a cost invoice');
    }
    $mafInvoice = new CRM_Costinvoicelink_BAO_Invoice();
    $mafInvoice->id = $invoiceId;
    $mafInvoice->delete();
    return;
  }
}