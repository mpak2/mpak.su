<?

if(mpsettings($t = "themes_index_events", "События") && !tables($table = ("{$conf['db']['prefix']}{$t}"))){
	qw("CREATE TABLE IF NOT EXISTS `{$table}` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`time` int(11) NOT NULL,
	`uid` int(11) NOT NULL,
	`users_event` int(11) NOT NULL COMMENT 'Событие системы',
	`name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
	PRIMARY KEY (`id`),
	KEY `users_event` (`users_event`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
}
