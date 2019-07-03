DROP TABLE `ips`;

CREATE TABLE `ips` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `modal` enum('jump','director','focus','other') NOT NULL DEFAULT 'other',
  `local-id` mediumint(128) NOT NULL DEFAULT '0',
  `ipv4` varchar(32) NOT NULL  DEFAULT '',
  `ipv6` varchar(128) NOT NULL  DEFAULT '',
  `netbios` varchar(250) NOT NULL  DEFAULT '',
  `uids` tinytext,
  `api-uid` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `used-domains` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `used-jumps` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `used-directors` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `used-domain-ids` mediumblob,
  `used-jump-ids` mediumblob,
  `used-director-ids` mediumblob,
  `hits-last-domainid` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `hits-last-jumpid` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `hits-last-directorid` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `create-last-domainid` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `create-last-jumpid` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `create-last-directorid` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `data-last-domainid` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `data-last-jumpid` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `data-last-directorid` int(11) UNSIGNED NOT NULL DEFAULT '0',
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
  `create-total` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `create-last` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `create-previous` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `create-oftern` float(21,10) UNSIGNED NOT NULL DEFAULT '0',
  `create-hour` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `create-day` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `create-week` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `create-fortnight` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `create-month` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `create-quarter` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `create-year` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `data-total` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `data-last` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `data-previous` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `data-oftern` float(21,10) UNSIGNED NOT NULL DEFAULT '0',
  `data-hour` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `data-day` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `data-week` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `data-fortnight` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `data-month` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `data-quarter` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `data-year` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `created` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `updated` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `blacklisted` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `whitelisted` int(11) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ips_index` (`ipv4`,`ipv6`,`netbios`),
  KEY `ips_search` (`ipv4`,`ipv6`,`netbios`,`blacklisted`,`whitelisted`),
  KEY `ips_chronologistics_idx` (`hits-last`,`hits-previous`,`create-last`,`create-previous`,`data-last`,`data-previous`,`created`,`updated`,`blacklisted`,`whitelisted`),
  KEY `ips_statistician_idx` (`hour-start`,`hour-ended`,`week-start`,`week-ended`,`fortnight-start`,`fortnight-ended`,`month-start`,`month-ended`,`quarter-start`,`quarter-ended`,`year-start`,`year-ended`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
