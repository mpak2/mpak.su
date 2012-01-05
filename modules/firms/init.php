<? die;

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_index` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `sobst_id` int(11) NOT NULL,
  `intname` varchar(255) NOT NULL,
  `brend` varchar(255) NOT NULL,
  `prof_id` int(11) NOT NULL,
  `sity` varchar(255) NOT NULL,
  `addr` varchar(255) NOT NULL,
  `tz_id` int(11) NOT NULL,
  `http` varchar(255) NOT NULL,
  `prim` varchar(255) NOT NULL,
  `contact_lico` varchar(255) NOT NULL,
  `tel` varchar(255) NOT NULL,
  `doptel` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `icq` int(11) NOT NULL,
  `mop_id` int(11) NOT NULL,
  `mtel` varchar(255) NOT NULL,
  `fax` varchar(255) NOT NULL,
  `dopfax` varchar(255) NOT NULL,
  `skype` varchar(255) NOT NULL,
  `src_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sobst_id` (`sobst_id`),
  KEY `prof_id` (`prof_id`),
  KEY `tz_id` (`tz_id`),
  KEY `mop_id` (`mop_id`),
  KEY `src_id` (`src_id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251";
mpqw($sql);

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_mop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251";
mpqw($sql);

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_prof` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251";
mpqw($sql);

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_sobst` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251";
mpqw($sql);

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_src` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251";
mpqw($sql);

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_tz` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251";
mpqw($sql);

mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('firms', 'firms_index', 'Фирмы', '1', 'Таблица БД')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('firms', 'firms_index_name', 'Название', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('firms', 'firms_index_description', 'Примечание', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('firms', 'firms_index_sobst_id', 'Форма собственности', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('firms', 'firms_index_intname', 'Интернациональное имя', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('firms', 'firms_index_brend', 'Брэнд', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('firms', 'firms_index_prof_id', 'Профиль', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('firms', 'firms_index_sity', 'Город', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('firms', 'firms_index_addr', 'Фактический адрес', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('firms', 'firms_index_tz_id', 'Часовой пояс', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('firms', 'firms_index_http', 'Сайт', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('firms', 'firms_index_contact_lico', 'Контактное лицо', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('firms', 'firms_index_tel', 'Телефон', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('firms', 'firms_index_doptel', 'Дополнительный телефон', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('firms', 'firms_index_email', 'Электронный адрес', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('firms', 'firms_index_icq', 'ICQ', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('firms', 'firms_index_mtel', 'Мобильный телефон', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('firms', 'firms_index_fax', 'Факс', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('firms', 'firms_index_dopfax', 'Дополнительный факс', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('firms', 'firms_index_skype', 'Скайп', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('firms', 'firms_index_src_id', 'Источник', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('firms', 'firms_index_id', 'Номер в базе', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('firms', 'firms_sobst_id', 'Номер в базе', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('firms', 'firms_sobst_name', 'Форма собственности', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('firms', 'firms_sobst_description', 'Дополнительные сведения', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('firms', 'firms_sobst', 'Форма собственности', '1', 'Таблица БД')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('firms', 'firms_prof_id', 'Номер в базе', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('firms', 'firms_prof_name', 'Профиль', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('firms', 'firms_prof_description', 'Примечание', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('firms', 'firms_prof', 'Профиль деятельности', '1', 'Таблица БД')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('firms', 'firms_indey_id', 'Номер в базе', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('firms', 'firms_index_mop_id', 'Мобильный оператор', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('firms', 'firms_tz', 'Часовой пояс', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('firms', 'firms_tz_id', 'Номер в базе', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('firms', 'firms_tz_name', 'Часовой пояс', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('firms', 'firms_tz_description', 'Примечание', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('firms', 'firms_index_uid', 'Владелец', '1', 'Дополнительное поле')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('firms', 'firms_mop', 'Мобильный оператор', '1', 'Таблица БД')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('firms', 'firms_src', 'Источник', '1', 'Таблица БД')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('firms', 'firms_index_prim', 'Примечание', '1', 'Дополнительное поле')");

?>