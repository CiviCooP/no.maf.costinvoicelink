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
    $rowActions[] = '<a class="action-item" title="ApplyContacts" href="'.$applyContactsUrl.'">Apply to contacts</a>';
    $rowActions[] = '<a class="action-item" title="ApplyActivities" href="'.$applyActivitiesUrl.'">Apply to activities</a>';
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
    $this->assign('addButtonLabel', $extensionConfig->getPageAddButtonLabel());
    $session = CRM_Core_Session::singleton();
    $session->pushUserContext(CRM_Utils_System::url('civicrm/mafinvoicelist', 'reset=1', true));
  }
  /**
   * Function to get invoices
   *
   * @return array $mafInvoices
   * @access protected
   */
  protected function getInvoices() {
    $mafInvoices = CRM_Costinvoicelink_BAO_Invoice::getValues(array());
    foreach ($mafInvoices as $key => $values) {
      $mafInvoices[$key]['actions'] = $this->setRowActions($key);
    }
    return $mafInvoices;
  }
}
