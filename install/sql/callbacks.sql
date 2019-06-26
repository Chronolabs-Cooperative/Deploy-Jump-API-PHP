DROP TABLE `callbacks`;

CREATE TABLE `callbacks` (
  `id` mediumint(250) UNSIGNED NOT NULL AUTO_INCREMENT,
  `modal` enum('report','hour','day','week','fortnight','month','quarter','year','other') DEFAULT 'other',
  `type` enum('hit','domain','jump','director','tld','ip','keyword','other') DEFAULT 'other',
  `type-id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `url` varchar(300) NOT NULL DEFAULT '',
  `post` longblob,
  `created` int(11) UNSIGNED DEFAULT '0',
  `called` int(11) UNSIGNED DEFAULT '0',
  `response` int(4) UNSIGNED DEFAULT '200',
  PRIMARY KEY (`id`),
  KEY `search` (`created`,`called`,`response`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

