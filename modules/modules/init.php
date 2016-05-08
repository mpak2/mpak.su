<? # modules/modules/init.php

if(mpsettings($t = "modules_index", "Модули") && !tables($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `folder` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `version` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `enabled` smallint(6) DEFAULT NULL,
  `access` smallint(6) NOT NULL,
  `admin` int(11) NOT NULL,
  `md5` varchar(255) NOT NULL,
  `priority` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `enabled` (`enabled`),
  KEY `priority` (`priority`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251");
} if(mpsettings($t = "modules_index_gaccess", "Группы") && !tables($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL,
  `gid` int(11) NOT NULL,
  `access` smallint(6) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251");
} if(mpsettings($t = "modules_index_uaccess", "Пользователи") && !tables($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `access` smallint(6) NOT NULL DEFAULT '5',
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251");
} 

mpsettings("{$arg['modpath']}_stop", "<!-- Окончание: {path} -->");
mpsettings("{$arg['modpath']}_start", "<!-- Начало: {path} -->");
