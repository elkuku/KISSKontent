CREATE TABLE `#__kukukontent_versions` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `title` varchar(500) DEFAULT NULL,
  `text` text,
  `id_user` tinyint(3) unsigned DEFAULT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;
