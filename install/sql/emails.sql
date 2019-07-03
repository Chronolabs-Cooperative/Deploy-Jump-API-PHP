DROP TABLE `emails`;

CREATE TABLE `emails` (
 `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
 `domain-id` int(11) UNSIGNED NOT NULL DEFAULT '0',
 `jump-id` int(11) UNSIGNED NOT NULL DEFAULT '0',
 `alias` varchar(500) NOT NULL DEFAULT '',
 `uids` tinytext,
 `api-uid` int(11) UNSIGNED NOT NULL DEFAULT '0',
 `addresses` int(11) UNSIGNED NOT NULL DEFAULT '0',
 `added` int(11) UNSIGNED NOT NULL DEFAULT '0',
 `removed` int(11) UNSIGNED NOT NULL DEFAULT '0',
 `expires` int(11) UNSIGNED NOT NULL DEFAULT '0',
 `created` int(11) UNSIGNED NOT NULL DEFAULT '0',
 `updated` int(11) UNSIGNED NOT NULL DEFAULT '0',
 `deleted` int(11) UNSIGNED NOT NULL DEFAULT '0',
 PRIMARY KEY (`id`),
 KEY `emails_domain_jump_idx` (`domain-id`,`jump-id`,`api-uid`,`alias`),
 KEY `emails_alias_uid_idx` (`alias`,`domain-id`,`jump-id`,`api-uid`),
 ENGINE=InnoDB DEFAULT CHARSET=utf8;

