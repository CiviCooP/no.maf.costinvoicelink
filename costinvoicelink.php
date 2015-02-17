<?php

require_once 'costinvoicelink.civix.php';

/**
 * Implementation of hook civicrm_navigationMenu
 *
 * @param array $params
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 */
function costinvoicelink_civicrm_navigationMenu(&$params) {
  $maxKey = _costinvoicelink_getMaxMenuKey($params);
  $menuParentId = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_Navigation', 'Contributions', 'id', 'name');
  $params[$menuParentId]['child'][$maxKey+1] = array (
    'attributes' => array (
      'label'      => ts('Cost Invoices'),
      'name'       => ts('Cost Invoices'),
      'url'        => CRM_Utils_System::url('civicrm/mafinvoicelist', 'reset=1', true),
      'permission' => 'access CiviContribute',
      'operator'   => null,
      'separator'  => null,
      'parentID'   => $menuParentId,
      'navID'      => $maxKey+1,
      'active'     => 1
    ));
}

/**
 * Implementation of hook_civicrm_config
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function costinvoicelink_civicrm_config(&$config) {
  _costinvoicelink_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_xmlMenu
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function costinvoicelink_civicrm_xmlMenu(&$files) {
  _costinvoicelink_civix_civicrm_xmlMenu($files);
}

/**
 * Implementation of hook_civicrm_install
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function costinvoicelink_civicrm_install() {
  _costinvoicelink_civix_civicrm_install();
}

/**
 * Implementation of hook_civicrm_uninstall
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function costinvoicelink_civicrm_uninstall() {
  _costinvoicelink_civix_civicrm_uninstall();
}

/**
 * Implementation of hook_civicrm_enable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function costinvoicelink_civicrm_enable() {
  _costinvoicelink_civix_civicrm_enable();
}

/**
 * Implementation of hook_civicrm_disable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function costinvoicelink_civicrm_disable() {
  _costinvoicelink_civix_civicrm_disable();
}

/**
 * Implementation of hook_civicrm_upgrade
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed  based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function costinvoicelink_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _costinvoicelink_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implementation of hook_civicrm_managed
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function costinvoicelink_civicrm_managed(&$entities) {
  _costinvoicelink_civix_civicrm_managed($entities);
}

/**
 * Implementation of hook_civicrm_caseTypes
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function costinvoicelink_civicrm_caseTypes(&$caseTypes) {
  _costinvoicelink_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implementation of hook_civicrm_alterSettingsFolders
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function costinvoicelink_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _costinvoicelink_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Function to determine max key in navigation menu (core solutions do not cater for child keys!)
 *
 * @param array $menuItems
 * @return int $maxKey
 */
function _costinvoicelink_getMaxMenuKey($menuItems) {
  $maxKey = 0;
  foreach ($menuItems as $menuKey => $menuItem) {
    if ($menuKey > $maxKey) {
      $maxKey = $menuKey;
    }
    if (isset($menuItem['child'])) {
      foreach ($menuItem['child'] as $childKey => $child) {
        if ($childKey > $maxKey) {
          $maxKey = $childKey;
        }
      }
    }
  }
  return $maxKey;
}
