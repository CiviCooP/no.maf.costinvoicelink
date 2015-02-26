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

  protected $contactSources = NULL;
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
    $defaults['invoice_id'] = CRM_Utils_Request::retrieve('iid', 'Positive');
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
   * Function to get the url params for the selected filters
   *
   * @return string
   * @access protected
   */
  protected function getFiltersUrlParams() {
    $urlParams = array();
    $urlParams[] = 'iid='.$this->_submitValues['invoice_id'];
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
    return $paramString;
  }

  /**
   * Function to process selected search filters
   *
   * @access protected
   */
  protected function processSearchContact() {
    if (empty($this->_submitValues['contactSourceFilter'])) {
      $session = CRM_Core_Session::singleton();
      $session->setStatus('You have to select a contact source to search with', 'No contact source selected', 'error');
    }
    $paramString = $this->getFiltersUrlParams();
    CRM_Utils_System::redirect(CRM_Utils_System::url('civicrm/mafcontactsapply', $paramString, true));
  }

  /**
   * Function to apply cost invoice to all selected contacts
   *
   * @access protected
   */
  protected function processApplyContacts() {
    $session = CRM_Core_Session::singleton();
    if (!isset($this->_submitValues['selectedContacts']) || empty($this->_submitValues['selectedContacts'])) {
      $session->setStatus('No contacts to apply the cost invoice to were selected', 'No contacts selected', 'error');
      $paramString = $this->getFiltersUrlParams();
      CRM_Utils_System::redirect(CRM_Utils_System::url('civicrm/mafcontactsapply', $paramString, true));
    } else {
      foreach ($this->_submitValues['selectedContacts'] as $selectedContactId) {
        $this->linkCostInvoiceToContact($selectedContactId);
      }
      $session->setStatus('Cost Invoice applied to selected contacts', 'Cost Invoice Applied', 'success');
      CRM_Utils_System::redirect($session->readUserContext());
    }
  }

  /**
   * Function to link invoice to contact
   *
   * @param $contactId
   * @access protected
   */
  protected function linkCostInvoiceToContact($contactId) {
    $params = array(
      'invoice_id' => $this->_submitValues['invoice_id'],
      'entity' => 'Contact',
      'entity_id' => $contactId);
    $existingLinks = CRM_Costinvoicelink_BAO_InvoiceEntity::getValues($params);
    if (empty($existingLinks)) {
      $params['linked_date'] = date('Ymd');
      CRM_Costinvoicelink_BAO_InvoiceEntity::add($params);
    }
  }

  /**
   * Function to add form elements
   *
   * @access protected
   */
  protected function addFormElements() {
    $extensionConfig = CRM_Costinvoicelink_Config::singleton();
    $this->add('text', 'invoice_id', ts('InvoiceId'));
    $this->add('select', 'contactSourceFilter', $extensionConfig->getContactSourceLabel(), $this->contactSources, true);
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
    $params = array(
      'option_group_id' => $extensionConfig->getContactSourceOptionGroupId(),
      'options' => array('limit' => 0));
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
   * Function to build contact query
   *
   * @return string
   * @access protected
   */
  protected function buildContactQuery() {
    $extensionConfig = CRM_Costinvoicelink_Config::singleton();
    $contactQuery = '
    SELECT cc.id, cc.contact_type, cc.display_name, cus.'.$extensionConfig->getSourceSourceCustomFieldColumn().',
     '.$extensionConfig->getSourceDateCustomFieldColumn().', '.$extensionConfig->getSourceMotivationCustomFieldColumn().'
    FROM civicrm_contact cc
    LEFT JOIN '.$extensionConfig->getSourceCustomGroupTable().' cus ON cc.id = cus.entity_id';
    $contactWhere = 'WHERE cc.id NOT IN(SELECT contact_id FROM civicrm_activity_contact)';
    if (!empty($this->csFilter)) {
      $contactWhere .= ' AND cus.'.$extensionConfig->getSourceSourceCustomFieldColumn().' = '.$this->csFilter;
    }
    return $contactQuery.' '.$contactWhere;
  }

  /**
   * Function to select contacts
   * - always only select those contacts that do not have any activities
   * - add filters if required
   *
   * @return array $contacts
   */
  protected function selectContacts() {
    $contacts = array();
    $contactQuery = $this->buildContactQuery();
    $daoContact = CRM_Core_DAO::executeQuery($contactQuery);
    while ($daoContact->fetch()) {
      if ($this->contactInDateRange($daoContact) == TRUE) {
        $contacts[$daoContact->id] = $this->createContactRow($daoContact);
      }
    }
    return $contacts;
  }

  /**
   * Function to create a row for a contact
   *
   * @param object $daoContact
   * @return array $row
   * @access protected
   */
  protected function createContactRow($daoContact) {
    $row = array();
    $row['display_name'] = $daoContact->display_name;
    $row['contact_type'] = $daoContact->contact_type;
    $extensionConfig = CRM_Costinvoicelink_Config::singleton();
    $sourceColumn = $extensionConfig->getSourceSourceCustomFieldColumn();
    $motivationColumn = $extensionConfig->getSourceMotivationCustomFieldColumn();
    $dateColumn = $extensionConfig->getSourceDateCustomFieldColumn();
    $sourceOptionGroupId = $extensionConfig->getContactSourceOptionGroupId();
    if (isset($daoContact->$sourceColumn)) {
      $row['source'] = CRM_Costinvoicelink_Utils::getOptionValueLabel($daoContact->$sourceColumn, $sourceOptionGroupId);
    }
    if (isset($daoContact->$dateColumn)) {
      $row['source_date'] = $daoContact->$dateColumn;
    }
    if (isset($daoContact->$motivationColumn) && !empty($daoContact->$motivationColumn)) {
      $row['source_motivation'] = $this->sourceMotivationToString($daoContact->$motivationColumn);
    }
    return $row;
  }

  /**
   * Function to convert array with source motivations into string
   *
   * @param array $sourceMotivations
   * @return string $motivationString
   * @access protected
   */
  protected function sourceMotivationToString($sourceMotivations) {
    $motivationArray = array();
    $extensionConfig = CRM_Costinvoicelink_Config::singleton();
    $motivationOptionGroupId = $extensionConfig->getContactSourceMotivationOptionGroupId();
    if (is_array($sourceMotivations)) {
      foreach ($sourceMotivations as $sourceMotivation) {
        $motivationArray[] = CRM_Costinvoicelink_Utils::getOptionValueLabel($sourceMotivation, $motivationOptionGroupId);
      }
      $motivationString = implode('; ', $motivationArray);
    } else {
      $motivationString = CRM_Costinvoicelink_Utils::getOptionValueLabel($sourceMotivations, $motivationOptionGroupId);
    }
    return $motivationString;
  }

  /**
   * Function to check if the contact is in the filtered date range
   *
   * @param object $daoContact
   * @return boolean
   * @access protected
   */
  protected function contactInDateRange($daoContact) {
    if (!empty($this->dfFilter)) {
      if ($daoContact->{$this->sourceDateCustomField} >= date('Y-m-d', strtotime($this->dfFilter))) {
        $fromFilter = TRUE;
      } else {
        $fromFilter = FALSE;
      }
    } else {
      $fromFilter = TRUE;
    }
    if (!empty($this->dtFilter)) {
      if ($daoContact->{$this->sourceDateCustomField} <= date('Y-m-d', strtotime($this->dtFilter))) {
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
