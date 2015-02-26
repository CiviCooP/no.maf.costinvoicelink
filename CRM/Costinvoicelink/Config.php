<?php
/**
 * Class following Singleton pattern for specific extension configuration
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @license AGPL-3.0
 */
class CRM_Costinvoicelink_Config {
  /*
   * singleton pattern
   */
  static private $_singleton = NULL;
  /*
   * properties to hold the contact source and contact source motivation option groups
   */
  protected $contactSourceOptionGroupId = NULL;
  protected $contactSourceMotivationOptionGroupId = NULL;
  /*
   * generic form labels
   */
  protected $cancelButtonLabel = NULL;
  protected $selectLabel = NULL;

  /*
   * properties to hold the page labels
   */
  protected $pageTitle = NULL;
  protected $pageHelpTxt = NULL;
  protected $pageInvoiceIdentifierLabel = NULL;
  protected $pageAddButtonLabel = NULL;
  /*
   * properties to hold the contact form labels and explanation
   */
  protected $contactFormHeader = NULL;
  protected $contactFormApplyButtonLabel = NULL;
  protected $contactSearchButtonLabel = NULL;
  protected $contactFilterLabel = NULL;
  protected $sourceDateFromLabel = NULL;
  protected $sourceDateToLabel = NULL;

  protected $contactDisplayNameLabel = NULL;
  protected $contactContactTypeLabel = NULL;
  protected $contactSourceLabel = NULL;
  protected $contactSourceDateLabel = NULL;
  protected $contactSourceMotivationLabel = NULL;
  protected $contactSelectErrorMessage = NULL;
  /*
   * properties to hold the activity form labels and explanation
   */
  protected $actFormHeader = NULL;
  protected $actFormApplyButtonLabel = NULL;
  protected $actSearchButtonLabel = NULL;
  protected $actFilterLabel = NULL;
  protected $actTypeFilterLabel = NULL;
  protected $actDateFromFilterLabel = NULL;
  protected $actDateToFilterLabel = NULL;

  protected $actListTypeLabel = NULL;
  protected $actListSubjectLabel = NULL;
  protected $actListTargetsLabel = NULL;
  protected $actListDateLabel = NULL;
  protected $actSelectErrorMessage = NULL;
  /*
   * properties to hold the invoice form labels and explanation
   */
  protected $invoiceFormHeader = NULL;
  protected $invoiceFormInvoiceIdentifierLabel = NULL;
  protected $externalIdExistsMessage = NULL;
  /*
   * properties for source custom group and fields
   */
  protected $sourceCustomGroupName = NULL;
  protected $sourceCustomGroupTable = NULL;
  protected $sourceCustomGroupId = NULL;

  protected $sourceSourceCustomFieldId = NULL;
  protected $sourceSourceCustomFieldName = NULL;
  protected $sourceSourceCustomFieldColumn = NULL;

  protected $sourceDateCustomFieldId = NULL;
  protected $sourceDateCustomFieldName = NULL;
  protected $sourceDateCustomFieldColumn = NULL;

  protected $sourceMotivationCustomFieldId = NULL;
  protected $sourceMotivationCustomFieldName = NULL;
  protected $sourceMotivationCustomFieldColumn = NULL;

  protected $sourceNoteCustomFieldId = NULL;
  protected $sourceNoteCustomFieldName = NULL;
  protected $sourceNoteCustomFieldColumn = NULL;
  /*
   * properties for option groups and values
   */
  protected $activityTypeOptionGroupId = NULL;

  /**
   * Constructor
   */
  function __construct() {
    $this->cancelButtonLabel = ts('Cancel');
    $this->selectLabel = ts('Select');
    $this->setPageLabels();
    $this->setInvoiceFormLabels();
    $this->setContactFormLabels();
    $this->setActivityFormLabels();
    $this->setActivityTypeOptionGroupId();
    $this->createSourceCustomGroupAndFields();
  }

  /**
   * Function to return singleton object
   *
   * @return object $_singleton
   * @access public
   * @static
   */
  public static function &singleton() {
    if (self::$_singleton === NULL) {
      self::$_singleton = new CRM_Costinvoicelink_Config();
    }
    return self::$_singleton;
  }

  /**
   * Function to get the activity type option group id
   *
   * @return int
   * @access public
   */
  public function getActivityTypeOptionGroupId() {
    return $this->activityTypeOptionGroupId;
  }

  /**
   * Function to get the option group id of the contact source
   *
   * @return int
   * @access public
   */
  public function getContactSourceOptionGroupId() {
    return $this->contactSourceOptionGroupId;
  }

  /**
   * Function to get the option group id of the contact source motivation
   *
   * @return null
   * @access public
   */
  public function getContactSourceMotivationOptionGroupId() {
    return $this->contactSourceMotivationOptionGroupId;
  }

  /**
   * Function to get the contact source custom group id
   *
   * @return int
   * @access public
   */
  public function getSourceCustomGroupId() {
    return $this->sourceCustomGroupId;
  }

  /**
   * Function to get the contact source custom group name
   *
   * @return string
   * @access public
   */
  public function getSourceCustomGroupName() {
    return $this->sourceCustomGroupName;
  }

  /**
   * Function to get the contact source custom group table name
   *
   * @return string
   * @access public
   */
  public function getSourceCustomGroupTable() {
    return $this->sourceCustomGroupTable;
  }

  /**
   * Function to get the contact source custom field id
   *
   * @return int
   * @access public
   */
  public function getSourceSourceCustomFieldId() {
    return $this->sourceSourceCustomFieldId;
  }

  /**
   * Function to get the contact source date custom field id
   *
   * @return int
   * @access public
   */
  public function getSourceDateCustomFieldId() {
    return $this->sourceDateCustomFieldId;
  }

  /**
   * Function to get the contact source motivation custom field id
   *
   * @return int
   * @access public
   */
  public function getSourceMotivationCustomFieldId() {
    return $this->sourceMotivationCustomFieldId;
  }

  /**
   * Function to get the contact source note custom field id
   *
   * @return int
   * @access public
   */
  public function getSourceNoteCustomFieldId() {
    return $this->sourceNoteCustomFieldId;
  }

  /**
   * Function to get the contact source custom field name
   *
   * @return string
   * @access public
   */
  public function getSourceSourceCustomFieldName() {
    return $this->sourceSourceCustomFieldName;
  }

  /**
   * Function to get the contact source custom date field name
   *
   * @return string
   * @access public
   */
  public function getSourceDateCustomFieldName() {
    return $this->sourceDateCustomFieldName;
  }

  /**
   * Function to get the contact source motivation custom field name
   *
   * @return string
   * @access public
   */
  public function getSourceMotivationCustomFieldName() {
    return $this->sourceMotivationCustomFieldName;
  }

  /**
   * Function to get the contact source note custom field name
   *
   * @return string
   * @access public
   */
  public function getSourceNoteCustomFieldName() {
    return $this->sourceNoteCustomFieldName;
  }

  /**
   * Function to get the contact source custom field column name
   *
   * @return string
   * @access public
   */
  public function getSourceSourceCustomFieldColumn() {
    return $this->sourceSourceCustomFieldColumn;
  }

  /**
   * Function to get the contact source date custom field column name
   *
   * @return string
   * @access public
   */
  public function getSourceDateCustomFieldColumn() {
    return $this->sourceDateCustomFieldColumn;
  }

  /**
   * Function to get the contact source motivation custom field column name
   *
   * @return string
   * @access public
   */
  public function getSourceMotivationCustomFieldColumn() {
    return $this->sourceMotivationCustomFieldColumn;
  }

  /**
   * Function to get the contact source note custom field column name
   *
   * @return string
   * @access public
   */
  public function getSourceNoteCustomFieldColumn() {
    return $this->sourceNoteCustomFieldColumn;
  }

  /**
   * Function to get the cancel button form Label
   *
   * @return string
   * @access public
   */
  public function getCancelButtonLabel() {
    return $this->cancelButtonLabel;
  }

  /**
   * Function to get the select label
   *
   * @return string
   * @access public
   */
  public function getSelectLabel() {
    return $this->selectLabel;
  }

  /**
   * Function to get the contact source label for contact form
   *
   * @return string
   * @access public
   */
  public function getContactSourceLabel() {
    return $this->contactSourceLabel;
  }

  /**
   * Function to get the contact display name form label
   *
   * @return string
   * @access public
   */
  public function getContactDisplayNameLabel() {
    return $this->contactDisplayNameLabel;
  }

  /**
   * Function to get the contact type form label
   *
   * @return string
   * @access public
   */
  public function getContactContactTypeLabel() {
    return $this->contactContactTypeLabel;
  }

  /**
   * Function to get the contact source date label
   *
   * @return string
   * @access public
   */
  public function getContactSourceDateLabel() {
    return $this->contactSourceDateLabel;
  }

  /**
   * Function to get the contact source motivation label
   *
   * @return string
   * @access public
   */
  public function getContactSourceMotivationLabel() {
    return $this->contactSourceMotivationLabel;
  }

  /**
   * Function to get the source date from label for contact form
   *
   * @return string
   * @access public
   */
  public function getSourceDateFromLabel() {
    return $this->sourceDateFromLabel;
  }

  /**
   * Function to get the source date to label for contact form
   *
   * @return string
   * @access public
   */
  public function getSourceDateToLabel() {
    return $this->sourceDateToLabel;
  }

  /**
   * Function to get the no contacts selected error message
   *
   * @return string
   * @access public
   */
  public function getContactSelectErrorMessage() {
    return $this->contactSelectErrorMessage;
  }

  /**
   * Function to get the external id exists message
   *
   * @return string
   * @access public
   */
  public function getExternalIdExistsMessage() {
    return $this->externalIdExistsMessage;
  }

  /**
   * Function to get the invoice form header
   *
   * @return string
   * @access public
   */
  public function getInvoiceFormHeader() {
    return $this->invoiceFormHeader;
  }

  /**
   * Function to get the contact form header
   *
   * @return string
   * @access public
   */
  public function getContactFormHeader() {
    return $this->contactFormHeader;
  }

  /**
   * Function to get the contactFormApplyButtonLabel
   *
   * @return string
   * @access public
   */
  public function getContactFormApplyButtonLabel() {
    return $this->contactFormApplyButtonLabel;
  }

  /**
   * Function to get the contactSearchButtonLabel
   *
   * @return string
   * @access public
   */
  public function getContactSearchButtonLabel() {
    return $this->contactSearchButtonLabel;
  }

  /**
   * Function to get the contactFilterLabel
   *
   * @return string
   * @access public
   */
  public function getContactFilterLabel() {
    return $this->contactFilterLabel;
  }

  /**
   * Function to get the invoiceFormInvoiceIdentifierLabel
   *
   * @return string
   * @access public
   */
  public function getInvoiceFormInvoiceIdentifierLabel() {
    return $this->invoiceFormInvoiceIdentifierLabel;
  }

  /**
   * Function to get the pageTitle
   *
   * @return string
   * @access public
   */
  public function getPageTitle() {
    return $this->pageTitle;
  }

  /**
   * Function to get the pageHelpTxt
   *
   * @return string
   * @access public
   */
  public function getPageHelpTxt() {
    return $this->pageHelpTxt;
  }

  /**
   * Function to get the pageInvoiceIdentifierLabel
   *
   * @return string
   * @access public
   */
  public function getPageInvoiceIdentifierLabel() {
    return $this->pageInvoiceIdentifierLabel;
  }

  /**
   * Function to get the pageAddButtonLabel
   *
   * @return string
   * @access public
   */
  public function getPageAddButtonLabel() {
    return $this->pageAddButtonLabel;
  }

  /**
   * Function to get the activity form header
   *
   * @return string
   * @access public
   */
  public function getActFormHeader() {
    return $this->actFormHeader;
  }

  /**
   * Function to get the activity form apply button label
   *
   * @return string
   * @access public
   */
  public function getActFormApplyButtonLabel() {
    return $this->actFormApplyButtonLabel;
  }

  /**
   * Function to get the activity search button label
   *
   * @return string
   * @access public
   */
  public function getActSearchButtonLabel() {
    return $this->actSearchButtonLabel;
  }

  /**
   * Function to get the activity filter label
   *
   * @return string
   * @access public
   */
  public function getActFilterLabel() {
    return $this->actFilterLabel;
  }

  /**
   * Function to get the activity type filter label
   *
   * @return string
   * @access public
   */
  public function getActTypeFilterLabel() {
    return $this->actTypeFilterLabel;
  }

  /**
   * Function to get the activity date from filter label
   *
   * @return string
   * @access public
   */
  public function getActDateFromFilterLabel() {
    return $this->actDateFromFilterLabel;
  }

  /**
   * Function to get the activity date to filter label
   *
   * @return string
   * @access public
   */
  public function getActDateToFilterLabel() {
    return $this->actDateToFilterLabel;
  }

  /**
   * Function to get the activity list type label
   *
   * @return string
   * @access public
   */
  public function getActListTypeLabel() {
    return $this->actListTypeLabel;
  }

  /**
   * Function to get the activity list subject label
   *
   * @return string
   * @access public
   */
  public function getActListSubjectLabel() {
    return $this->actListSubjectLabel;
  }

  /**
   * Function to get the activity list targets label
   *
   * @return string
   * @access public
   */
  public function getActListTargetsLabel() {
    return $this->actListTargetsLabel;
  }

  /**
   * Function to get the activity list date label
   *
   * @return string
   * @access public
   */
  public function getActListDateLabel() {
    return $this->actListDateLabel;
  }

  /**
   * Function to set the page labels for Invoices List
   *
   * @access protected
   */
  protected function setPageLabels() {
    $this->pageTitle = ts('MAF Norge Cost Invoices ');
    $this->pageHelpTxt = ts('The existing Cost Invoices are listed below. You can manage or '
      .'delete them from this screen or add a new Cost Invoice.');
    $this->pageInvoiceIdentifierLabel = ts('Invoice Identifier');
    $this->pageAddButtonLabel = ts('New Cost Invoice');
  }

  /**
   * Function to set the form labels for Invoice Add Form
   *
   * @access protected
   */
  protected function setInvoiceFormLabels() {
    $this->invoiceFormHeader = ts('MAF Cost Invoice');
    $this->invoiceFormInvoiceIdentifierLabel = ts('Invoice Identifier');
    $this->externalIdExistsMessage = ts('Invoice Identifier already exists in the database, can not be added');
  }

  /**
   * Function to set the form labels for Apply Contacts Form
   *
   * @access protected
   */
  protected function setContactFormLabels() {
    $this->contactFormHeader = ts('Apply MAF Cost Invoice to Contacts');
    $this->contactFormApplyButtonLabel = ts('Apply to selected contacts');
    $this->contactSearchButtonLabel = ts('Search contacts');
    $this->contactFilterLabel = ts('Search contact');
    $this->contactSourceLabel = ts('Contact source');
    $this->sourceDateFromLabel = ts('Source Date From');
    $this->sourceDateToLabel = ts('Source Date To');
    $this->contactDisplayNameLabel = ts('Contact Name');
    $this->contactContactTypeLabel = ts('Contact Type');
    $this->contactSourceDateLabel = ts('Contact Source Date');
    $this->contactSourceMotivationLabel = ts('Contact Source Motivation');
    $this->contactSelectErrorMessage = ts('No contacts selected');
  }

  /**
   * Function to set the form labels for Apply Activities Form
   *
   * @access protected
   */
  protected function setActivityFormLabels() {
    $this->actFormHeader = ts('Apply MAF Cost Invoice to Activities');
    $this->actFormApplyButtonLabel = ts('Apply to selected activities');
    $this->actSearchButtonLabel = ts('Search activities');
    $this->actFilterLabel = ts('Search Activity');
    $this->actTypeFilterLabel = ts('Activity Type');
    $this->actDateFromFilterLabel = ts('Activity Date From');
    $this->actDateToFilterLabel = ts('To');
    $this->actListTypeLabel = ts('Activity Type');
    $this->actListSubjectLabel = ts('Subject');
    $this->actListTargetsLabel = ts('Target(s)');
    $this->actListDateLabel = ts('Activity Date');
  }

  /**
   * Function to create custom group if required
   *
   */
  protected function createSourceCustomGroupAndFields() {
    $this->sourceCustomGroupName = 'maf_contact_source';
    $customGroup = CRM_Costinvoicelink_Utils::getCustomGroup($this->sourceCustomGroupName);
    if (empty($customGroup)) {
      $this->sourceCustomGroupTable = 'civicrm_value_maf_contact_source';
      $sourceCustomGroup = CRM_Costinvoicelink_Utils::createCustomGroup($this->sourceCustomGroupName, $this->sourceCustomGroupTable, 'Contact');
      $this->sourceCustomGroupId = $sourceCustomGroup['id'];
    } else {
      $this->sourceCustomGroupId = $customGroup['id'];
      $this->sourceCustomGroupTable = $customGroup['table_name'];
    }
    $this->createSourceCustomFields();
  }

  /**
   * Function to create custom fields for contact source custom group
   *
   * @access protected
   */
  protected function createSourceCustomFields() {
    $this->sourceSourceCustomFieldName = 'contact_source';
    $sourceOptionGroup = CRM_Costinvoicelink_Utils::createOptionGroup('maf_contact_source');
    $this->contactSourceOptionGroupId = $sourceOptionGroup['id'];
    $customField = $this->createSingleCustomField($this->sourceCustomGroupId, $this->sourceSourceCustomFieldName, 'String', 'Select', 1, $sourceOptionGroup['id']);
    $this->sourceSourceCustomFieldId = $customField['id'];
    $this->sourceSourceCustomFieldColumn = $customField['column_name'];

    $this->sourceDateCustomFieldName = 'contact_source_date';
    $customField = $this->createSingleCustomField($this->sourceCustomGroupId, $this->sourceDateCustomFieldName, 'Date', 'Select Date', 1);
    $this->sourceDateCustomFieldId = $customField['id'];
    $this->sourceDateCustomFieldColumn = $customField['column_name'];

    $this->sourceMotivationCustomFieldName = 'contact_source_motivation';
    $motivationOptionGroup = CRM_Costinvoicelink_Utils::createOptionGroup('maf_contact_source_motivation');
    $this->contactSourceMotivationOptionGroupId = $motivationOptionGroup['id'];
    $customField = $this->createSingleCustomField($this->sourceCustomGroupId, $this->sourceMotivationCustomFieldName, 'String', 'AdvMulti-Select', 1, $motivationOptionGroup['id']);
    $this->sourceMotivationCustomFieldId = $customField['id'];
    $this->sourceMotivationCustomFieldColumn = $customField['column_name'];

    $this->sourceNoteCustomFieldName = 'contact_source_note';
    $customField = $this->createSingleCustomField($this->sourceCustomGroupId, $this->sourceNoteCustomFieldName, 'Memo', 'TextArea', 0);
    $this->sourceNoteCustomFieldId = $customField['id'];
    $this->sourceNoteCustomFieldColumn = $customField['column_name'];
  }

  /**
   * Function to create custom Field if not exists
   *
   * @param int $customGroupId
   * @param string $name
   * @param string $dataType
   * @param string $htmlType
   * @param int $isSearchable
   * @param int $optionGroupId
   * @param null $defaultValue
   * @return array
   * @access protected
   */
  protected function createSingleCustomField($customGroupId, $name, $dataType, $htmlType, $isSearchable, $optionGroupId = 0, $defaultValue = NULL) {
    $customField = CRM_Costinvoicelink_Utils::getCustomField($customGroupId, $name);
    if (empty($customField)) {
      $customField = CRM_Costinvoicelink_Utils::createCustomField($customGroupId, $name, $name, $dataType, $htmlType, $isSearchable, $optionGroupId, $defaultValue);
    }
    return $customField;
  }

  /**
   * Function to set the activity type option group id
   *
   * @throws Exception when error from API
   * @access protected
   */
  protected function setActivityTypeOptionGroupId() {
    $params = array('name' => 'activity_type', 'return' => 'id');
    try {
      $this->activityTypeOptionGroupId = civicrm_api3('OptionGroup', 'Getvalue', $params);
    } catch (CiviCRM_API3_Exception $ex) {
      throw new Exception('Could not find activity group with name activity_type,
      error from API OptionGroup Getvalue: '.$ex->getMessage());
    }
  }
}
