<?php
/**
 * Page MafInvoicesList to list all cost invoices
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @license AGPL-3.0
 */
require_once 'CRM/Core/Page.php';

class CRM_Costinvoicelink_Page_InvoicesList extends CRM_Core_Page {

  function run() {
    $this->setPageConfiguration();
    $mafInvoices = $this->getInvoices();
    $this->assign('mafInvoices', $mafInvoices);
    parent::run();
  }
  /**
   * Function to set actions for row
   *
   * @param int $invoiceId
   * @return array
   * @access protected
   */
  protected function setRowActions($invoiceId) {
    $rowActions = array();
    $applyContactsUrl = CRM_Utils_System::url('civicrm/mafcontactsapply', 'action=update&iid='.$invoiceId, true);
    $applyActivitiesUrl = CRM_Utils_System::url('civicrm/mafactivitiesapply', 'action=update&iid='.$invoiceId, true);
    $deleteUrl = CRM_Utils_System::url('civicrm/mafinvoice', 'action=delete&iid='.$invoiceId, true);
    $rowActions[] = '<a class="action-item" title="Delete" href="'.$deleteUrl.'">Delete</a>';
    $rowActions[] = '<a class="action-item" title="SaveContacts" href="'.$applyContactsUrl.'">Contacts criteria</a>';
    $rowActions[] = '<a class="action-item" title="SaveActivities" href="'.$applyActivitiesUrl.'">Activities criteria</a>';
    return $rowActions;
  }
  /**
   * Function to set the page configuration initially
   *
   * @access protected
   */
  protected function setPageConfiguration() {
    $extensionConfig = CRM_Costinvoicelink_Config::singleton();
    CRM_Utils_System::setTitle($extensionConfig->getPageTitle());
    $this->assign('addUrl', CRM_Utils_System::url('civicrm/mafinvoice', 'action=add', true));
    $this->assign('helpTxt', $extensionConfig->getPageHelpTxt());
    $this->assign('invoiceIdentifierLabel', $extensionConfig->getPageInvoiceIdentifierLabel());
    $this->assign('contactSourceLabel', $extensionConfig->getContactSourceLabel());
    $this->assign('contactFromDateLabel', $extensionConfig->getSourceDateFromLabel());
    $this->assign('contactToDateLabel', $extensionConfig->getSourceDateToLabel());
    $this->assign('activityTypeLabel', $extensionConfig->getActTypeFilterLabel());
    $this->assign('activitySubjectLabel', $extensionConfig->getActivitySubjectLabel());
    $this->assign('activityFromDateLabel', $extensionConfig->getActivityDateFromLabel());
    $this->assign('activityToDateLabel', $extensionConfig->getActivityDateToLabel());
    $this->assign('addButtonLabel', $extensionConfig->getPageAddButtonLabel());
  }
  /**
   * Function to get invoices
   *
   * @return array $mafInvoices
   * @access protected
   */
  protected function getInvoices() {
    $extensionConfig = CRM_Costinvoicelink_Config::singleton();
    $sourceOptionGroupId = $extensionConfig->getContactSourceOptionGroupId();
    $mafInvoices = CRM_Costinvoicelink_BAO_Invoice::getValues(array());
    foreach ($mafInvoices as $key => $values) {
      if (isset($values['activity_type_id'])) {
        $activityTypeOptionGroup = CRM_Costinvoicelink_Utils::getOptionGroup('activity_type');
        $mafInvoices[$key]['activity_type'] = CRM_Costinvoicelink_Utils::getOptionValueLabel($values['activity_type_id'],
          $activityTypeOptionGroup['id']);
      }
      if (isset($values['contact_source'])) {
        $mafInvoices[$key]['contact_source'] = CRM_Costinvoicelink_Utils::getOptionValueLabel($values['contact_source'], $sourceOptionGroupId);
      }
      $mafInvoices[$key]['activity_subjects'] = $this->getInvoiceActivitySubjects($key);
      $mafInvoices[$key]['actions'] = $this->setRowActions($key);
    }
    return $mafInvoices;
  }

  /**
   * Method to put all activity subjects for invoice in one field
   *
   * @param int $invoiceId
   * @return string $subjectString
   * @access protected
   */
  protected function getInvoiceActivitySubjects($invoiceId) {
    $subjects = array();
    $query = 'SELECT * FROM civicrm_maf_invoice_activity_subject WHERE invoice_id = %1';
    $params = array(1 => array($invoiceId, 'Integer'));
    $dao = CRM_Core_DAO::executeQuery($query, $params);
    while ($dao->fetch()) {
      $subjects[] = $dao->activity_subject;
    }
    $subjectString = implode('; ', $subjects);
    if (strlen($subjectString > 70)) {
      $subjectString = substr($subjectString, 0, 70).'...';
    }
    return $subjectString;
  }
}
