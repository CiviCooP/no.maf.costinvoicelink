<?php

require_once 'CRM/Core/Form.php';

/**
 * Form controller class
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @license AGPL-3.0
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_Costinvoicelink_Form_ApplyActivities extends CRM_Core_Form {

  protected $activityTypes = NULL;
  protected $atFilter = NULL;
  protected $dfFilter = NULL;
  protected $dtFilter = NULL;
  protected $processFilter = FALSE;

  /**
   * Overridden parent method to buildQuickForm (call parent method too)
   */
  function buildQuickForm() {
    $this->addFormElements();

    parent::buildQuickForm();
  }

  /**
   * Overridden parent method to set default values
   * limit date range to avoid selecting masses of activities
   */
  function setDefaultValues() {
    $defaults = array();
    $defaults['invoice_id'] = CRM_Utils_Request::retrieve('iid', 'Positive');
    list($defaults['activityDateFrom']) = CRM_Utils_Date::setDateDefaults(date('Ymd', strtotime('first day of this month')));
    list($defaults['activityDateTo']) = CRM_Utils_Date::setDateDefaults(date("Ymd", strtotime("+1 month")));
    if ($this->processFilter == TRUE) {
      $selectedActivities = $this->selectActivities();
      $this->assign('mafActivities', $selectedActivities);
      if (!empty($this->atFilter)) {
        $defaults['activityTypeFilter'] = $this->atFilter;
      }
      if (!empty($this->dfFilter)) {
        list($defaults['activityDateFrom']) = CRM_Utils_Date::setDateDefaults($this->dfFilter);
      }
      if (!empty($this->dtFilter)) {
        list($defaults['activityDateTo']) = CRM_Utils_Date::setDateDefaults($this->dtFilter);
      }
    }
    return $defaults;
  }
  /**
   * Overridden parent method to initiate form
   */
  function preProcess() {
    if (isset($_REQUEST['at']) || isset($_REQUEST['df']) || isset($_REQUEST['dt'])) {
      $this->processFilter = TRUE;
    } else {
      $this->processFilter = FALSE;
    }
    $this->atFilter = CRM_Utils_Request::retrieve('at', 'Positive');
    $this->dfFilter = CRM_Utils_Request::retrieve('df', 'Date');
    $this->dtFilter = CRM_Utils_Request::retrieve('dt', 'Date');
    $this->getActivityTypes();
    $extensionConfig = CRM_Costinvoicelink_Config::singleton();
    $this->assign('selectLabel', $extensionConfig->getSelectLabel());
    $this->setActivityFormLabels();
  }

  /**
   * Function to set the activity form labels
   *
   * @access protected
   */
  protected function setActivityFormLabels() {
    if ($this->_action == CRM_Core_Action::ADD) {
      $actionLabel = 'Add';
    } else {
      $actionLabel = 'Edit';
    }
    $extensionConfig = CRM_Costinvoicelink_Config::singleton();
    $this->assign('actFormHeader', $actionLabel.' '.$extensionConfig->getActFormHeader());
    $this->assign('activityFilterLabel', $extensionConfig->getActFilterLabel());
    $this->assign('activityDateDateFromLabel', $extensionConfig->getActDateFromFilterLabel());
    $this->assign('activityDateToLabel', $extensionConfig->getActDateToFilterLabel());
    $this->assign('activitySearchButtonLabel', $extensionConfig->getActSearchButtonLabel());
    $this->assign('actListTypeLabel', $extensionConfig->getActListTypeLabel());
    $this->assign('actListSubjectLabel', $extensionConfig->getActListSubjectLabel());
    $this->assign('actListTargetLabel', $extensionConfig->getActListTargetsLabel());
    $this->assign('actListDateLabel', $extensionConfig->getActListDateLabel());
  }

  /**
   * Overridden parent method to process form (calls parent method too)
   */
  function postProcess() {
    if (isset($this->_submitValues['_qf_ApplyActivities_submit'])) {
      if ($this->_submitValues['_qf_ApplyActivities_submit'] == 'Search activities') {
        $this->processSearchActivities();
      }
      if ($this->_submitValues['_qf_ApplyActivities_submit'] == 'Apply to selected activities') {
        $this->processApplyActivities();
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
    if (isset($this->_submitValues['activityTypeFilter']) && !empty($this->_submitValues['activityTypeFilter'])) {
      $urlParams[] = 'at='.$this->_submitValues['activityTypeFilter'];
    }
    if (isset($this->_submitValues['activityDateFrom']) && !empty($this->_submitValues['activityDateFrom'])) {
      $urlParams[] = 'df='.date('Ymd', strtotime($this->_submitValues['activityDateFrom']));
    }
    if (isset($this->_submitValues['activityDateTo']) && !empty($this->_submitValues['activityDateTo'])) {
      $urlParams[] = 'dt='.date('Ymd', strtotime($this->_submitValues['activityDateTo']));
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
  protected function processSearchActivities() {
    if (empty($this->_submitValues['activityTypeFilter']) || empty($this->_submitValues['activityDateFrom']) || empty($this->_submitValues['activityDateTo'])) {
      $session = CRM_Core_Session::singleton();
      $session->setStatus('You have to select an activity type and an activity date range to search with', 'No activity type selected', 'error');
    }
    $paramString = $this->getFiltersUrlParams();
    CRM_Utils_System::redirect(CRM_Utils_System::url('civicrm/mafactivitiesapply', $paramString, true));
  }

  /**
   * Function to apply cost invoice to all selected activties
   *
   * @access protected
   */
  protected function processApplyActivities() {
    $session = CRM_Core_Session::singleton();
    if (!isset($this->_submitValues['selectedActivities']) || empty($this->_submitValues['selectedActivities'])) {
      $session->setStatus('No activities to apply the cost invoice to were selected', 'No activities selected', 'error');
      $paramString = $this->getFiltersUrlParams();
      CRM_Utils_System::redirect(CRM_Utils_System::url('civicrm/mafactivitiesapply', $paramString, true));
    } else {
      foreach ($this->_submitValues['selectedActivities'] as $selectedActivityId) {
        $this->linkCostInvoiceToActivity($selectedActivityId);
      }
      $session->setStatus('Cost Invoice applied to selected activities', 'Cost Invoice Applied', 'success');
      CRM_Utils_System::redirect($session->readUserContext());
    }
  }

  /**
   * Function to link invoice to activity
   *
   * @param int $activityId
   * @access protected
   */
  protected function linkCostInvoiceToActivity($activityId) {
    $params = array(
      'invoice_id' => $this->_submitValues['invoice_id'],
      'entity' => 'Activity',
      'entity_id' => $activityId);
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
    $this->add('select', 'activityTypeFilter', $extensionConfig->getActTypeFilterLabel(), $this->activityTypes, true);
    $this->addDate('activityDateFrom', $extensionConfig->getActDateFromFilterLabel(), false);
    $this->addDate('activityDateTo', $extensionConfig->getActDateToFilterLabel(), false);
    $this->addButtons(array(
      array('type' => 'submit', 'name' => $extensionConfig->getActFormApplyButtonLabel()),
      array('type' => 'cancel', 'name' => $extensionConfig->getCancelButtonLabel())));
  }

  /**
   * Function to get the activity types
   *
   * @access protected
   */
  protected function getActivityTypes() {
    $extensionConfig = CRM_Costinvoicelink_Config::singleton();
    $params = array(
      'option_group_id' => $extensionConfig->getActivityTypeOptionGroupId(),
      'options' => array('limit'=>0));
    try {
      $optionValues = civicrm_api3('OptionValue', 'Get', $params);
      foreach ($optionValues['values'] as $optionValue) {
        $this->activityTypes[$optionValue['value']] = $optionValue['label'];
      }
    } catch (CiviCRM_API3_Exception $ex) {
      $this->activityTypes = array();
    }
    $this->activityTypes[0] = '- select -';
    asort($this->activityTypes);
  }

  /**
   * Function to build activity query
   *
   * @return string $activityQuery
   * @access protected
   */
  protected function buildActivityQuery() {
    $activityQuery = 'SELECT id, subject, activity_date_time, activity_type_id FROM civicrm_activity';
    $activityWhere = $this->getActivityWhereClauses();
    return $activityQuery.' WHERE '.$activityWhere;
  }

  /**
   * Function to build activity where clauses
   *
   * @return string
   * @access protected
   */
  protected function getActivityWhereClauses() {
    $count = 1;
    $activityWhereClauses[] = 'is_current_revision = %'.$count;
    if (!empty($this->atFilter)) {
      $count++;
      $activityWhereClauses[] = 'activity_type_id = %'.$count;
    }
    if (!empty($this->dfFilter)) {
      $count++;
      $activityWhereClauses[] = 'activity_date_time >= %'.$count;
    }
    if (!empty($this->dtFilter)) {
      $count++;
      $activityWhereClauses[] = 'activity_date_time <= %'.$count;
    }
    return implode(' AND ', $activityWhereClauses);
  }

  /**
   * Function to build params for activity query
   *
   * @return array $queryParams
   * @access protected
   */
  protected function buildActivityQueryParams() {
    $count = 1;
    $queryParams = array();
    $queryParams[$count] = array(1, 'Integer');
    if (!empty($this->atFilter)) {
      $count++;
      $queryParams[$count] = array($this->atFilter, 'Integer');
    }
    if (!empty($this->dfFilter)) {
      $count++;
      $queryParams[$count] = array($this->dfFilter, 'Date');
    }
    if (!empty($this->dtFilter)) {
      $count++;
      $queryParams[$count] = array($this->dtFilter, 'Date');
    }
    return $queryParams;
  }

  /**
   * Function to select activities
   *
   * @return array $activities
   */
  protected function selectActivities() {
    $activities = array();
    $activityQuery = $this->buildActivityQuery();
    $activityParams = $this->buildActivityQueryParams();
    $daoActivity = CRM_Core_DAO::executeQuery($activityQuery, $activityParams);
    while ($daoActivity->fetch()) {
      $activities[$daoActivity->id] = $this->createActivityRow($daoActivity);
    }
    return $activities;
  }

  /**
   * Function to create a row for an activity
   *
   * @param object $daoActivity
   * @return array $row
   * @access protected
   */
  protected function createActivityRow($daoActivity) {
    $extensionConfig = CRM_Costinvoicelink_Config::singleton();
    $row = array();
    $row['activity_type'] = CRM_Costinvoicelink_Utils::getOptionValueLabel($daoActivity->activity_type_id,
      $extensionConfig->getActivityTypeOptionGroupId());
    $row['subject'] = $daoActivity->subject;
    $row['targets'] = $this->getActivityTargets($daoActivity->id);
    $row['activity_date'] = $daoActivity->activity_date_time;
    return $row;
  }

  /**
   * Function to get target contact names in string
   *
   * @param int $activityId
   * @return string $targetString
   * @access protected
   *
   */
  protected function getActivityTargets($activityId) {
    $targetString = '';
    $targetContactNames = array();
    $targetCount = 0;
    $targetQuery = 'SELECT contact_id FROM civicrm_activity_contact WHERE activity_id = %1 AND record_type_id = %2';
    $targetParams = array(
      1 => array($activityId, 'Integer'),
      2 => array(3, 'Integer'));
    $daoTargets = CRM_Core_DAO::executeQuery($targetQuery, $targetParams);
    while ($daoTargets->fetch()) {
      $targetCount++;
      $targetContactNames[] = CRM_Costinvoicelink_Utils::getContactName($daoTargets->contact_id);
      if ($targetCount > 3) {
        $targetString = implode('; ', $targetContactNames).' and more';
        return $targetString;
      }
    }
    if (!empty($targetContactNames)) {
      $targetString = implode('; ', $targetContactNames);
    }
    return $targetString;
  }
}
