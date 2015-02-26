CREATE TABLE IF NOT EXISTS civicrm_maf_invoice_entity (
  id int(11) NOT NULL AUTO_INCREMENT,
  invoice_id INT DEFAULT NULL,
  entity VARCHAR(45) DEFAULT NULL,
  entity_id INT DEFAULT NULL,
  linked_date DATE DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY id_UNIQUE (id),
  KEY fk_invoice_idx (invoice_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
