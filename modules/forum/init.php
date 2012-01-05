<? die;

echo '<p>'.$sql = "CREATE ALGORITHM=UNDEFINED DEFINER=`shop_mpak_su`@`localhost` SQL SECURITY DEFINER VIEW `{$conf['db']['prefix']}{$arg['modpath']}_cmess` AS select `{$conf['db']['prefix']}{$arg['modpath']}_mess`.`uid` AS `uid`,count(0) AS `count` from `{$conf['db']['prefix']}{$arg['modpath']}_mess` group by `{$conf['db']['prefix']}{$arg['modpath']}_mess`.`uid`";
mpqw($sql);

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_mess` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `vetki_id` int(11) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `vetki_id` (`vetki_id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251";
mpqw($sql);

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_vetki` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vetki_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `aid` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `mess` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `vetki_id` (`vetki_id`),
  KEY `aid` (`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251";
mpqw($sql);

mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('forum', 'forum_parent_vetki', 'services_obj', '1', 'Таблица родительских веток')");

?>