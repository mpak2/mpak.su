<? die;

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_index` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `img` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251";
mpqw($sql);

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_period` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `year` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `week` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `year` (`year`),
  KEY `week` (`week`),
  KEY `month` (`month`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251";
mpqw($sql);

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `up` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` int(11) NOT NULL,
  `premium` int(11) NOT NULL,
  `hide` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `hide` (`hide`),
  KEY `up` (`up`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251";
mpqw($sql);

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_projects_payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `projects_id` int(11) NOT NULL,
  `name` int(11) NOT NULL,
  `sum` float NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `time` (`time`),
  KEY `projects_id` (`projects_id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251";
mpqw($sql);

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_projects_works` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `projects_id` int(11) NOT NULL,
  `works_id` int(11) NOT NULL,
  `tasks_id` int(11) NOT NULL,
  `period_id` int(11) NOT NULL,
  `duration` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `projects_id` (`projects_id`),
  KEY `works_id` (`works_id`),
  KEY `time` (`time`),
  KEY `uid` (`uid`),
  KEY `tasks_id` (`tasks_id`),
  KEY `period_id` (`period_id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251";
mpqw($sql);

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251";
mpqw($sql);

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `up` int(11) NOT NULL,
  `estimate` int(11) NOT NULL,
  `projects_id` int(11) NOT NULL,
  `tasks_status_id` int(11) NOT NULL DEFAULT '4',
  `workers_id` int(11) NOT NULL,
  `tags_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `time` (`time`),
  KEY `uid` (`uid`),
  KEY `projects_id` (`projects_id`),
  KEY `tasks_status_id` (`tasks_status_id`),
  KEY `estimate` (`estimate`),
  KEY `up` (`up`),
  KEY `works_id` (`workers_id`),
  KEY `tags_id` (`tags_id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251";
mpqw($sql);

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_tasks_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `tasks_id` int(11) NOT NULL,
  `file` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `time` (`time`),
  KEY `uid` (`uid`),
  KEY `tasks_id` (`tasks_id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251";
mpqw($sql);

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_tasks_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hide` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `hide` (`hide`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251";
mpqw($sql);

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_wages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `sum` int(11) NOT NULL,
  `projects_id` int(11) NOT NULL,
  `workers_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `workers_id` (`workers_id`),
  KEY `projects_id` (`projects_id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251";
mpqw($sql);

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_workers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `dismissed` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `works_id` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `time` (`time`),
  KEY `uid` (`uid`),
  KEY `price` (`price`),
  KEY `works_id` (`works_id`),
  KEY `dismissed` (`dismissed`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251";
mpqw($sql);

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_works` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `sort` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251";
mpqw($sql);

mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('cost', 'cost_tasks', 'Задачи', '0', 'Название таблицы')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('cost', 'cost_projects', 'Проект', '0', 'Название таблицы')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('cost', 'cost_workers', 'Работники', '0', 'Название таблицы')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('cost', 'cost_works', 'Работа', '0', 'Название таблицы')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('cost', 'cost_projects_works', 'Учет', '0', 'Название таблицы')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('cost', 'cost_projects_payment', 'Оплаты', '0', 'Название таблицы')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('cost', 'cost_tasks_status', 'СтатусЗадач', '0', 'Название таблицы')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('cost', 'cost_tasks_comments', 'Комменты', '0', 'Название таблицы')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('cost', 'cost_tasks_status_id', '4', '5', 'Значение статуса задачи при создании новой')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('cost', 'cost_period', 'Период', '5', 'Название таблицы')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('cost', 'cost_wages', 'ЗП', '5', 'Название таблицы')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('cost', 'cost_tasks_status=>order', 'sort', '5', 'Название таблицы статусов')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('cost', 'cost=>spisok', 'works,tasks_status,projects,workers,tasks_status,projects,works,tags', '5', 'Справочники')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('cost', 'cost_tags', 'Тег', '5', 'Название таблицы')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('cost', 'cost_tasks=>title', 'time,uid,status_id,tags_id,name', '5', 'Заголовки таблицы')");

?>