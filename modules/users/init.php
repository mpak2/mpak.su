<? die;

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `refer` int(11) NOT NULL,
  `img` varchar(255) NOT NULL,
  `ref` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `reg_time` int(11) NOT NULL,
  `last_time` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `param` text NOT NULL,
  `icq` varchar(255) NOT NULL,
  `skype` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `ref` (`refer`),
  KEY `pass` (`pass`),
  KEY `ref_2` (`ref`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251";
mpqw($sql);

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `count` int(11) NOT NULL,
  `log` smallint(6) NOT NULL,
  `send` smallint(6) NOT NULL,
  `cmail` int(11) NOT NULL,
  `last` int(11) NOT NULL,
  `max` int(11) NOT NULL,
  `min` int(11) NOT NULL,
  `log_last` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_2` (`name`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251";
mpqw($sql);

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_event_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `description` text NOT NULL,
  `own` varchar(255) NOT NULL,
  `return` varchar(255) NOT NULL,
  `zam` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251";
mpqw($sql);

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_grp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251";
mpqw($sql);

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_mem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `gid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`,`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251";
mpqw($sql);

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `auth` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251";
mpqw($sql);

mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('users', 'user_grp', 'Зарегистрированные', '4', 'Группа зарегистрированных пользователей')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('users', 'admin_grp', 'Администратор', '5', 'Группа администратров')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('users', 'admin_usr', 'mpak', '5', 'Бог')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('users', 'default_usr', 'гость', '1', 'Пользователь при входе')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('users', 'default_grp', 'Гость', '1', 'Группа пользователя при входе')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('users', 'users_field_icq', 'Номер ICQ клиента', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('users', 'users_field_skype', 'Номер скайп клиента', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('users', 'users_field_email', 'Почтовый ящик', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('users', 'users_field_name', 'Логин доступа', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('users', 'users_log', '', '0', 'Логирование событий')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('users', 'user_reg_fields', '', '1', 'Добавочные поля для регистрации через запятую')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('users', 'users_reg_redirect', '/deposit:check', '1', 'Страница перенаправления после регистрации')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('users', 'users_event_memcache_get', 'Использован созданный ранее memcache', '1', 'Событие')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('users', 'users_event_memcache_set', 'Сформирован новый memcache', '1', 'Событие')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('users', 'users_event_image', 'Обновление изображения', '1', 'Событие')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('users', 'users_event_mail', 'Отправка сообщения пользователю', '1', 'Событие')");

?>