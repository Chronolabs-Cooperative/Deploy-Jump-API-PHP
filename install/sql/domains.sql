DROP TABLE `domains`;

CREATE TABLE `domains` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `domain` varchar(255) NOT NULL  DEFAULT '',,
  `admin-email` varchar(255) NOT NULL DEFAULT '',
  `ssl` enum('Yes', 'No') NOT NULL DEFAULT 'No',
  `root-ssl-csr-file` varchar(255) NOT NULL DEFAULT '',
  `root-ssl-certificate-file` varchar(255) NOT NULL DEFAULT '',
  `root-ssl-certificate-key-file` varchar(255) NOT NULL DEFAULT '',
  `root-ssl-ca-certificate-file` varchar(255) NOT NULL DEFAULT '/etc/ssl/certs/ca-certificates.crt',
  `subs-ssl-csr-file` varchar(255) NOT NULL DEFAULT '',
  `subs-ssl-certificate-file` varchar(255) NOT NULL DEFAULT '',
  `subs-ssl-certificate-key-file` varchar(255) NOT NULL DEFAULT '',
  `subs-ssl-ca-certificate-file` varchar(255) NOT NULL DEFAULT '/etc/ssl/certs/ca-certificates.crt',
  `apache2-config-file` varchar(255) NOT NULL DEFAULT '%apipath%/include/data/apache2-config.conf.txt',
  `apache2-config-ssl-file` varchar(255) NOT NULL DEFAULT '%apipath%/include/data/apache2-config-ssl.conf.txt',
  `awstats-config-file` varchar(255) NOT NULL DEFAULT '%apipath%/include/data/awstats-config.conf.txt',
  `callback-hit` varchar(200) NOT NULL DEFAULT '',
  `callback-stats` varchar(200) NOT NULL DEFAULT '',
  `callback-jumps` varchar(200) NOT NULL DEFAULT '',
  `callback-directors` varchar(200) NOT NULL DEFAULT '',
  `callback-reports` varchar(200) NOT NULL DEFAULT '',
  `http-port` int(11) UNSIGNED NOT NULL DEFAULT '80',
  `https-port` int(11) UNSIGNED NOT NULL DEFAULT '443',
  `uids` tinytext,
  `api-uid` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `jumps` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `directors` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `active-directors` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `expire-directors` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `last-jumpid` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `last-directorid` int(11) UNSIGNED NOT NULL DEFAULT '0',
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
  `deleted` int(11) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `domain_index` (`domain`),
  KEY `domains_search` (`domain`,`admin-email`,`uid`),
  KEY `domains_chronologistics_idx` (`last`,`created`,`updated`,`deleted`),
  KEY `domains_statistician_idx` (`hour-start`,`hour-ended`,`week-start`,`week-ended`,`fortnight-start`,`fortnight-ended`,`month-start`,`month-ended`),`quarter-start`,`quarter-ended`,`year-start`,`year-ended`
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

