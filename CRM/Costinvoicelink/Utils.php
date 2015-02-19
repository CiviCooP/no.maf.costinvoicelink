<?php
/**
 * Class with generic extension utils functions
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @license AGPL-3.0
 */

class CRM_Costinvoicelink_Utils {

  /**
   * Function to get custom group if exists
   * @param string $customGroupName
   * @return array
   * @access public
   * @static
   */
  static function getCustomGroup($customGroupName) {
    try {
      $customGroup = civicrm_api3('CustomGroup', 'Getsingle', array('name' => $customGroupName));
      return $customGroup;
    } catch (CiviCRM_API3_Exception $ex) {
      return array();
    }
  }

  /**
   * Function to get custom field if exists
   *
   * @param int $customGroupId
   * @param string $customFieldName
   * @return array
   * @access public
   * @static
   */
  static function getCustomField($customGroupId, $customFieldName) {
    $params = array(
      'custom_group_id' => $customGroupId,
      'name' => $customFieldName
    );
    try {
      $customField = civicrm_api3('CustomField', 'Getsingle', $params);
      return $customField;
    } catch (CiviCRM_API3_Exception $ex) {
      return array();
    }
  }

  /**
   * Function to create custom group
   *
   * @param string $customGroupName
   * @param string $customGroupTable
   * @param string $extends
   * @return int $customGroup['id']
   * @throws Exception when error from API CustomGroup Create
   * @access public
   * @static
   */
  static function createCustomGroup($customGroupName, $customGroupTable, $extends) {
    $params = array(
      'name' => $customGroupName,
      'title' => self::createLabelFromName($customGroupName),
      'extends' => $extends,
      'is_active' => 1,
      'is_reserved' => 1,
      'table_name' => $customGroupTable
    );
    try {
      $customGroup = civicrm_api3('CustomGroup', 'Create', $params);
    } catch (CiviCRM_API3_Exception $ex) {
      throw new Exception(ts('Could not create custom group '.$customGroupName
          .', error from API CustomGroup Create: ').$ex->getMessage());
    }
    foreach ($customGroup['values'] as $customGroupId => $returnGroup) {
      return $returnGroup;
    }
  }

  /**
   * Function to create custom field
   *
   * @param int $customGroupId
   * @param string $customFieldName
   * @param string $customFieldColumn
   * @param string $customFieldDataType
   * @param string $customFieldHtmlType
   * @param mixed $defaultValue
   * @param int $isSearchable
   * @param int $optionGroupId
   * @return int $customField['id']
   * @throws Exception when error from API CustomField Create
   * @access public
   * @static
   */
  static function createCustomField($customGroupId, $customFieldName, $customFieldColumn, $customFieldDataType, $customFieldHtmlType,
                                    $isSearchable, $optionGroupId = 0, $defaultValue = NULL) {

    $params = array(
      'custom_group_id' => $customGroupId,
      'name' => $customFieldName,
      'label' => self::createLabelFromName($customFieldName),
      'data_type' => $customFieldDataType,
      'html_type' => $customFieldHtmlType,
      'column_name' => $customFieldColumn,
      'is_active' => 1,
      'is_reserved' => 1,
      'is_searchable' => $isSearchable,
    );
    if (!empty($defaultValue)) {
      $params['default_value'] = $defaultValue;
    }
    if (!empty($optionGroupId)) {
      $params['option_group_id'] = $optionGroupId;
    }
    try {
      $customField = civicrm_api3('CustomField', 'Create', $params);
    } catch (CiviCRM_API3_Explorer $ex) {
      throw new Exception(ts('Could not create custom field '.$customFieldName
          .' in custom group id '.$customGroupId.', error from API CustomField Create: ').$ex->getMessage());
    }
    foreach ($customField['values'] as $customFieldId => $returnField) {
      return $returnField;
    }
  }

  /**
   * Function to get contact name
   *
   * @param int $contactId
   * @return string $contactName
   * @access public
   * @static
   */
  static function getContactName($contactId) {
    $params = array(
      'id' => $contactId,
      'return' => 'display_name');
    try {
      $contactName = civicrm_api3('Contact', 'Getvalue', $params);
    } catch (CiviCRM_API3_Exception $ex) {
      $contactName = '';
    }
    return $contactName;
  }

  /**
   * Function to create option group if not exists already
   *
   * @param string $optionGroupName
   * @return array $optionGroup
   * @throws Exception if option group can not be created
   * @access public
   * @static
   */
  public static function createOptionGroup($optionGroupName) {
    $optionGroup = self::getOptionGroup($optionGroupName);
    if (empty($optionGroup)) {
      $params = array(
        'name' => $optionGroupName,
        'title' => self::createLabelFromName($optionGroupName),
        'is_active' => 1,
        'is_reserved' => 1
      );
      try {
        $optionGroup = civicrm_api3('OptionGroup', 'Create', $params);
      } catch (CiviCRM_API3_Exception $ex) {
        throw new Exception('Could not create option group '.$optionGroupName.
          ', error from API OptionGroup Create: '.$ex->getMessage());
      }
    }
    return $optionGroup;
  }

  /**
   * Function to get an option group
   *
   * @param string $optionGroupName
   * @return boolean
   * @access public
   * @static
   */
  public static function getOptionGroup($optionGroupName) {
    $params = array('name' => $optionGroupName);
    try {
      $optionGroup = civicrm_api3('OptionGroup', 'Getsingle', $params);
    } catch (CiviCRM_API3_Exception $ex) {
      $optionGroup = array();
    }
    return $optionGroup;
  }

  /**
   * Function to create label from name
   *
   * @param $name
   * @return string
   * @access public
   * @static
   */
  public static function createLabelFromName($name) {
    $labelExplode = explode('_', $name);
    foreach ($labelExplode as $key => $label) {
      $labelExplode[$key] = ucfirst($label);
    }
    return implode(' ', $labelExplode);
  }

  /**
   * Function to get option label for option value/option group id
   *
   * @param mixed $optionValue
   * @param int $optionGroupId
   * @return string $optionLabel
   * @access public
   * @static
   */
  public static function getOptionValueLabel($optionValue, $optionGroupId) {
    $optionLabel = '';
    if (!empty($optionValue) && !empty($optionGroupId)) {
      $params = array(
        'option_group_id' => $optionGroupId,
        'value' => $optionValue,
        'return' => 'label'
      );
      try {
        $optionLabel = civicrm_api3('OptionValue', 'Getvalue', $params);
      } catch (CiviCRM_API3_Exception $ex) {
        $optionLabel = '';
      }
    }
    return $optionLabel;
  }
}