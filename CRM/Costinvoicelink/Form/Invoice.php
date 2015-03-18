<?php

require_once 'CRM/Core/Form.php';

/**
 * Form controller class
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @license AGPL-3.0
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_Costinvoicelink_Form_Invoice extends CRM_Core_Form {

  /**
   * Overridden parent method to buildQuickForm (call parent method too)
   */
  function buildQuickForm() {
    $this->addFormElements();

    parent::buildQuickForm();
  }

  /**
   * Overridden parent method to add validation rules
   */
  function addRules() {
    $this->addFormRule(array('CRM_Costinvoicelink_Form_Invoice', 'validateInvoiceIdentifier'));
  }

  /**
   * Overridden parent method to initiate form
   */
  function preProcess() {
    $extensionConfig = CRM_Costinvoicelink_Config::singleton();
    if ($this->_action == CRM_Core_Action::ADD) {
      $actionLabel = 'Add';
    } else {
      $actionLabel = 'Edit';
    }
    $this->assign('formHeader', $actionLabel.' '.$extensionConfig->getInvoiceFormHeader());
    /*
     * if action = delete, execute delete immediately
     */
    if ($this->_action == CRM_Core_Action::DELETE) {
      CRM_Costinvoicelink_BAO_Invoice::deleteById(CRM_Utils_Request::retrieve('iid', 'Positive'));
      $session = CRM_Core_Session::singleton();
      $session->setStatus('Cost Invoice deleted', 'Delete', 'success');
      $session->pushUserContext(CRM_Utils_System::url('civicrm/mafinvoicelist', 'reset=1', true));
    }
  }

  /**
   * Overridden parent method to process form (calls parent method too)
   */
  function postProcess() {
    $values = $this->exportValues();
    CRM_Costinvoicelink_BAO_Invoice::add(array('external_id' => $values['external_id']));
    parent::postProcess();
  }

  /**
   * Function to add form elements
   *
   * @access protected
   */
  protected function addFormElements() {
    $extensionConfig = CRM_Costinvoicelink_Config::singleton();
    $this->add('text', 'external_id', $extensionConfig->getInvoiceFormInvoiceIdentifierLabel(), array('size' => CRM_Utils_Type::HUGE), true);
    $this->addButtons(array(
      array('type' => 'next', 'name' => ts('Save'), 'isDefault' => true,),
      array('type' => 'cancel', 'name' => ts('Cancel'))));
  }

  /**
   * Function to validate the external_id entered does not exist for add
   *
   * @param array $fields
   * @return array $errors or TRUE
   * @access public
   * @static
   */
  static function validateInvoiceIdentifier($fields) {
    $invoice = new CRM_Costinvoicelink_BAO_Invoice();
    $invoice->external_id = $fields['external_id'];
    if ($invoice->count() > 0) {
      $extensionConfig = CRM_Costinvoicelink_Config::singleton();
      $errors['external_id'] = $extensionConfig->getExternalIdExistsMessage();
      return $errors;
    }
    return TRUE;
  }
}
