CREATE TABLE `#__kisskontent` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `title` varchar(500) DEFAULT NULL,
  `text` text,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE `#__kisskontent_versions` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `title` varchar(500) DEFAULT NULL,
  `text` text,
  `summary` varchar(500) NOT NULL,
  `id_user` tinyint(3) unsigned DEFAULT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;
