<?

if(mpsettings($t = "data_cat", "Категории") && !tables($table = ("{$conf['db']['prefix']}{$t}"))){
	qw("CREATE TABLE `{$table}` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `description` text NOT NULL,
	  `name` varchar(255) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8");
} 
if(mpsettings($t = "data_index", 'Данные') && !tables($table = ("{$conf['db']['prefix']}{$t}"))){
	qw("CREATE TABLE `{$table}` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `time` int(11) NOT NULL,
	  `cat_id` int(11) NOT NULL,
	  `type_id` int(11) NOT NULL,
	  `uid` int(11) NOT NULL,
	  `img` varchar(255) NOT NULL,
	  `name` varchar(255) DEFAULT NULL,
	  `file` varchar(255) NOT NULL,
	  `w` int(11) NOT NULL,
	  `h` int(11) NOT NULL,
	  `c` int(11) NOT NULL,
	  `count` int(11) NOT NULL,
	  `hide` smallint(6) NOT NULL,
	  `md5` varchar(255) NOT NULL,
	  `cat_id_item` int(11) DEFAULT NULL,
	  PRIMARY KEY (`id`),
	  KEY `type_id` (`type_id`),
	  KEY `cat_id` (`cat_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8");
}
if(mpsettings($t = "data_type", "Типы") && !tables($table = ("{$conf['db']['prefix']}{$t}"))){
	qw("CREATE TABLE `{$table}` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `img` varchar(255) NOT NULL,
	  `name` varchar(255) NOT NULL,
	  `description` text NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8");
}
mpsettings("{$arg['modpath']}_index=>title", "cat_id,img,file,hide,name,type_id,w,h,c");
mpsettings("{$arg['modpath']}_tpl_exceptions", "1");
?>
