<?

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `addr` int(11) NOT NULL,
  `open` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `addr` (`addr`),
  KEY `open` (`open`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
mpqw($sql);

mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('messages', 'messages_display', '0', '1', 'Блок сообщений всегда виден на главной странице')");

?>
