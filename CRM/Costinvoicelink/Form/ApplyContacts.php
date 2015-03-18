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
  protected $sourceCustomField = NULL;
  protected $sourceDateCustomField = NULL;
  protected $sourceMotivationCustomField = NULL;
  protected $invoiceId = NULL;

  /**
   * Overridden parent method to buildQuickForm (call parent method too)
   */
  function buildQuickForm() {
    $this->setFormTitle();
    $this->addFormElements();

    parent::buildQuickForm();
  }

  /**
   * Overridden parent method to set default values
   */
  function setDefaultValues() {
    $defaults = array();
    $defaults['invoice_id'] = $this->invoiceId;
    if ($this->_action == CRM_Core_Action::UPDATE) {
      $mafInvoice = CRM_Costinvoicelink_BAO_Invoice::getValues(array('id' => $this->invoiceId));
      if (!empty($mafInvoice)) {
        if (isset($mafInvoice[$this->invoiceId]['contact_source'])) {
          $defaults['contactSourceFilter'] = $mafInvoice[$this->invoiceId]['contact_source'];
        }
        if (isset($mafInvoice[$this->invoiceId]['contact_from_date'])) {
          list($defaults['sourceDateFrom']) = CRM_Utils_Date::setDateDefaults($mafInvoice[$this->invoiceId]['contact_from_date']);
        }
        if (isset($mafInvoice[$this->invoiceId]['contact_to_date'])) {
          list($defaults['sourceDateTo']) = CRM_Utils_Date::setDateDefaults($mafInvoice[$this->invoiceId]['contact_to_date']);
        }
      }
    }
    return $defaults;
  }
  /**
   * Overridden parent method to initiate form
   */
  function preProcess() {
    $this->invoiceId = CRM_Utils_Request::retrieve('iid', 'Positive');
    $this->getContactSources();
    $extensionConfig = CRM_Costinvoicelink_Config::singleton();
    $this->sourceCustomField = 'custom_'.$extensionConfig->getSourceSourceCustomFieldId();
    $this->sourceDateCustomField = 'custom_'.$extensionConfig->getSourceDateCustomFieldId();
    $this->sourceMotivationCustomField = 'custom_'.$extensionConfig->getSourceMotivationCustomFieldId();
    $this->setContactFormLabels();
    $session = CRM_Core_Session::singleton();
    $session->pushUserContext(CRM_Utils_System::url('civicrm/mafinvoicelist', 'reset=1', true));
  }

  /**
   * Method to set the contact form labels
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
    $this->assign('contactFormHeader', $actionLabel.' '.$extensionConfig->getContactFormHeader().' '
      .CRM_Costinvoicelink_BAO_Invoice::getExternalIdentifierWithId($this->invoiceId));
  }

  /**
   * Overridden parent method to process form (calls parent method too)
   */
  function postProcess() {
    $this->saveInvoiceContactSelection($this->_submitValues);
    parent::postProcess();
    $session = CRM_Core_Session::singleton();
    $session->setStatus('Contact Selection for Cost Invoice '.CRM_Costinvoicelink_BAO_Invoice::getExternalIdentifierWithId($this->invoiceId).' saved', 'Save', 'success');
    CRM_Utils_System::redirect($session->readUserContext());
  }

  /**
   * Method to save the contact selection for the invoice
   *
   * @param $formValues
   * @access protected
   */
  protected function saveInvoiceContactSelection($formValues) {
    $invoiceContactParams = array(
      'id' => $formValues['invoice_id'],
      'contact_source' => $formValues['contactSourceFilter'],
      'contact_from_date' => date('Ymd', strtotime($formValues['sourceDateFrom'])),
      'contact_to_date' => date('Ymd', strtotime($formValues['sourceDateTo']))
    );
    $savedInvoice = CRM_Costinvoicelink_BAO_Invoice::add($invoiceContactParams);
    $this->invoiceId = $savedInvoice['id'];
  }

  /**
   * Method to add form elements
   *
   * @access protected
   */
  protected function addFormElements() {
    $extensionConfig = CRM_Costinvoicelink_Config::singleton();
    $this->add('text', 'invoice_id', ts('InvoiceId'));
    $this->add('select', 'contactSourceFilter', $extensionConfig->getContactSourceLabel(), $this->contactSources);
    $this->addDate('sourceDateFrom', $extensionConfig->getSourceDateFromLabel(), true);
    $this->addDate('sourceDateTo', $extensionConfig->getSourceDateToLabel(), true);
    $this->addButtons(array(
      array('type' => 'submit', 'name' => $extensionConfig->getSaveButtonLabel()),
      array('type' => 'cancel', 'name' => $extensionConfig->getCancelButtonLabel())));
  }

  /**
   * Method to get the contact sources
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
   * Method to set the form title based on action and data coming in
   *
   * @access protected
   */
  protected function setFormTitle() {
    $title = 'Cost Invoice Link';
    CRM_Utils_System::setTitle($title);
  }

  /**
   * Overridden parent method to set validation rules
   *
   * @access public
   */
  public function addRules() {
    $this->addFormRule(array('CRM_Costinvoicelink_Form_ApplyContacts', 'validateSourceEmpty'));
    $this->addFormRule(array('CRM_Costinvoicelink_Form_ApplyContacts', 'validateFromAndToDates'));
  }
  /**
   * Method to validate if source is not empty

   * @param array $fields
   * @return array/bool
   * @access public
   * @static
   */
  public static function validateSourceEmpty($fields) {
    $extensionConfig = CRM_Costinvoicelink_Config::singleton();
    if (empty($fields['contactSourceFilter'])) {
      $errors['contactSourceFilter'] = $extensionConfig->getContactSourceErrorMessage();
      return $errors;
    }
    return TRUE;
  }
  /**
   * Method to validate from date and to date are OK together

   * @param array $fields
   * @return array/bool
   * @access public
   * @static
   */
  public static function validateFromAndToDates($fields) {
    $extensionConfig = CRM_Costinvoicelink_Config::singleton();
    $fromDate = new DateTime($fields['sourceDateFrom']);
    $toDate = new DateTime($fields['sourceDateTo']);
    if ($toDate <= $fromDate) {
      $errors['sourceDateFrom'] = $extensionConfig->getContactDateErrorMessage();
      return $errors;
    }
    return TRUE;
  }
}
