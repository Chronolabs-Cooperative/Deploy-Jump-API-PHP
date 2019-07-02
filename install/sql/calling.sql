DROP TABLE `calling`;

CREATE TABLE `calling` (
  `id` mediumint(250) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(128) NOT NULL DEFAULT '',
  `modal` enum('new','delete','other') DEFAULT 'new',
  `api-uid` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `uid` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `created` int(11) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `search` (`id`,`key`,`modal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

