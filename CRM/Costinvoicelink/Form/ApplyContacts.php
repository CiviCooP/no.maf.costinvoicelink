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

  /**
   * Overridden parent method to buildQuickForm (call parent method too)
   */
  function buildQuickForm() {
    $this->addFormElements();

    parent::buildQuickForm();
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
    $this->assign('contactFormHeader', $actionLabel.' '.$extensionConfig->getContactFormHeader());
    $this->assign('contactFilterLabel', $extensionConfig->getContactFilterLabel());
  }

  /**
   * Overridden parent method to process form (calls parent method too)
   */
  function postProcess() {
    CRM_Core_Error::debug('values', $this->_submitValues);
    parent::postProcess();
  }

  /**
   * Function to add form elements
   *
   * @access protected
   */
  protected function addFormElements() {
    $extensionConfig = CRM_Costinvoicelink_Config::singleton();
    $this->assign('sourceDateFromLabel', $extensionConfig->getSourceDateFromLabel());
    $this->assign('sourceDateToLabel', $extensionConfig->getSourceDateToLabel());
    $this->addButtons(array(
      array('type' => 'submit', 'name' => ts('Apply To Contacts'), 'isDefault' => true,),
      array('type' => 'cancel', 'name' => ts('Cancel'))));
  }
}
