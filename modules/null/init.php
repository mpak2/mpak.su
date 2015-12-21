<?

mpqw($sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_index` (
	`id` int(11) NOT NULL auto_increment,
	`time` int(11) NOT NULL,
	`uid` int(11) NOT NULL,
	`img` varchar(255) NOT NULL,
	`name` varchar(255) NOT NULL,
	`description` text NOT NULL,
	PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251"); echo "<p>{$sql}";

mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('{$arg['modpath']}', '{$arg['modpath']}_tpl_exceptions', 1, 4, '')");
