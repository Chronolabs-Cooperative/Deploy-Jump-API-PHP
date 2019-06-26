DROP TABLE `keywords`;

CREATE TABLE `keywords` (
  `id` mediumint(128) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ids` longblob,
  `keyword` varchar(128) NOT NULL,
  `weight` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `occurences` int(11) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `search` (`keyword`,`weight`,`occurences`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

