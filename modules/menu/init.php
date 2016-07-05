<? # modules/menu/init.php

if(mpsettings($t = "menu_index", "Меню") && !tables($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `index_id` int(11) NOT NULL,
  `region_id` int(11) NOT NULL DEFAULT '2',
  `img` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `href` varchar(255) NOT NULL DEFAULT '/',
  `sort` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `orderby` (`sort`),
  KEY `index_id` (`index_id`),
  KEY `region_id` (`region_id`),
  KEY `sort` (`sort`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251");
} if(mpsettings($t = "menu_region", "Регионы") && !tables($table = ("{$conf['db']['prefix']}{$t}"))){
qw("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251");
} 

mpsettings("{$arg['modpath']}_index=>order", "sort");
