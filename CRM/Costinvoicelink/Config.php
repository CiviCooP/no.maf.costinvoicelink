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
   * properties to hold the page labels
   */
  protected $pageTitle = NULL;
  protected $pageHelpTxt = NULL;
  protected $pageInvoiceIdentifierLabel = NULL;
  protected $pageAddButtonLabel = NULL;
  /*
   * properties to hold the form labels and explanation
   */
  protected $formTitle = NULL;
  protected $formInvoiceIdentifierLabel = NULL;
  protected $formContactsLabel = NULL;
  protected $formSourceLabel = NULL;
  protected $formSourceDateFromLabel = NULL;
  protected $formSourceDateToLabel = NULL;
  protected $formContactsButtonLabel = NULL;
  protected $formFilterActivitiesLabel = NULL;
  protected $formActivitiesDateFromLabel = NULL;
  protected $formActivitiesDateToLabel = NULL;
  protected $formActivityTypesLabel = NULL;
  protected $formActListSelectLabel = NULL;
  protected $formActListActTypeLabel = NULL;
  protected $formActListSubjectLabel = NULL;
  protected $formActListTargetLabel = NULL;
  protected $formActListActDateLabel = NULL;
  protected $formActivitiesButtonLabel = NULL;

  /**
   * Constructor
   */
  function __construct() {
    $this->setPageLabels();
    $this->setFormLabels();
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
   * Function to get the formTitle
   *
   * @return string
   * @access public
   */
  public function getFormTitle() {
    return $this->formTitle;
  }

  /**
   * Function to get the formActivitiesButtonLabel
   *
   * @return string
   * @access public
   */
  public function getFormActivitiesButtonLabel() {
    return $this->formActivitiesButtonLabel;
  }

  /**
   * Function to get the formActListActDateLabel
   *
   * @return string
   * @access public
   */
  public function getFormActListDateLabel() {
    return $this->formActListActDateLabel;
  }

  /**
   * Function to get the formActListTargetLabel
   *
   * @return string
   * @access public
   */
  public function getFormActListTargetLabel() {
    return $this->formActListTargetLabel;
  }

  /**
   * Function to get the formActListSubjectLabel
   *
   * @return string
   * @access public
   */
  public function getFormActListSubjectLabel() {
    return $this->formActListSubjectLabel;
  }

  /**
   * Function to get the formActListActTypeLabel
   *
   * @return string
   * @access public
   */
  public function getFormActListActTypeLabel() {
    return $this->formActListActTypeLabel;
  }

  /**
   * Function to get the formActListSelectLabel
   *
   * @return string
   * @access public
   */
  public function getFormActListSelectLabel() {
    return $this->formActListSelectLabel;
  }

  /**
   * Function to get the formActivityTypesLabel
   *
   * @return string
   * @access public
   */
  public function getFormActivityTypesLabel() {
    return $this->formActivityTypesLabel;
  }

  /**
   * Function to get the formActivitiesDateToLabel
   *
   * @return string
   * @access public
   */
  public function getFormActivitiesDateToLabel() {
    return $this->formActivitiesDateToLabel;
  }

  /**
   * Function to get the formActivitiesDateFromLabel
   *
   * @return string
   * @access public
   */
  public function getFormActivitiesDateFromLabel() {
    return $this->formActivitiesDateFromLabel;
  }

  /**
   * Function to get the formFilterActivitiesLabel
   *
   * @return string
   * @access public
   */
  public function getFormFilterActivitiesLabel() {
    return $this->formFilterActivitiesLabel;
  }

  /**
   * Function to get the formContactsButtonLabel
   *
   * @return string
   * @access public
   */
  public function getFormContactsButtonLabel() {
    return $this->formContactsButtonLabel;
  }

  /**
   * Function to get the formSourceDateToLabel
   *
   * @return string
   * @access public
   */
  public function getFormSourceDateToLabel() {
    return $this->formSourceDateToLabel;
  }

  /**
   * Function to get the formSourceDateFromLabel
   *
   * @return string
   * @access public
   */
  public function getFormSourceDateFromLabel() {
    return $this->formSourceDateFromLabel;
  }

  /**
   * Function to get the formSourceLabel
   *
   * @return string
   * @access public
   */
  public function getFormSourceLabel() {
    return $this->formSourceLabel;
  }

  /**
   * Function to get the formContactsLabel
   *
   * @return string
   * @access public
   */
  public function getFormContactsLabel() {
    return $this->formContactsLabel;
  }

  /**
   * Function to get the formInvoiceIdentifierLabel
   *
   * @return string
   * @access public
   */
  public function getFormInvoiceIdentifierLabel() {
    return $this->formInvoiceIdentifierLabel;
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
   * Function to set the form labels for Invoice Add/Edit
   *
   * @access protected
   */
  protected function setFormLabels() {
    $this->formTitle = 'Cost Invoice';
    $this->formInvoiceIdentifierLabel = ts('Invoice Identifier');
    $this->formContactsLabel = ts('Contacts');
    $this->formSourceLabel = ts('Source');
    $this->formSourceDateFromLabel = ts('Source Date From');
    $this->formSourceDateToLabel = ts('Source Date To');
    $this->formContactsButtonLabel = ts('Process Contacts');
    $this->formFilterActivitiesLabel = ts('Filter Activities');
    $this->formActivitiesDateFromLabel = ts('Activity Date From');
    $this->formActivitiesDateToLabel = ts('Activity Date To');
    $this->formActivityTypesLabel = ts('Activity Type(s)');
    $this->formActListSelectLabel = ts('Select');
    $this->formActListActTypeLabel = ts('Activity Type');
    $this->formActListSubjectLabel = ts('Subject');
    $this->formActListTargetLabel = ts('Target(s)');
    $this->formActListActDateLabel = ts('Activity Date');
    $this->formActivitiesButtonLabel = ts('Process Selected Activities');
  }
}
