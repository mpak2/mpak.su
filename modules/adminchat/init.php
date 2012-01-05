<? die;

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}` (
  `id` int(11) NOT NULL auto_increment,
  `time` int(11) NOT NULL,
  `user` varchar(255) NOT NULL,
  `host` varchar(255) NOT NULL,
  `text` text NOT NULL COMMENT 'Екстра',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251";
mpqw($sql);

?>