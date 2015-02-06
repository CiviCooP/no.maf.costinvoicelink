CREATE TABLE IF NOT EXISTS civicrm_maf_entity_invoice (
  id int(11) NOT NULL AUTO_INCREMENT,
  invoice_id INT DEFAULT NULL,
  entity VARCHAR(45) DEFAULT NULL,
  entity_id INT DEFAULT NULL,
  linked_date DATE DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY id_UNIQUE (id),
  KEY fk_invoice_idx (invoice_id),
  KEY entity_idx (entity, entity_id),
  CONSTRAINT fk_invoice FOREIGN KEY (invoice_id)
  REFERENCES civicrm_maf_entity_invoice(id)
  ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
