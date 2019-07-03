DROP TABLE `emails_addresses`;

CREATE TABLE `emails_addresses` (
 `id` int(250) UNSIGNED NOT NULL AUTO_INCREMENT,
 `email` varchar(200) NOT NULL DEFAULT '',
 `name` varchar(200) NOT NULL DEFAULT '',
 `emails-ids` tinytext,
 `uids` tinytext,
 `api-uid` int(11) UNSIGNED NOT NULL DEFAULT '0',
 `last-email-id` int(11) UNSIGNED NOT NULL DEFAULT '0',
 `addresses` int(11) UNSIGNED NOT NULL DEFAULT '0',
 `added` int(11) UNSIGNED NOT NULL DEFAULT '0',
 `removed` int(11) UNSIGNED NOT NULL DEFAULT '0',
 `expires` int(11) UNSIGNED NOT NULL DEFAULT '0',
 `created` int(11) UNSIGNED NOT NULL DEFAULT '0',
 `updated` int(11) UNSIGNED NOT NULL DEFAULT '0',
 PRIMARY KEY (`id`),
 KEY `email_addresses_idx` (`email`,`name`,`api-uid`,`last-email-id`),
 ENGINE=InnoDB DEFAULT CHARSET=utf8;

