<?

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}` (
  `id` int(11) NOT NULL auto_increment,
  `param` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `param` (`param`,`value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
mpqw($sql);

?>
