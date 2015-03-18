CREATE TABLE IF NOT EXISTS civicrm_maf_invoice_activity_subject (
  id int(11) NOT NULL AUTO_INCREMENT,
  invoice_id INT DEFAULT NULL,
  activity_subject VARCHAR(128) DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY id_UNIQUE (id),
  KEY fk_maf_invoice_idx (invoice_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
