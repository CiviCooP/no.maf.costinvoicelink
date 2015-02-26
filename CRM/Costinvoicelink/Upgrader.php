<?php

/**
 * Collection of upgrade steps
 */
class CRM_Costinvoicelink_Upgrader extends CRM_Costinvoicelink_Upgrader_Base
{

  /**
   * Run an SQL scripts to generate tables when the extension is installed
   */
  public function install()
  {
    $this->executeSqlFile('sql/createMafInvoice.sql');
    $this->executeSqlFile('sql/createMafInvoiceEntity.sql');
  }
}