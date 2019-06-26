DROP TABLE `jumps`;

CREATE TABLE `jumps` (
 `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
 `domain-id` int(11) UNSIGNED NOT NULL DEFAULT '0',
 `sub-domain` varchar(64) NOT NULL DEFAULT '',
 `hostname` varchar(200) NOT NULL DEFAULT '',
 `focus-mining` int(11) UNSIGNED NOT NULL DEFAULT '0',
 `focus-ip-id` int(11) UNSIGNED NOT NULL DEFAULT '0',
 `focus-tld-id` int(11) UNSIGNED NOT NULL DEFAULT '0',
 `focus-url` varchar(500) NOT NULL DEFAULT '',
 `focus-title` varchar(200) NOT NULL DEFAULT '',
 `focus-sitename` varchar(200) NOT NULL DEFAULT '',
 `focus-decription` tinytext,
 `focus-keywords` tinytext,
 `focus-feed` varchar(200) NOT NULL DEFAULT '',
 `focus-image` varchar(200) NOT NULL DEFAULT '',
 `focus-icon` varchar(200) NOT NULL DEFAULT '',
 `focus-mimetype` varchar(100) NOT NULL DEFAULT '',
 `focus-email` varchar(200) NOT NULL DEFAULT '',
 `focus-generator` varchar(200) NOT NULL DEFAULT '',
 `apache2-path` varchar(64) NOT NULL DEFAULT '/var/www/%hostname%',
 `apache2-access-log` varchar(64) NOT NULL DEFAULT '%hostname%-access.log',
 `apache2-error-log` varchar(64) NOT NULL DEFAULT '%hostname%-error.log',
 `apache2-configured` int(11) UNSIGNED NOT NULL DEFAULT '0',
 `apache2-ssl-configured` int(11) UNSIGNED NOT NULL DEFAULT '0',
 `awstats-configured` int(11) UNSIGNED NOT NULL DEFAULT '0',
 `github-cloned` int(11) UNSIGNED NOT NULL DEFAULT '0',
 `github-pulled` int(11) UNSIGNED NOT NULL DEFAULT '0',
 `callback-hit` varchar(200) NOT NULL DEFAULT '',
 `callback-stats` varchar(200) NOT NULL DEFAULT '',
 `callback-directors` varchar(200) NOT NULL DEFAULT '',
 `uid` int(11) UNSIGNED NOT NULL DEFAULT '0',
 `directors` int(11) UNSIGNED NOT NULL DEFAULT '0',
 `last-director-id` int(11) UNSIGNED NOT NULL DEFAULT '0',
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
 KEY `jumps_domain_id_idx` (`domain-id`),
 KEY `jumps_domain_uid_idx` (`sub-domain`,`uid`),
 KEY `jumps_chronologistics_idx` (`apache2-configured`,`apache2-ssl-configured`,`awstats-configured`,`created`,`updated`,`deleted`)
 KEY `jumps_statistician_idx` (`hour-start`,`hour-ended`,`week-start`,`week-ended`,`fortnight-start`,`fortnight-ended`,`month-start`,`month-ended`),`quarter-start`,`quarter-ended`,`year-start`,`year-ended`
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
