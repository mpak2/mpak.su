<? die;

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251";
mpqw($sql);

echo '<p>'.$sql = "CREATE TABLE `{$conf['db']['prefix']}{$arg['modpath']}_img` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `kid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `img` varchar(255) NOT NULL,
  `hide` int(11) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `kid` (`kid`),
  KEY `uid` (`uid`),
  KEY `hide` (`hide`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251";
mpqw($sql);

mpqw("INSERT INTO `{$conf['db']['prefix']}settings` (`modpath`, `name`, `value`, `aid`, `description`) VALUES ('foto', 'foto_lightbox', '<script type=\"text/javascript\" src=\"/include/jquery-lightbox-0.5/js/jquery.lightbox-0.5.js\"></script><link rel=\"stylesheet\" type=\"text/css\" href=\"/include/jquery-lightbox-0.5/css/jquery.lightbox-0.5.css\" media=\"screen\" /><script type=\"text/javascript\">\$(function(){\$(\'#gallery a\').lightBox();});</script>', '1', 'Подключение лайтбокса')");

?>