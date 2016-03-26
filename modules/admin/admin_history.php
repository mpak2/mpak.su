<?

if(mpsettings($t = "admin_history", "История") && ($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `history_tables_id` int(11) NOT NULL,
  `history_type_id` int(11) NOT NULL,
  `name` int(11) NOT NULL,
  `diff` text NOT NULL COMMENT 'Разница между массивами',
  `data` text NOT NULL COMMENT 'Данные до сохранения',
  PRIMARY KEY (`id`),
  KEY `history_tables_id` (`history_tables_id`),
  KEY `history_type_id` (`history_type_id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251");
} if(mpsettings($t = "admin_history_tables", "Таблицы") && ($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modpath` varchar(255) NOT NULL,
  `modname` varchar(255) NOT NULL,
  `fn` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `modname` (`modpath`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251");
} if(mpsettings($t = "admin_history_type", "Тип") && ($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251");
} if(mpsettings($t = "admin_search", "Поиск") && ($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251");
}

mpsettings("{$arg['modpath']}_history_log", "1");
mpsettings("{$arg['modpath']}_history=>order", "id DESC");
