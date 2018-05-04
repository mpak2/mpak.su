<?

<? # modules/users/init.php

if(mpsettings($t = "users", "Пользователи") && !tables($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL,
  `refer` int(11) NOT NULL,
  `img` varchar(255) NOT NULL,
  `ref` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
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
  KEY `ref_2` (`ref`),
  KEY `type_id` (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");
} if(mpsettings($t = "users_event", "События") && !tables($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `up` int(11) NOT NULL COMMENT 'Последнее обновление события',
  `uid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `hide` smallint(6) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_2` (`name`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");
} if(mpsettings($t = "users_event_logs", "Журнал") && !tables($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");
} if(mpsettings($t = "users_event_mess", "Сообщения") && !tables($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `event_notice_id` int(11) NOT NULL,
  `dst` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `response` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `time` (`time`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");
} if(mpsettings($t = "users_event_notice", "Уведомления") && !tables($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `grp_id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `login` varchar(255) NOT NULL,
  `event_id` int(11) NOT NULL,
  `log` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `zam` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`),
  KEY `uid` (`uid`),
  KEY `grp_id` (`grp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");
} if(mpsettings($t = "users_event_params", "Параметры") && !tables($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");
} if(mpsettings($t = "users_event_value", "Значение") && !tables($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");
} if(mpsettings($t = "users_grp", "Группы") && !tables($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");
} if(mpsettings($t = "users_sess", null) && !tables($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `ref` varchar(255) NOT NULL,
  `refer` int(11) NOT NULL,
  `last_time` int(11) NOT NULL,
  `count_time` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  `cnull` int(11) NOT NULL,
  `sess` varchar(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `agent` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `last_time` (`last_time`),
  KEY `uid` (`uid`,`cnull`,`count`),
  KEY `ip` (`ip`),
  KEY `agent` (`agent`),
  KEY `sess` (`sess`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");
} if(mpsettings($t = "users_type", "Типы") && !tables($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `auth` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");
} if(mpsettings($t = "users_event_values", "Значения") && !tables($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `event_logs_id` int(11) DEFAULT NULL,
  `event_params_id` int(11) DEFAULT NULL,
  `event_value_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_params_id` (`event_params_id`),
  KEY `event_value_id` (`event_value_id`),
  KEY `event_logs_id` (`event_logs_id`),
  CONSTRAINT `mp_users_event_values_ibfk_1` FOREIGN KEY (`event_logs_id`) REFERENCES `mp_users_event_logs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8");
} if(mpsettings($t = "users_mem", "Участники") && !tables($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `grp_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`,`grp_id`),
  CONSTRAINT `mp_users_mem_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `mp_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8");
}

mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('{$arg['modpath']}', '{$arg['modpath']}', 'Пользователи', '4', '')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('{$arg['modpath']}', '{$arg['modpath']}_event', 'События', '4', '')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('{$arg['modpath']}', '{$arg['modpath']}_event_logs', 'Журнал', '4', '')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('{$arg['modpath']}', '{$arg['modpath']}_event_mess', 'Сообщения', '4', '')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('{$arg['modpath']}', '{$arg['modpath']}_event_notice', 'Уведомления', '4', '')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('{$arg['modpath']}', '{$arg['modpath']}_users_grp', 'Группы', '4', '')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('{$arg['modpath']}', '{$arg['modpath']}_mem', 'Участники', '4', '')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('{$arg['modpath']}', '{$arg['modpath']}_type', 'Типы', '4', '')");

mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('{$arg['modpath']}', 'user_grp', 'Зарегистрированные', '4', 'Группа зарегистрированных пользователей')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('{$arg['modpath']}', 'admin_grp', 'Администратор', '5', 'Группа администратров')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('{$arg['modpath']}', 'admin_usr', 'mpak', '5', 'Бог')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('{$arg['modpath']}', 'default_usr', 'гость', '1', 'Пользователь при входе')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('{$arg['modpath']}', 'default_grp', 'Гость', '1', 'Группа пользователя при входе')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('{$arg['modpath']}', 'user_reg_fields', '', '1', 'Добавочные поля для регистрации через запятую')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('{$arg['modpath']}', 'user_diff_fields', '', '1', 'Добавочные скрываемые в лк')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('{$arg['modpath']}', '{$arg['modpath']}_field_icq', 'Номер ICQ клиента', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('{$arg['modpath']}', '{$arg['modpath']}_field_skype', 'Номер скайп клиента', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('{$arg['modpath']}', '{$arg['modpath']}_field_email', 'Почтовый ящик', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('{$arg['modpath']}', '{$arg['modpath']}_field_name', 'Логин доступа', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('{$arg['modpath']}', '{$arg['modpath']}_log', '', '0', 'Логирование событий')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('{$arg['modpath']}', '{$arg['modpath']}_reg_redirect', '/', '1', 'Страница перенаправления после регистрации')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('{$arg['modpath']}', '{$arg['modpath']}_event_memcache_get', 'Использован созданный ранее memcache', '1', 'Событие')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('{$arg['modpath']}', '{$arg['modpath']}_event_memcache_set', 'Сформирован новый memcache', '1', 'Событие')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('{$arg['modpath']}', '{$arg['modpath']}_event_image', 'Обновление изображения', '1', 'Событие')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('{$arg['modpath']}', '{$arg['modpath']}_event_mail', 'Отправка сообщения пользователю', '1', 'Событие')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('{$arg['modpath']}', '{$arg['modpath']}_activation', '', '0', 'Активация пользователя')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('{$arg['modpath']}', '{$arg['modpath']}_reg_text', 'Спасибо за регистрацию. Рады приветствовать вас на нашем сайте', '0', 'Текст при регистрации')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('{$arg['modpath']}', '{$arg['modpath']}_tpl_exceptions', '1', '0', 'Выпадающие списки')");

mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('{$arg['modpath']}', 'sess_time', '3600', '1', 'Время сессии')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('{$arg['modpath']}', 'del_sess', '0', '1', 'Отслеживание сессий')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('{$arg['modpath']}', '{$arg['modpath']}_index', 'Статьи', '4', '')");
