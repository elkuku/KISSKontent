CREATE TABLE `#__kisskontent` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(500) DEFAULT NULL,
  `text` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__kisskontent_versions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(500) NOT NULL,
  `id_kiss` int(10) unsigned NOT NULL COMMENT 'Kontent ID',
  `text` text NOT NULL,
  `summary` varchar(500) NOT NULL,
  `id_user` tinyint(3) unsigned NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Modified date/time',
  `lang` varchar(5) NOT NULL COMMENT 'Language Tag',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__kiss_translations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key',
  `id_kiss` tinyint(3) unsigned NOT NULL COMMENT 'KISSKontent ID',
  `title` varchar(500) NOT NULL COMMENT 'Translation title',
  `text` text NOT NULL COMMENT 'Translation text',
  `lang` varchar(5) NOT NULL COMMENT 'Language tag',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Translations for KISSKontent';

