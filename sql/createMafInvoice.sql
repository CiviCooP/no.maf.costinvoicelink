CREATE TABLE IF NOT EXISTS civicrm_maf_invoice (
  id int(11) NOT NULL AUTO_INCREMENT,
  external_id VARCHAR(45) DEFAULT NULL,
  contact_source VARCHAR(128) DEFAULT NULL,
  contact_from_date DATE DEFAULT NULL,
  contact_to_date DATE DEFAULT NULL,
  activity_type_id INT DEFAULT NULL,
  activity_from_date DATE DEFAULT NULL,
  activity_to_date DATE DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY id_UNIQUE (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
