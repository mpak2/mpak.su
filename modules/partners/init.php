<? die;

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_index` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `img` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `href` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251";
mpqw($sql);

mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('partners', 'partners_tpl_exceptions', '1', '4', '')");

?>