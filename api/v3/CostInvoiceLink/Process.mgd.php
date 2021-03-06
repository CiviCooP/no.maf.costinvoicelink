<?php
// This file declares a managed database record of type "Job".
// The record will be automatically inserted, updated, or deleted from the
// database as appropriate. For more details, see "hook_civicrm_managed" at:
// http://wiki.civicrm.org/confluence/display/CRMDOC42/Hook+Reference
return array (
  0 => 
  array (
    'name' => 'Cron:CostInvoiceLink.Process',
    'entity' => 'Job',
    'params' => 
    array (
      'version' => 3,
      'name' => 'Process Cost Invoice Link MAF Norge',
      'description' => 'Link cost invoices to contacts and activities',
      'run_frequency' => 'Hourly',
      'api_entity' => 'CostInvoiceLink',
      'api_action' => 'Process',
      'parameters' => '',
      'is_active' => 1
    ),
  ),
);