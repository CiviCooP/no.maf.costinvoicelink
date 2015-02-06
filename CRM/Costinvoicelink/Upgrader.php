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
    $this->executeSqlFile('sql/createMafEntityInvoice.sql');
  }

  public function upgrade_4200()
  {
    $this->ctx->log->info('Applying update 4200');
    CRM_Core_DAO::executeQuery('UPDATE foo SET bar = "whiz"');
    CRM_Core_DAO::executeQuery('DELETE FROM bang WHERE willy = wonka(2)');
    return TRUE;
  } // */


  /**
   * Example: Run an external SQL script
   * @return TRUE on success
   * @throws Exception
  public function upgrade_4201() {
   * $this->ctx->log->info('Applying update 4201');
   * // this path is relative to the extension base dir
   * $this->executeSqlFile('sql/upgrade_4201.sql');
   * return TRUE;
   * } // */
}