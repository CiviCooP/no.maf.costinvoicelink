CREATE TABLE IF NOT EXISTS civicrm_maf_invoice (
  id int(11) NOT NULL AUTO_INCREMENT,
  external_id varchar(45) DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY id_UNIQUE (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
