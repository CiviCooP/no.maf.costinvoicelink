<?php
/**
 * DAO Invoice for cost invoice details
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @license AGPL-3.0
 */
class CRM_Costinvoicelink_DAO_Invoice extends CRM_Core_DAO {
  /**
   * static instance to hold the field values
   *
   * @var array
   * @static
   */
  static $_fields = null;
  static $_export = null;
  static $_fieldKeys = null;
  /**
   * empty definition for virtual function
   */
  static function getTableName() {
    return 'civicrm_maf_invoice';
  }
  /**
   * returns all the column names of this table
   *
   * @access public
   * @return array
   */
  static function &fields() {
    if (!(self::$_fields)) {
      self::$_fields = array(
        'id' => array(
          'name' => 'id',
          'type' => CRM_Utils_Type::T_INT,
          'required' => true
        ),
        'external_id' => array(
          'name' => 'external_id',
          'type' => CRM_Utils_Type::T_STRING,
          'maxlength' => 45,
        ),
        'contact_source' => array(
          'name' => 'contact_source',
          'type' => CRM_Utils_Type::T_STRING,
          'maxlength' => 128,
        ),
        'contact_from_date' => array(
          'name' => 'contact_from_date',
          'type' => CRM_Utils_Type::T_DATE,
        ),
        'contact_to_date' => array(
          'name' => 'contact_to_date',
          'type' => CRM_Utils_Type::T_DATE,
        ),
        'activity_type_id' => array(
          'name' => 'activity_type_id',
          'type' => CRM_Utils_Type::T_INT,
        ),
        'activity_from_date' => array(
          'name' => 'activity_from_date',
          'type' => CRM_Utils_Type::T_DATE,
        ),
        'activity_to_date' => array(
          'name' => 'activity_to_date',
          'type' => CRM_Utils_Type::T_DATE,
        ),
      );
    }
    return self::$_fields;
  }
  /**
   * Returns an array containing, for each field, the array key used for that
   * field in self::$_fields.
   *
   * @access public
   * @return array
   */
  static function &fieldKeys() {
    if (!(self::$_fieldKeys)) {
      self::$_fieldKeys = array(
        'id' => 'id',
        'external_id' => 'external_id',
        'contact_source' => 'contact_source',
        'contact_from_date' => 'contact_from_date',
        'contact_to_date' => 'contact_to_date',
        'activity_type_id' => 'activity_type_id',
        'activity_from_date' => 'activity_from_date',
        'activity_to_date' => 'activity_to_date'
      );
    }
    return self::$_fieldKeys;
  }
  /**
   * returns the list of fields that can be exported
   *
   * @access public
   * return array
   * @static
   */
  static function &export($prefix = false)
  {
    if (!(self::$_export)) {
      self::$_export = array();
      $fields = self::fields();
      foreach($fields as $name => $field) {
        if (CRM_Utils_Array::value('export', $field)) {
          if ($prefix) {
            self::$_export['activity'] = & $fields[$name];
          } else {
            self::$_export[$name] = & $fields[$name];
          }
        }
      }
    }
    return self::$_export;
  }
}