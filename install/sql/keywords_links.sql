DROP TABLE `keywords_links`;

CREATE TABLE `keywords_links` (
  `id` mediumint(250) UNSIGNED NOT NULL AUTO_INCREMENT,
  `keyword-id` mediumint(250) UNSIGNED NOT NULL DEFAULT '0',
  `type` enum('domain','jump','director','tld','ip','other') DEFAULT 'other',
  `type-id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `domains` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `last-domains-id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `jumps` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `last-jumps-id` int(11) UNSIGNED NOT NULL DEFAULT '0',
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
   PRIMARY KEY (`id`),
   KEY `search` (`created`,`updated`,`keyword-id`,`type`,`type-id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

