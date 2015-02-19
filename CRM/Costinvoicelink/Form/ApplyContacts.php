<?php

require_once 'CRM/Core/Form.php';

/**
 * Form controller class
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @license AGPL-3.0
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_Costinvoicelink_Form_ApplyContacts extends CRM_Core_Form {

  protected $contactSources;
  protected $csFilter = NULL;
  protected $dfFilter = NULL;
  protected $dtFilter = NULL;
  protected $processFilter = FALSE;
  protected $sourceCustomField = NULL;
  protected $sourceDateCustomField = NULL;
  protected $sourceMotivationCustomField = NULL;

  /**
   * Overridden parent method to buildQuickForm (call parent method too)
   */
  function buildQuickForm() {
    $this->addFormElements();

    parent::buildQuickForm();
  }

  /**
   * Overridden parent method to set default values
   */
  function setDefaultValues() {
    $defaults = array();
    if ($this->processFilter == TRUE) {
      $selectedContacts = $this->selectContacts();
      $this->assign('mafContacts', $selectedContacts);
      if (!empty($this->csFilter)) {
        $defaults['contactSourceFilter'] = $this->csFilter;
      }
      if (!empty($this->dfFilter)) {
        list($defaults['sourceDateFrom']) = CRM_Utils_Date::setDateDefaults($this->dfFilter);
      }
      if (!empty($this->dtFilter)) {
        list($defaults['sourceDateTo']) = CRM_Utils_Date::setDateDefaults($this->dtFilter);
      }
    }
    return $defaults;
  }
  /**
   * Overridden parent method to initiate form
   */
  function preProcess() {
    if (isset($_REQUEST['cs']) || isset($_REQUEST['df']) || isset($_REQUEST['dt'])) {
      $this->processFilter = TRUE;
    } else {
      $this->processFilter = FALSE;
    }
    $this->csFilter = CRM_Utils_Request::retrieve('cs', 'Positive');
    $this->dfFilter = CRM_Utils_Request::retrieve('df', 'Date');
    $this->dtFilter = CRM_Utils_Request::retrieve('dt', 'Date');
    $this->getContactSources();
    $extensionConfig = CRM_Costinvoicelink_Config::singleton();
    $this->sourceCustomField = 'custom_'.$extensionConfig->getSourceSourceCustomFieldId();
    $this->sourceDateCustomField = 'custom_'.$extensionConfig->getSourceDateCustomFieldId();
    $this->sourceMotivationCustomField = 'custom_'.$extensionConfig->getSourceMotivationCustomFieldId();
    $this->assign('selectLabel', $extensionConfig->getSelectLabel());
    $this->setContactFormLabels();
  }

  /**
   * Function to set the contact form labels
   *
   * @access protected
   */
  protected function setContactFormLabels() {
    if ($this->_action == CRM_Core_Action::ADD) {
      $actionLabel = 'Add';
    } else {
      $actionLabel = 'Edit';
    }
    $extensionConfig = CRM_Costinvoicelink_Config::singleton();
    $this->assign('contactFormHeader', $actionLabel.' '.$extensionConfig->getContactFormHeader());
    $this->assign('contactFilterLabel', $extensionConfig->getContactFilterLabel());
    $this->assign('sourceDateFromLabel', $extensionConfig->getSourceDateFromLabel());
    $this->assign('sourceDateToLabel', $extensionConfig->getSourceDateToLabel());
    $this->assign('contactSearchButtonLabel', $extensionConfig->getContactSearchButtonLabel());
    $this->assign('contactDisplayNameLabel', $extensionConfig->getContactDisplayNameLabel());
    $this->assign('contactContactTypeLabel', $extensionConfig->getContactContactTypeLabel());
    $this->assign('contactSourceLabel', $extensionConfig->getContactSourceLabel());
    $this->assign('contactSourceDateLabel', $extensionConfig->getContactSourceDateLabel());
    $this->assign('contactSourceMotivationLabel', $extensionConfig->getContactSourceMotivationLabel());
  }

  /**
   * Overridden parent method to process form (calls parent method too)
   */
  function postProcess() {
    if (isset($this->_submitValues['_qf_ApplyContacts_submit'])) {
      if ($this->_submitValues['_qf_ApplyContacts_submit'] == 'Search contacts') {
        $this->processSearchContact();
      }
      if ($this->_submitValues['_qf_ApplyContacts_submit'] == 'Apply to selected contacts') {
        $this->processApplyContacts();
      }
    }
    parent::postProcess();
  }
  /**
   * Function to process selected search filters
   *
   * @access protected
   */
  protected function processSearchContact() {
    $urlParams = array();
    if (isset($this->_submitValues['contactSourceFilter']) && !empty($this->_submitValues['contactSourceFilter'])) {
      $urlParams[] = 'cs='.$this->_submitValues['contactSourceFilter'];
    }
    if (isset($this->_submitValues['sourceDateFrom']) && !empty($this->_submitValues['sourceDateFrom'])) {
      $urlParams[] = 'df='.date('Ymd', strtotime($this->_submitValues['sourceDateFrom']));
    }
    if (isset($this->_submitValues['sourceDateTo']) && !empty($this->_submitValues['sourceDateTo'])) {
      $urlParams[] = 'dt='.date('Ymd', strtotime($this->_submitValues['sourceDateTo']));
    }
    if (empty($urlParams)) {
      $paramString = 'reset=1';
    } else {
      $paramString = 'reset=1&'.implode('&', $urlParams);
    }
    CRM_Utils_System::redirect(CRM_Utils_System::url('civicrm/mafcontactsapply', $paramString, true));
  }
  /**
   * Function to apply cost invoice to all selected contacts
   *
   * @access protected
   */
  protected function processApplyContacts() {
    CRM_Core_Error::debug('submitValues', $this->_submitValues);
    exit();
  }

  /**
   * Function to add form elements
   *
   * @access protected
   */
  protected function addFormElements() {
    $extensionConfig = CRM_Costinvoicelink_Config::singleton();
    $this->add('select', 'contactSourceFilter', $extensionConfig->getContactSourceLabel(), $this->contactSources);
    $this->addDate('sourceDateFrom', $extensionConfig->getSourceDateFromLabel(), false);
    $this->addDate('sourceDateTo', $extensionConfig->getSourceDateToLabel(), false);
    $this->addButtons(array(
      array('type' => 'submit', 'name' => $extensionConfig->getContactFormApplyButtonLabel()),
      array('type' => 'cancel', 'name' => $extensionConfig->getCancelButtonLabel())));
  }

  /**
   * Function to get the contact sources
   *
   * @access protected
   */
  protected function getContactSources() {
    $extensionConfig = CRM_Costinvoicelink_Config::singleton();
    $params = array('option_group_id' => $extensionConfig->getContactSourceOptionGroupId());
    try {
      $optionValues = civicrm_api3('OptionValue', 'Get', $params);
      foreach ($optionValues['values'] as $optionValue) {
        $this->contactSources[$optionValue['value']] = $optionValue['label'];
      }
    } catch (CiviCRM_API3_Exception $ex) {
      $this->contactSources = array();
    }
    $this->contactSources[0] = '- select -';
    asort($this->contactSources);
  }

  /**
   * Function to select contacts based on filters
   *
   * @return array $contacts
   * @throws Exception when error from API
   */
  protected function selectContacts() {
    $params = array(
      'is_deceased' => 0,
      'is_deleted' => 0,
      'options' => array('limit' => 0),
      'return' => array($this->sourceDateCustomField, $this->sourceMotivationCustomField, 'display_name', 'contact_type'));
    if (isset($this->csFilter) && !empty($this->csFilter)) {
      $params[$this->sourceCustomField] = $this->csFilter;
    }
    try {
      $apiContacts = civicrm_api3('Contact', 'Get', $params);
      foreach ($apiContacts['values'] as $contactId => $apiContact) {
        if ($this->contactInDateRange($apiContact) == TRUE) {
          $contacts[$contactId] = $this->createContactRow($apiContact);
        }
      }
    } catch (CiviCRM_API3_Exception $ex) {
      throw new Exception('Error when searching for contacts, message from API Contact Get: '.$ex->getMessage());
    }
    return $contacts;
  }

  /**
   * Function to create a row for a contact
   *
   * @param array $contact
   * @return array $row
   * @access protected
   */
  protected function createContactRow($contact) {
    $row = array();
    $row['display_name'] = $contact['display_name'];
    $row['contact_type'] = $contact['contact_type'];
    $extensionConfig = CRM_Costinvoicelink_Config::singleton();
    $sourceOptionGroupId = $extensionConfig->getContactSourceOptionGroupId();
    $row['source'] = CRM_Costinvoicelink_Utils::getOptionValueLabel($contact[$this->sourceCustomField], $sourceOptionGroupId);
    $row['source_date'] = $contact[$this->sourceDateCustomField];
    $row['source_motivation'] = $this->sourceMotivationToString($contact[$this->sourceMotivationCustomField]);
    return $row;
  }

  /**
   * Function to convert array with source motivations into string
   *
   * @param array $sourceMotivations
   * @return string
   * @access protected
   */
  protected function sourceMotivationToString($sourceMotivations) {
    $motivationArray = array();
    $extensionConfig = CRM_Costinvoicelink_Config::singleton();
    $motivationOptionGroupId = $extensionConfig->getContactSourceMotivationOptionGroupId();
    if (!empty($sourceMotivations)) {
      foreach ($sourceMotivations as $sourceMotivation) {
        $motivationArray[] = CRM_Costinvoicelink_Utils::getOptionValueLabel($sourceMotivation, $motivationOptionGroupId);
      }
    }
    return implode('; ', $motivationArray);
  }

  /**
   * Function to check if the contact is in the filtered date range
   *
   * @param array $contact
   * @return boolean
   * @access protected
   */
  protected function contactInDateRange($contact) {
    $toFilter = FALSE;
    if (!empty($this->dfFilter)) {
      if ($contact[$this->sourceDateCustomField] >= date('Y-m-d', strtotime($this->dfFilter))) {
        $fromFilter = TRUE;
      } else {
        $fromFilter = FALSE;
      }
    } else {
      $fromFilter = TRUE;
    }
    if (!empty($this->dtFilter)) {
      if ($contact[$this->sourceDateCustomField] <= date('Y-m-d', strtotime($this->dtFilter))) {
        $toFilter = TRUE;
      } else {
        $toFilter = FALSE;
      }
    } else {
      $toFilter = TRUE;
    }
    if ($fromFilter == TRUE && $toFilter == TRUE) {
      return TRUE;
    } else {
      return FALSE;
    }
  }
}
