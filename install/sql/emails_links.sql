DROP TABLE `emails_links`;

CREATE TABLE `emails_links` (
 `email-address-id` int(250) UNSIGNED NOT NULL DEFAULT '0',
 `email` int(11) UNSIGNED NOT NULL DEFAULT '0',
 `configured` int(11) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

