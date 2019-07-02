DROP TABLE `reports`;

CREATE TABLE `reports` (
  `id` mediumint(250) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(128) NOT NULL DEFAULT '',
  `modal` enum('hour','day','week','fortnight','month','quarter','year','other') DEFAULT 'other',
  `type` enum('domain','jump','director','tld','ip','keyword','other') DEFAULT 'other',
  PRIMARY KEY (`id`),
  KEY `search` (`id`,`key`,`modal`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

