DROP TABLE `locals_places`;

CREATE TABLE `locals_places` (
  `id` mediumint(250) UNSIGNED NOT NULL AUTO_INCREMENT,
  `country-id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `state-id` int(128) UNSIGNED NOT NULL DEFAULT '0',
  `suburb-id` int(128) UNSIGNED NOT NULL DEFAULT '0',
  `place` varchar(200) NOT NULL  DEFAULT '',
  `address` tinyint,
  `places-key` varchar(64) NOT NULL  DEFAULT '',
  `2km-nearby-places-key` tinytext,
  `5km-nearby-places-key` tinytext,
  `10km-nearby-places-key` tinytext,
  `25km-nearby-places-key` tinytext,
  `2km-exactly-places-key` tinytext,
  `5km-exactly-places-key` tinytext,
  `10km-exactly-places-key` tinytext,
  `25km-exactly-places-key` tinytext,  
  `longitude` decimal(16,12) NOT NULL  DEFAULT '0.000000000000',
  `latitude` decimal(16,12) NOT NULL  DEFAULT '0.000000000000',
  `hour-start` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `hour-ended` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `day-start` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `day-ended` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `week-start` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `week-ended` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `fortnight-start` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `fortnight-ended` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `month-start` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `month-ended` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `quarter-start` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `quarter-ended` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `year-start` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `year-ended` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `hits-total` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `hits-last` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `hits-previous` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `hits-oftern` float(21,10) UNSIGNED NOT NULL DEFAULT '0',
  `hits-hour` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `hits-day` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `hits-week` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `hits-fortnight` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `hits-month` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `hits-quarter` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `hits-year` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `created` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `updated` int(11) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `places_index` (`state-id`,`country-id`,`suburb-id`,`place`),
  KEY `places_search` (`longitude`,`latitude`,`places-key`),
  KEY `places_statistician_idx` (`hour-start`,`hour-ended`,`week-start`,`week-ended`,`fortnight-start`,`fortnight-ended`,`month-start`,`month-ended`,`quarter-start`,`quarter-ended`,`year-start`,`year-ended`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
