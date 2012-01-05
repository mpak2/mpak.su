<? die;

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}` (
  `id` int(11) NOT NULL auto_increment,
  `time` int(11) NOT NULL,
  `poll` varchar(255) NOT NULL,
  `gid` smallint(6) NOT NULL,
  `mult` smallint(6) NOT NULL,
  `golos_time` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251";
mpqw($sql);

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_post` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `poll` int(11) NOT NULL,
  `result` smallint(6) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251";
mpqw($sql);

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_value` (
  `id` int(11) NOT NULL auto_increment,
  `pid` int(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  `color` varchar(100) NOT NULL,
  `result` smallint(6) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251";
mpqw($sql);

?>