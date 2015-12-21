<?

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `parent` int(11) NOT NULL,
  `hide` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `otime` int(11) NOT NULL,
  `text` text NOT NULL,
  `otvet` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `hide` (`hide`),
  KEY `hide_2` (`hide`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251";
mpqw($sql);

mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('gbook', 'gbook_admin_site', 'Администрация сайта', '1', 'Подпись под сообщениями оставленными через админстраницу.')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('gbook', 'gbook_vid_mess', '1', '1', 'Установка видимости сообщений гостевой книги после добавления.')");
mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('gbook', 'gbook_title', 'Здесь вы можете оставить  свои сообщения, вопросы и пожелания.', '1', 'Сообщение в верхней части гостевой книги отображаемое при входе.')");

?>
