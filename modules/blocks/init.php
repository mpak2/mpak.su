<? # modules/blocks/init.php

if(mpsettings($t = "blocks_index", "Блоки") && !tables($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `src` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `theme` varchar(255) NOT NULL,
  `shablon` varchar(255) NOT NULL,
  `access` smallint(6) NOT NULL,
  `pol` smallint(6) NOT NULL,
  `reg_id` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `param` text NOT NULL,
  `hide` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `hide` (`hide`),
  KEY `theme` (`theme`),
  KEY `sort` (`sort`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251");
} if(mpsettings($t = "blocks_index_gaccess", "Группы") && !tables($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `index_id` int(11) NOT NULL,
  `gid` int(11) NOT NULL,
  `access` smallint(6) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251");
} if(mpsettings($t = "blocks_index_uaccess", "Пользователи") && !tables($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `index_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `access` smallint(6) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251");
} if(mpsettings($t = "blocks_reg", "Регионы") && !tables($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sort` int(11) NOT NULL,
  `term` int(11) NOT NULL,
  `reg_id` int(11) NOT NULL,
  `mid` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `fn` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mid` (`mid`),
  KEY `reg_id` (`reg_id`),
  KEY `fn` (`fn`),
  KEY `term` (`term`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251");
} if(mpsettings($t = "blocks_reg_modules", "Модули") && !tables($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sort` int(11) NOT NULL,
  `reg_id` int(11) NOT NULL,
  `modules_index` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `theme` varchar(255) NOT NULL,
  `uri` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sort` (`sort`),
  KEY `modules_index` (`modules_index`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251");
} if(mpsettings($t = "blocks_shablon", null) && !tables($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `shablon` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251");
}
