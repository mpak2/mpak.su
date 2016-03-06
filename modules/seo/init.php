<? # modules/seo/init.php

if(mpsettings($t = "seo_cat", "Категория") && ($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `href` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `alias` (`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251");
} if(mpsettings($t = "seo_characters", "Символы") && ($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sort` int(11) NOT NULL,
  `characters_lang_id` int(11) NOT NULL,
  `from` varchar(255) NOT NULL,
  `to` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251");
} if(mpsettings($t = "seo_characters_lang", "Язык") && ($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
} if(mpsettings($t = "seo_index", "Внешние") && ($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `index_type_id` int(11) NOT NULL DEFAULT '1',
  `priority` float NOT NULL DEFAULT '0.8' COMMENT 'Приоритет ссылки в sitemap.xml',
  `name` varchar(255) NOT NULL,
  `cat_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_2` (`name`),
  KEY `uid` (`uid`),
  KEY `cat_id` (`cat_id`),
  KEY `name` (`name`),
  KEY `index_type_id` (`index_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251");
} if(mpsettings($t = "seo_index_type", "Типы") && ($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251");
} if(mpsettings($t = "seo_location", "Внутренние") && ($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `location_status_id` int(11) NOT NULL DEFAULT '301',
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_2` (`name`),
  KEY `name` (`name`),
  KEY `uid` (`uid`),
  KEY `cat_id` (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251");
} if(mpsettings($t = "seo_location_status", "Статус") && ($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251");
} if(mpsettings($t = "seo_test", "Проверка") && ($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
} if(mpsettings($t = "seo_index_themes", "Адресация") && ($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `index_id` int(11) DEFAULT NULL,
  `location_id` int(11) NOT NULL,
  `themes_index` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `themes_index` (`themes_index`),
  KEY `location_id` (`location_id`),
  KEY `index_id` (`index_id`),
  CONSTRAINT `mp_seo_index_themes_ibfk_1` FOREIGN KEY (`index_id`) REFERENCES `mp_seo_index` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mp_seo_index_themes_ibfk_2` FOREIGN KEY (`location_id`) REFERENCES `mp_seo_location` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=cp1251");
} if(mpsettings($t = "seo_location_themes", "Перенаправления") && ($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `index_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `themes_index` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `location_id` (`location_id`),
  KEY `themes_index` (`themes_index`),
  KEY `index_id` (`index_id`),
  KEY `uid` (`uid`),
  CONSTRAINT `mp_seo_location_themes_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `mp_seo_location` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mp_seo_location_themes_ibfk_2` FOREIGN KEY (`index_id`) REFERENCES `mp_seo_index` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=cp1251");
} 

mpsettings("{$arg['modpath']}_index_themes=>title", "time,uid,index_id,location_id,themes_index,title");
mpsettings("{$arg['modpath']}_index=>order", "id DESC");
mpsettings("{$arg['modpath']}_meta", "modules/seo/admin_meta.php");
mpsettings("{$arg['modpath']}_cat=>order", "id DESC");
mpsettings("{$arg['modpath']}_location=>order", "id DESC");
