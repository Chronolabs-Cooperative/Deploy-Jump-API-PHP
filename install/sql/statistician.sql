DROP TABLE `statistician`;

CREATE TABLE `statistician` (
  `id` mediumint(199) UNSIGNED NOT NULL AUTO_INCREMENT,
  `modal` enum('hour','day','week','fortnight','month','quarter','year','other') DEFAULT 'other',
  `type` enum('domain','jump','director','tld','ip','keyword','other') DEFAULT 'other',
  `type-id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `weight` int(69) UNSIGNED NOT NULL DEFAULT '0',
  `year` int(4) UNSIGNED NOT NULL DEFAULT '2019',
  `quarter` int(2) UNSIGNED NOT NULL DEFAULT '1',
  `month` int(2) UNSIGNED NOT NULL DEFAULT '7',
  `fortnight` int(2) UNSIGNED NOT NULL DEFAULT '1',
  `week` int(2) UNSIGNED NOT NULL DEFAULT '1',
  `day` int(2) UNSIGNED NOT NULL DEFAULT '1',
  `dayname` enum('Mon','Tue','Wed','Thu','Fri','Sat','Sun') DEFAULT 'Sun',
  `hour` int(2) UNSIGNED NOT NULL DEFAULT '0',
  `previous-modal` enum('hour','day','week','fortnight','month','quarter','year','other') DEFAULT 'other',
  `previous-scope` enum('day','week','fortnight','month','quarter','year','biannual','other') DEFAULT 'other',
  `previous-seconds` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `previous-start` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `previous-end` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `previous-value` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `previous-average` float(21,10) UNSIGNED NOT NULL DEFAULT '0',
  `previous-stdev` float(21,10) UNSIGNED NOT NULL DEFAULT '0',
  `previous-difference` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `previous-difference-average` float(21,10) UNSIGNED NOT NULL DEFAULT '0',
  `previous-difference-stdev` float(21,10) UNSIGNED NOT NULL DEFAULT '0',
  `previous-pip` int(11) NOT NULL DEFAULT '0',
  `previous-pip-last` int(11) NOT NULL DEFAULT '0',
  `previous-pip-motion` enum('up-above','down-above','up-miniscule','down-miniscule','up-average','up-stdev','up-equal','down-average','down-stdev','down-equal') DEFAULT 'equal',
  `scope-modal` enum('day','week','fortnight','month','quarter','year','biannual','other') DEFAULT 'other',
  `scope-seconds` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `scope-start` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `scope-end` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `scope-value` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `scope-average` float(21,10) UNSIGNED NOT NULL DEFAULT '0',
  `scope-stdev` float(21,10) UNSIGNED NOT NULL DEFAULT '0',
  `scope-difference` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `scope-difference-average` float(21,10) UNSIGNED NOT NULL DEFAULT '0',
  `scope-difference-stdev` float(21,10) UNSIGNED NOT NULL DEFAULT '0',
  `scope-pip` int(11) NOT NULL DEFAULT '0',
  `scope-pip-last` int(11) NOT NULL DEFAULT '0',
  `scope-pip-motion` enum('up-above','down-above','up-miniscule','down-miniscule','up-average','up-stdev','up-equal','down-average','down-stdev','down-equal') DEFAULT 'equal',
  `start` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `end` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `seconds` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `value` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `average` float(21,10) UNSIGNED NOT NULL DEFAULT '0',
  `stdev` float(21,10) UNSIGNED NOT NULL DEFAULT '0',
  `difference` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `pip` int(11) NOT NULL DEFAULT '0',
  `pip-last` int(11) NOT NULL DEFAULT '0',
  `pip-motion` enum('up-above','down-above','up-miniscule','down-miniscule','up-average','up-stdev','up-equal','down-average','down-stdev','down-equal') DEFAULT 'equal',
  `reports` longblob,
  `expires` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `created` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `updated` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `reported` int(11) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `statistician_index` (`modal`,`type`,`type-id`,`weight`),
  KEY `statistician_chronologistics_index` (`year`,`quarter`,`month`,`fortnight`,`day`,`dayname`,`hour`,`previous-start`,`previous-end`,`scope-start`,`scope-end`,`start`,`end`,`expires`,`created`,`updated`,`reported`),
  KEY `statistician_type_index` (`type`,`type-id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

