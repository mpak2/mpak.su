<? # modules/cron/init.php

if(mpsettings($t = "cron_index", "Cron") && !tables($table = ("{$conf['db']['prefix']}{$t}"))){
	qw("CREATE TABLE `mp_cron_index` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	  `cron` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	  `path` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	  `hide` int(11) DEFAULT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8");
} 
if(mpsettings($t = "cron_log", "Лог") && !tables($table = ("{$conf['db']['prefix']}{$t}"))){
	qw("CREATE TABLE `{$table}` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `time` int(11) DEFAULT NULL,
	  `value` text,
	  `index_id` int(11) DEFAULT NULL,
	  PRIMARY KEY (`id`),
	  KEY `index_id` (`index_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8");
} 
if(mpsettings($t = "cron_settings", "Настройки") && !tables($table = ("{$conf['db']['prefix']}{$t}"))){
	qw("CREATE TABLE `{$table}` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `name` varchar(255) DEFAULT NULL,
	  `value` varchar(255) DEFAULT NULL,
	  `description` varchar(255) DEFAULT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8");
} 

mpsettings("{$arg['modpath']}_admin_index", "Cron");
mpsettings("{$arg['modpath']}_tpl_exceptions", "1");